<?php
	require_once('vmscFunctions.php');	
	
    $images=array('jpg','jpeg','gif','png','svg');
	$filename='';
    $filesize='';
    $reply='';
    $action='u';
	
	$accepted=array();
	$rejected=array();
	if(isset($_FILES['files']) && !empty($_FILES['files'])){
		$files=$_FILES['files'];
		//echo("<pre>");var_dump($files);echo("</pre>");
		foreach($files['name'] as $cnt => $filename){
			$filetmp=$files['tmp_name'][$cnt];
			$filesize=$files['size'][$cnt];
			$fileerror=$files['error'][$cnt];
			
			if($fileerror === 0 && substr_count($filename,".") === 1){
				$thefilename=explode(' ',$filename);
				$name=ucfirst(strtolower(array_shift($thefilename)));
				$surname=strtolower(implode(' ',$thefilename));
				$surname=ucfirst(substr($surname,0,strripos($surname,".")));
				$thefilename=explode(' ',$filename);
				
				echo('<br>Name :'.$name.'<br>Surname :'.$surname.'<br>');
				
				$ext=substr($filename,strripos($filename,".")+1,strlen($filename));
				$ext=strtolower($ext);
				$filename=$thefilename[0].' '.substr($thefilename[1],0,strripos($thefilename[1],"."));
				$personid=0;
				if(in_array($ext,$images) === true && $filesize <= 10485760){
					$query='select max(PersonId) as PersonId from perssonel where lower(ltrim(rtrim(Name)))=lower(ltrim(rtrim(?))) and lower(ltrim(rtrim(Surname)))=lower(ltrim(rtrim(?)))';
					$types="ss";
					$params=array($name,$surname);
					$result=query($types,$params,$query);
					while($row=$result->fetch_assoc()){
						$personid=$row['PersonId'];
					}mysqli_free_result($result);
					echo('Query: '.$query.'<Br>Params :'.implode(' ',$params).'<br>PersonId :'.$personid);
					if($personid==0){
						$query='insert into perssonel(Name,Surname,IDNo,Addictions,Drivers,Sassa,CreatedOn,Status,Complete)
						values(ltrim(rtrim(?)),ltrim(rtrim(?)),"000000",0,0,0,now(),0,3)';
						$types="ss";
						
						$params=array($name,$surname);
						$result=query($types,$params,$query);
						
						echo('Query: '.$query.'<Br>Params :'.implode(' ',$params).'<br>PersonId :'.$personid);
						mysqli_free_result($result);
						
						$query='select max(PersonId) as PersonId from perssonel where lower(ltrim(rtrim(Name)))=lower(ltrim(rtrim(?))) and lower(ltrim(rtrim(Surname)))=lower(ltrim(rtrim(?)))';
						$types="ss";
						$params=array($name,$surname);
						$result=query($types,$params,$query);
						while($row=$result->fetch_assoc()){
							$personid=$row['PersonId'];
						}mysqli_free_result($result);
						echo('<br>PersonId :'.$personid);
					}
					$query='insert into personimages(ImageId,PersonId,Ext,UploadedOn,Name)
							values(null,?,?,now(),?)';
						$types="iss";
						$params=array($personid,$ext,$name.' '.$surname);
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

					$accepted[$cnt]='Your image '
						.$name.' '.$surname.' (size: '
							.bytescalc($filesize,2).') was successfully uploaded.<br>';
				}else{
					$rejected[$cnt]='Your image '
						.$name.' '.$surname.' (size: '
							.bytescalc($filesize,2).') was not accepted.
					<br>Only the following image types are excepted: '
								.implode(", ",$images).'.
					<br>Please make sure you upload the right image type.<br>';
				}
			}
		}
	}
	echo('Accepted :<pre>');var_dump($accepted);echo('</pre>');
	echo('Rejected :<pre>');var_dump($rejected);echo('</pre>');
?>
<!DOCTYPE type="html">
<html lang="US">
<head>
	<link rel="shortcut icon" href="include/icons/logo.png" />
    <link rel="stylesheet" href="vmsstyle.css">
	<title>Bulk Image Upload</title>
</head>
<body>
<form action="" method="post" enctype="multipart/form-data">
	<input  id="images" type="file" class="myImages" name="files[]" multiple>
	<input type="submit" anme="submit" value="Upload" />
</form>


</body>
</html>