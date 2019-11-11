<?php
	require_once('vmscFunctions.php');

//SysParam & Auto Logout & Logging
	$userid=valunscramble($_POST[idunscramble('userid')]);
	$secid=valunscramble($_POST[idunscramble('secid')]);
	timer(20,$userid);

//Sys logs
	$array=array('user',$userid);
	$tables=array('');
	logs($userid,'l','PersonSearchList',$array,$tables);

//App Params
	$personid=valunscramble($_POST[idunscramble('personid')]);
	$discid=valunscramble($_POST['discid']);
	$response='';
	$reply='';
	
	//echo('<br>UserId: '.$userid.'<br>SecId: '.$secid.'<br>PersonId: '.$personid);
	//var_dump($_POST);
	//var_dump($_FILES['file']);
	
	$response=upload($userid,$personid,'PersonDisciplinaryDocUpdate.php','disc',0,0,$discid);
	$query='select D.DocId, 
				   concat(D.Name,".",D.Ext) as NoteName, 
				   D.DiscId, 
				   D.Ext
			  from persondocs D
			  join (select max(DocId) as DocId,DiscId
					  from persondocs
					 where DiscId=?) X
			    on D.DiscId=X.DiscId
			   and D.DocId=X.DocId
			 where D.DiscId=?';
    $types="ii";
    $params=array($discid,$discid);
    $result=query($types,$params,$query);
	while($row=$result->fetch_assoc()){
		$docid=$row['DocId'];
		$ext=$row['Ext'];
		$note=$row['NoteName'];
		$discid=$row['DiscId'];
	}mysqli_free_result($result);
	$reply='<a href="include/persondocs/'.$docid.'.'.$ext.'" />'.$note.'</a>
			<br>
			<label style="clear:left;">'.$response.'</label>
			<input style="clear:left;" id="disciplinarydoc'.valscramble($discid).'" type="file" class="fileinput" name="file">
			<button type="button" onclick="persondisciplinedoc('.valscramble($discid).');">Upload</button>
			<input type="hidden" id="recentdiscid" value="'.$discid.'" />';
	
	
	echo($reply);
?>