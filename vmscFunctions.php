<?php

	/* Dev can't use this
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;
	require 'PHPMailer/src/Exception.php';
	require 'PHPMailer/src/PHPMailer.php';
	require 'PHPMailer/src/SMTP.php';
	*/
    //require_once 'vendor/autoload.php';
    


function db_connect(){
    $connect=1;
    ///Development
    if($connect === 1){
        $db='ngopilot';
        $host='localhost';
        $login='ngopilot';
        $passwd='3213219514ZXCZXC';
    }
    ///Production
    if($connect === 2){
        $db='ngopilot';
        $host='localhost';
        $login='ngopilot';
        $passwd='3213219514ZXCZXC';
    }

    $conn=mysqli_connect($host,$login,$passwd,$db);
    if(!$conn){
        echo('<br><h4 style="font-size:20px">Error connecting</h4><br>');
    }
    return $conn;
}
function query($types,$params,$query){
    $conn=db_connect();
    $fullparams=array();
    $fullparams[]= $types;
    foreach($params as $paramkey => $paramvalue){
        $fullparams[]=& $params[$paramkey];
    }
    $sql=$conn->prepare($query);
    call_user_func_array(array($sql,'bind_param'),$fullparams);
    //var_dump($sql);
    $sql->execute();
    $result=$sql->get_result();
    mysqli_close($conn);
    return $result;
}
function str_clean($str){
    return filter_var($str,FILTER_SANITIZE_STRING);
}
function int_clean($int){
    return filter_var($int,FILTER_VALIDATE_INT);
}
function arr_clean($array){
    return filter_var($array, FILTER_SANITIZE_NUMBER_INT);
}
function idscramble($name){
    return $name;
}
function idunscramble($name){
    return $name;
}
function valunscramble($val){
    return $val;
}
function valscramble($val){
    return $val;
}
function timer($val,$userid){
    //For Dev -> $val=1000
    $val=1000;
    $query='select LogId,timestampdiff(minute,LoggedOn,now()) as Diff
			  from logs
		     where LogId=(select max(LogId)
						    from logs
						   where UserId=?)';
    $types='i';
    $params=array($userid);
    $diff=0;
    $logid=0;
    $result=query($types,$params,$query);
    while($row=$result->fetch_assoc()){
        $logid=$row['LogId'];
        $diff=$row['Diff'];
    }mysqli_free_result($result);
    //echo('Timer Function: Val: '.$val.'Diff in min.: '.$diff.' LogId: '.$logid.' ');
    if($diff>$val){
        #echo('<br>'.$diff.'<br>');
       // header('Location:logout.php?'.idscramble('userid').'='.valscramble($userid).'&'.idscramble('logid').'='.valscramble($logid));
    }
}
function style(){
    echo("
		");
}
function navhtml($userid,$secid,$navid){
    //$date=strtotime(date('y-m-d h:i:s'));
    $name='';
    $query='select NavId,Link,SecId
			  from navmenu
			 where SecId>=?';
    $types="i";
    $params=array($secid);
    $result=query($types,$params,$query);

    echo('<div id="nav"><ul>');
    while($row=$result->fetch_assoc()){
        echo('<li><a ');
        if($navid==$row['NavId']){
            echo('style="background-color:#cccccc;" ');
        }
        echo('href="'.$row['Link'].'.php?'.idscramble('userid').'='.valscramble($userid).
            '&'.idscramble('secid').'='.valscramble($secid).
            '&'.idscramble('navid').'='.valscramble($navid).' ">'.$row['Link'].'</a></li>');
    }
    mysqli_free_result($result);
    $query='select Name from users where UserId=?';
    $types='i';
    $params=array($userid);
    $result=query($types,$params,$query);
    while($row=$result->fetch_assoc()){
        $name=$row['Name'];
    }
    mysqli_free_result($result);
    echo('<li style="float:right;" >
			<div id="user">'.$name.' you are logged in.<br>
				<a style="padding:4px 10px" href="logout.php?'.idscramble('userid').'='.valscramble($userid).' ">Log Out</a>
			</div></li></ul></div>');
}
function logs($userid,$action,$page,$array,$tables,$activeid=null){

    //echo('<br>Just in logs()<pre>');var_dump( array( $userid,$action,$page,$array,$tables,$activeid ) );echo('</pre>');

    $dump='Params:';
    foreach($array as $val){
        $dump.=$val.'::';
    }
    
    $dump.='tables:';
    foreach($tables as $table){
        $dump.=$table.':';
    }
    
    $query='insert into logs(UserId,LoggedOn,Page,Action,Dump,ActiveId)values(?,now(),?,?,?,?)';
    $types='isssi';
    $params=array($userid,$page,$action,$dump,$activeid);
    query($types,$params,$query);

}
function upload($userid,$personid,$page,$filetype,$multiple,$sickid=0,$discid=0){
    /*
     //	Functions	:: logs($userid,$action,$page,$array,$tables,$activeid=null)
     //				:: bytescalc($size, $precision = 0)
     // $userid 	:: required for logs
     //	$page		:: required for logs
     //	$filetype 	:: image,file,video
     //	$multiple 	:: 0=single,1=multiple

     */
    $images=array('jpg','jpeg','gif','png','svg');
    $docs=array('pdf','docx','docm','doc','xls','xlsx','ppt','pptx','pub','rtf','txt','odt','ods');
    $vids=array('mp4','avi','wmv','avi');
    $filename='';
    $filesize='';
    $reply='';
    $action='u';
    //Single
    if($multiple==0){
        if(isset($_FILES['file']) && !empty($_FILES['file']) && $_FILES['file']['error'] === 0 && substr_count($_FILES['file']['name'],".") === 1){
            $ext=substr($_FILES['file']['name'],strripos($_FILES['file']['name'],".")+1,strlen($_FILES['file']['name']));
            $ext=strtolower($ext);
            $filename=substr($_FILES['file']['name'],0,strripos($_FILES['file']['name'],"."));
            //$filename=$_FILES['file']['name'];
            $filesize=$_FILES['file']['size'];

            switch ($filetype){
                case 'image':
                    if(in_array($ext,$images) === true && $filesize <= 10485760){
                        //echo('In image<br>');
                        $query='insert into personimages(PersonId,Ext,UploadedOn,Name)
									values(?,?,now(),?)';
                        $types="iss";
                        $params=array($personid,$ext,$filename);
                        $result=query($types,$params,$query);

                        $query='select max(ImageId) as ImageId,Ext from personimages where PersonId=?';
                        $types="i";
                        $params=array($personid);
                        $result=query($types,$params,$query);
                        while($row=$result->fetch_assoc()){
                            $imageid=$row['ImageId'];
                            $ext=$row['Ext'];
                        }mysqli_free_result($result);
                        //echo('<pre>');var_dump($_FILES);//echo('</pre>');
                        $path="include/personimages/".$imageid.".".$ext;
                        //echo("<br>TMP PATH".$_FILES['file']['tmp_name']."<br>NEW PATH".$path);
                        //echo exec('<br>whoami');
                        move_uploaded_file($_FILES['file']['tmp_name'],$path);
						$reply.='Your file '.substr($filename,0,9).' was uploaded.<br>size: '.bytescalc($filesize,2).'';

                        $tables=array('personimages');
                        $array=array('PersonId: '.$personid,'Temp file:'
                            .$_FILES['file']['tmp_name'],'Final file: '
                            .$path,'Your file '.$filename.' (size: '
                            .bytescalc($filesize,2).') was successfully uploaded.');
                        logs($userid,$action,$page,$array,$tables);
                        unset($_FILES);
                    }else{
                        unset($_FILES);
                        $reply.='Your image '
                            .$filename.' (size: '
                                .bytescalc($filesize,2).') was not accepted.<br>Only the following image types are excepted: '
                                    .implode(", ",$images).'.<br>Please make sure you upload the right image type.';
                    }
                    break;
                case 'disc':
                    if(in_array($ext,$docs) === true && $filesize <= 10485760){
                        $query='insert into persondocs(DocId,PersonId,Ext,UploadedOn,Name,DiscId)
							values(0,?,?,now(),?,?)';
                        $types="issi";
                        $params=array($personid,$ext,$filename,$discid);
                        $result=query($types,$params,$query);

                        $query='select max(DocId) as DocId ,Ext from persondocs where PersonId=?';
                        $types="i";
                        $params=array($personid);
                        $result=query($types,$params,$query);
                        while($row=$result->fetch_assoc()){
                            $docid=$row['DocId'];
                            $ext=$row['Ext'];
                        }mysqli_free_result($result);
                        $path="include/persondocs/".$docid.".".$ext;

                        move_uploaded_file($_FILES['file']['tmp_name'],$path);
						//echo("<br>".$_FILES['file']['tmp_name']);
                        $reply.='Your file '.substr($filename,0,9).' was uploaded.<br>size: '.bytescalc($filesize,2).'';

                        $tables=array('persondocs');
                        $array=array('PersonId: '.$personid,'Temp file:'
                            .$_FILES['file']['tmp_name'],'Final file: '
                            .$path,'Your file '.$filename.' (size: '
                            .bytescalc($filesize,2).') was successfully uploaded.');
                        logs($userid,$action,$page,$array,$tables);
                        unset($_FILES);
                    }else{
                        unset($_FILES);
                        $reply.='Your file '
                            .$filename.' (size: '
                                .bytescalc($filesize,2).') was not accepted.
						<br>Only the following file types are excepted: '
                                    .implode(", ",$docs).'.
						<br>Please make sure you upload the right file type.';
                    }
                    break;
				case 'intake':
                    if(in_array($ext,$docs) === true && $filesize <= 10485760){
                        $query='insert into personintakedocs(IntakeId,PersonId,Ext,UploadedOn,Name)
							values(0,?,?,now(),?)';
                        $types="iss";
                        $params=array($personid,$ext,$filename);
                        $result=query($types,$params,$query);

                        $query='select max(IntakeId) as IntakeId ,Ext from personintakedocs where PersonId=?';
                        $types="i";
                        $params=array($personid);
                        $result=query($types,$params,$query);
                        while($row=$result->fetch_assoc()){
                            $intakeid=$row['IntakeId'];
                            $ext=$row['Ext'];
                        }mysqli_free_result($result);
                        $path="include/personintakedocs/".$intakeid.".".$ext;
                        //echo("<br>".$_FILES['file']['tmp_name']." ".$path);
                        
                        move_uploaded_file($_FILES['file']['tmp_name'],$path);
						$reply.='Your file '.substr($filename,0,9).' was uploaded. Size: '.bytescalc($filesize,2).'';

                        $tables=array('personintakedocs');
                        $array=array('PersonId: '.$personid,'Temp file:'
                            .$_FILES['file']['tmp_name'],'Final file: '
                            .$path,'Your file '.$filename.' (size: '
                            .bytescalc($filesize,2).') was successfully uploaded.');
                        logs($userid,$action,$page,$array,$tables);
                        unset($_FILES);
                    }else{
                        unset($_FILES);
                        $reply.='Your file '
                            .$filename.' (size: '
                                .bytescalc($filesize,2).') was not accepted.
						<br>Only the following file types are excepted: '
                                    .implode(", ",$docs).'.
						<br>Please make sure you upload the right file type.';
                    }
                    break;
                case 'video':
                    if(in_array($ext,$vids) === true && $filesize <= 104857600){
                        $query='insert into personvids(VidId,PersonId,Ext,UploadedOn,Name)
							values(0,?,?,now(),?)';
                        $types="iss";
                        $params=array($personid,$ext,$filename);
                        $result=query($types,$params,$query);

                        $query='select max(VidId) as VidId,Ext from personvids where PersonId=?';
                        $types="i";
                        $params=array($personid);
                        $result=query($types,$params,$query);
                        while($row=$result->fetch_assoc()){
                            $vidid=$row['VidId'];
                            $ext=$row['Ext'];
                        }mysqli_free_result($result);
                        $path='include/personvids/'.$vidid.'.'.$ext;

                        move_uploaded_file($_FILES['file']['tmp_name'],$path);
                        $reply.='Your file '.substr($filename,0,9).' was uploaded.<br>size: '.bytescalc($filesize,2).'';

                        $tables=array('personvids');
                        $array=array('PersonId: '.$personid,'Temp file:'
                            .$_FILES['file']['tmp_name'],'Final file: '
                            .$path,'Your video '
                            .$filename.' (size: '
                            .bytescalc($filesize,2).') was successfully uploaded.');
                        logs($userid,$action,$page,$array,$tables);
                        unset($_FILES);
                    }else{
                        unset($_FILES);
                        $reply.='Your video '
                            .$filename.' (size: '
                                .bytescalc($filesize,2).') was not accepted.
						<br>Only the following video types are excepted: '
                                    .implode(", ",$vids).'.
						<br>Please make sure you upload the right video type.';
                    }
                    break;
                case 'doc':
                    if(in_array($ext,$docs) === true && $filesize <= 10485760){
                        $query='insert into doctorsnotes(NoteId,SickId,Ext,UploadedOn,Name)
							values(0,?,?,now(),?)';
                        $types="iss";
                        $params=array($sickid,$ext,$filename);
                        $result=query($types,$params,$query);

                        $query='select max(NoteId) as DocId ,Ext from doctorsnotes where SickId=?';
                        $types="i";
                        $params=array($sickid);
                        $result=query($types,$params,$query);
                        while($row=$result->fetch_assoc()){
                            $docid=$row['DocId'];
                            $ext=$row['Ext'];
                        }mysqli_free_result($result);
                        $path="include/doctorsnotes/".$docid.".".$ext;

                        move_uploaded_file($_FILES['file']['tmp_name'],$path);
						//echo("<br>".$_FILES['file']['tmp_name']);

                        $tables=array('doctorsnotes');
                        $array=array('PersonId: '.$personid,'Temp file:'
                            .$_FILES['file']['tmp_name'],'Final file: '
                            .$path,'Your note '
                            .$filename.' (size: '
                            .bytescalc($filesize,2).') was successfully uploaded.');
                        logs($userid,$action,$page,$array,$tables);
                        unset($_FILES);
                        $reply.='Your note '.$filename.' (size: '.bytescalc($filesize,2).') was successfully uploaded.';
                    }else{
                        unset($_FILES);
                        $reply.='Your doc '
                            .$filename.' (size: '
                                .bytescalc($filesize,2).') was not accepted.
						<br>Only the following doc types are excepted: '
                                    .implode(", ",$docs).'.
						<br>Please make sure you upload the right doc type.';
                    }
                    break;
            }
        }else{
            unset($_FILES);
            $reply.='Your file '.$filename.' (size: '.bytescalc($filesize,2).') was not accepted.
			<br>Please make sure that no "." are in the filename';
        }
    }
    //Multiple
   /*
    if($multiple==1){
        $accepted=array();
        $rejected=array();
        if(isset($_FILES['files']) && !empty($_FILES['files'])){
            $files=$_FILES['files'];

            foreach($files['name'] as $cnt => $filename){
                $filetmp=$files['tmp_name'][$cnt];
                $filesize=$files['size'][$cnt];
    $fileerror=$files['error'][$cnt];

    if($fileerror === 0 && substr_count($filename,".") === 1){
    $ext=substr($filename,strripos($filename,".")+1,strlen($filename));
    $ext=strtolower($ext);
    $filename=substr($_FILES['file']['name'],0,strripos($_FILES['file']['name'],"."));
    switch ($filetype){
    case 'image':
    if(in_array($ext,$images) === true && $filesize <= 10485760){
    $query='insert into personimages(ImageId,PersonId,Ext,UploadedOn,Name)
    values(null,?,?,now(),?)';
    $types="iss";
    $params=array($personid,$ext,$filename);
    $result=query($types,$params,$query);

    $query='select max(ImageId) as ImageId,Ext from personimages where PersonId=?';
    $types="i";
    $params=array($personid);
    $result=query($types,$params,$query);
    while($row=$result->fetch_assoc()){
    $imageid=$row['ImageId'];
    $ext=$row['Ext'];
    }mysqli_free_result($result);
    $path='include/personimages/'.$imageid.'.'.$ext;

    move_uploaded_file($filetmp,$path);

    $tables=array('personimages');
    $array=array('PersonId: '.$personid,'Temp file:'
    .$filetmp,'Final file: '
    .$path,'Your file '
    .$filename.' (size: '
    .bytescalc($filesize,2).') was successfully uploaded.');
    logs($userid,$action,$page,$array,$tables);
    $accepted[$cnt]='Your image '
    .$filename.' (size: '
    .bytescalc($filesize,2).') was successfully uploaded.<br>';
    }else{
    $rejected[$cnt]='Your image '
    .$filename.' (size: '
    .bytescalc($filesize,2).') was not accepted.
    <br>Only the following image types are excepted: '
    .implode(", ",$images).'.
    <br>Please make sure you upload the right image type.<br>';
    }
    break;
    case 'file':
    if(in_array($ext,$docs) === true && $filesize <= 10485760){
    $query='insert into persondocs(DocId,PersonId,Ext,UploadedOn,Name)
    values(null,?,?,now(),?)';
    $types="iss";
    $params=array($personid,$ext,$filename);
    $result=query($types,$params,$query);

    $query='select max(DocId) as DocId ,Ext from persondocs where PersonId=?';
    $types="i";
    $params=array($personid);
    $result=query($types,$params,$query);
    while($row=$result->fetch_assoc()){
    $docid=$row['DocId'];
    $ext=$row['Ext'];
    }mysqli_free_result($result);
    $path='include/persondocs/'.$docid.'.'.$ext;

    move_uploaded_file($filetmp,$path);

    $tables=array('persondocs');
    $array=array('PersonId: '.$personid,'Temp file:'
    .$filetmp,'Final file: '
    .$path,'Your file '
    .$filename.' (size: '
    .bytescalc($filesize,2).') was successfully uploaded.');
    logs($userid,$action,$page,$array,$tables);
    $accepted[$cnt]='Your file '
    .$filename.' (size: '
    .bytescalc($filesize,2).') was successfully uploaded.<br>';
    }else{
    $rejected[$cnt]='Your file '
    .$filename.' (size: '
    .bytescalc($filesize,2).') was not accepted.
    <br>Only the following file types are excepted: '
    .implode(", ",$docs).'.
    <br>Please make sure you upload the right file type.';
    }
    break;
    case 'doc':
    if(in_array($ext,$docs) === true && $filesize <= 10485760){
    $query='insert into doctorsnotes(NoteId,SickId,Ext,UploadedOn,Name)
    values(null,?,?,now(),?)';
    $types="iss";
    $params=array($sickid,$ext,$filename);
    $result=query($types,$params,$query);

    $query='select max(NoteId) as DocId ,Ext from doctorsnotes where SickId=?';
    $types="i";
    $params=array($sickid);
    $result=query($types,$params,$query);
    while($row=$result->fetch_assoc()){
    $docid=$row['NoteId'];
    $ext=$row['Ext'];
    }mysqli_free_result($result);
    $path='include/doctorsnotes/'.$docid.'.'.$ext;

    move_uploaded_file($filetmp,$path);

    $tables=array('doctorsnotes');
    $array=array('PersonId: '.$personid.' SickId: '.$sickid,'Temp file:'
    .$filetmp,'Final file: '
    .$path,'Your file '
    .$filename.' (size: '
    .bytescalc($filesize,2).') was successfully uploaded.');
    logs($userid,$action,$page,$array,$tables);
    $accepted[$cnt]='Your file '
    .$filename.' (size: '
    .bytescalc($filesize,2).') was successfully uploaded.<br>';
    }else{
    $rejected[$cnt]='Your file '
    .$filename.' (size: '
    .bytescalc($filesize,2).') was not accepted.
    <br>Only the following file types are excepted: '
    .implode(", ",$docs).'.
    <br>Please make sure you upload the right file type.';
    }
    break;
    case 'video':
    if(in_array($ext,$vids) === true && $filesize <= 104857600){
    $query='insert into personvids(VidId,PersonId,Ext,UploadedOn,Name)
    values(null,?,?,now(),?)';
    $types="iss";
    $params=array($personid,$ext,$filename);
    $result=query($types,$params,$query);

    $query='select max(VidId) as VidId,Ext from personvids where PersonId=?';
    $types="i";
    $params=array($personid);
    $result=query($types,$params,$query);
    while($row=$result->fetch_assoc()){
    $vidid=$row['VidId'];
    $ext=$row['Ext'];
    }mysqli_free_result($result);
    $path='include/personvids/'.$vidid.'.'.$ext;

    move_uploaded_file($filetmp,$path);

    $tables=array('personvids');
    $array=array('PersonId: '
    .$personid,'Temp file:'
    .$filetmp,'Final file: '
    .$path,'Your video '
    .$filename.' (size: '
    .bytescalc($filesize,2).') was successfully uploaded.');
    logs($userid,$action,$page,$array,$tables);
    $accepted[$cnt]='Your file '
    .$filename.' (size: '
    .bytescalc($filesize,2).') was successfully uploaded.<br>';
    }else{
    $rejected[$cnt]='Your video '
    .$filename.' (size: '
    .bytescalc($filesize,2).') was not accepted.
    <br>Only the following video types are excepted: '.implode(", ",$vids).'.
    <br>Please make sure you upload the right video type.<br>';
    }
    break;
    }
    }else{
    $rejected[$cnt]='Your file '.$filename.' (size: '.bytescalc($filesize,2).') was not accepted.
    <br>Please make sure that no "." are in the filename';
    }
    }
    unset($_FILES);
    }else{
    $reply.='No files were selected.';
    }
    $reply.=implode('<br>',$accepted);
    $reply.=implode('<br>',$rejected);
    }
     */

    return $reply;
}
function bytescalc($size, $precision = 0){
    $unit = ['Byte','KiB','MiB','GiB','TiB','PiB','EiB','ZiB','YiB'];
    for($i = 0; $size >= 1024 && $i < count($unit)-1; $i++){
        $size /= 1024;
    }
    return round($size, $precision).' '.$unit[$i];
}
function secure($secid){
    return $secid;
}
function checklogs($userid){
    return 1;
}
function verifyuser($userid,$email){
    $query='select count(UserId) as Cnt from users where UserId=? and Email=?';
    $types="is";
    $params=array($userid,$email);
    $result=query($types,$params,$query);
    while($row=$result->fetch_assoc()){
        $cnt=$row['Cnt'];
    }mysqli_free_result($result);
    return $cnt;
}
function keymail($userid,$key){
	$subject='Here is the key to edit the counsellor note.';
	$body='<p>Please copy and paste the key into the form.<p>'.$key.'</p>';

	mailmessage($userid,$subject,$body);
}

function mailbdays($userid){
	$body='';
	$subject='Birthday List of Vistarus personnel';
	$cnt=0;
    $query='select count(*) as Cnt
    		 from perssonel P
				  join (select PersonId,max(EntryDate) as EntryDate,max(ExitDate) as ExitDate
						  from personentryexit
						 group by PersonId) X
					on P.PersonId=X.PersonId
				 where length(ltrim(IDNo))>7
				   and dayofmonth(date(now()))=convert(substring(ltrim(IDNo),5,2) ,INTEGER)
				   and month(date(now()))=convert(substring(ltrim(IDNo),3,2) ,INTEGER)
				   and (EntryDate is not null or EntryDate != Date("0000-00-00"))
				   and (ExitDate is null or Exitdate=Date("0000-00-00"))
               and 1=?';
	$types='i';
	$params=array(1);
	$result=query($types,$params,$query);
	while($row=$result->fetch_assoc()){
    	$cnt=$row['Cnt'];
    }mysqli_free_result($result);
	if($cnt>0){
        $query='select P.Name,
					   P.Surname,
                       case when char_length(IDNo)>7 then 
                        floor(
                            datediff(
                                    date(
                                        now()
                                    ),
                                    date(
                                        concat(
                                            case when substring(ltrim(IDNo),1,1)<=2 
                                                 then concat("20","",substring(ltrim(IDNo),1,2)) 
                                                 else concat("19","",substring(ltrim(IDNo),1,2)) end,
                                            "-",
                                            substring(ltrim(IDNo),3,2),
                                            "-",
                                            substring(ltrim(IDNo),5,2)
                                        )
                                    )
                                )/365.25
                            ) else "Undetermined" end as Age
				  from perssonel P
				  join (select PersonId,max(EntryDate) as EntryDate,max(ExitDate) as ExitDate
						  from personentryexit
						 group by PersonId) X
					on P.PersonId=X.PersonId
				 where length(ltrim(IDNo))>7
				   and dayofmonth(date(now()))=convert(substring(ltrim(IDNo),5,2) ,INTEGER)
				   and month(date(now()))=convert(substring(ltrim(IDNo),3,2) ,INTEGER)
				   and (EntryDate is not null or EntryDate != Date("0000-00-00"))
				   and (ExitDate is null or Exitdate=Date("0000-00-00"))
				   and 1=?';
    	$types='i';
    	$params=array(1);
    	$result=query($types,$params,$query);
    	$body='<p>The following have birthdays today</p><table border="0"><tr><th>Name</th><th>Surname</th><th>Age</th></tr>';
    	while($row=$result->fetch_assoc()){
	        $body.='<tr><td>'.$row['Name'].'</td><td>'.$row['Surname'].'</td><td>'.$row['Age'].'</td></tr>';
	    }mysqli_free_result($result);
	    $body.='</table>';
    }else{
    	$body.='<p>No one at Vistarus celebrates their birthday today.</p>';
    }

	mailmessage($userid,$subject,$body);
}

function mailmessage($userid,$subject,$body){

	$name='';
	$email='';

	$query='select Name,Surname,Email from users where UserId=?';
	$types="i";
	$params=array($userid);
	$result=query($types,$params,$query);
	while($row=$result->fetch_assoc()){
		$name=$row['Name'].' '.$row['Surname'];
		$email=$row['Email'];
	}mysqli_free_result($result);


	$mail = new PHPMailer(true);                          // Passing `true` enables exceptions
    try {
        //Server settings
        $mail->SMTPDebug = 0;                                 // Enable verbose debug output
        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'smtp.vistarus.co.za';                  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'it@vistarus.co.za';                // SMTP username
        $mail->Password = 'cV3#JxCt49a';                      // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587;                                    // TCP port to connect to
        $mail->SMTPOptions = array(
                'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );
        //Recipients
        //echo('<br>'.$email.'<br>'.$name.'<br>');
        $mail->setFrom('it@vistarus.co.za', 'Vistarus Personnel Management System');
        $mail->addAddress($email, $name);     // Add a recipient
        //$mail->addReplyTo('it@vistarus.co.za', 'System');

        //Content
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body    = $body;

        $mail->send();
        //echo 'Message has been sent';
	} catch (Exception $e) {
		echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
	}
}



function userloginupdate(){
    // Other option to have instead of now in case statement is : max(LoggedOn)

    $query = 'select UserId, unix_timestamp(now()), unix_timestamp(now())-10 
               from userlogin 
              where LoggedIn > unix_timestamp(now()) - 10 
                and (LoggedOut is null 
                 or LoggedOut = "0000-00-00 00:00:00")';


	$query='update userlogin UL
			   set UL.LoggedOut=(select case when (UNIX_TIMESTAMP(now())-max(UNIX_TIMESTAMP(LoggedOn)))>10
											 then max(LoggedOn) else "" end
								   from logs L
								  where L.UserId=UL.UserId )
			 where UL.LoggedOut= "0000-00-00 00:00:00"
                OR UL.LoggedOut is ?';

	$types="s";
	$params=array('null');
    $result=query($types,$params,$query);
    var_dump($result);
	mysqli_free_result($result);
}
function mailpasswd($userid){
    $name='';
    $surname = '';
    $link='';
    $subject='';
    $body='';

    //Users details
    $query='select Name, Surname from users where UserId=?';
    $types="i";
    $params=array($userid);
    $result=query($types,$params,$query);
    while($row = $result -> fetch_assoc()){
        $name=$row['Name'];
        $surname = $row['Surname'];
    }mysqli_free_result($result);


    //Build link
    $key = random_bytes(32);
    $message = $name.$surname;

    $nonce = random_bytes(24);
    $cipher = sodium_crypto_secretbox($message, $nonce, $key);
    $plain = sodium_crypto_secretbox_open($cipher, $nonce, $key);

    $query = 'insert into passwdkeys(KeyId, RandBytes, Message, Cipher, Plain, Nonce, CreatedOn)values(null,?,?,?,?,?,now())';
    $types="sssss";
    $params=array($key, $message, $cipher, $plain, $nonce);
    $result=query($types, $params, $query);



}

?>