<?php
    require_once('vmscFunctions.php');
    require_once('vmscPersonProfileSql.php');

//SysParam & Auto Logout & Logging
	$userid=str_clean(valunscramble($_POST[idunscramble('userid')]));
	$secid=str_clean(valunscramble($_POST[idunscramble('secid')]));
	timer(20,$userid);

//Sys logs
	$array=array('user',$userid);
	$tables=array('');
	logs($userid,'l','PersonSearchList',$array,$tables);

//App Params
	$personid=str_clean(valunscramble($_POST[idunscramble('personid')]));
	$response='';
	$reply='';
	//echo('<br>UserId: '.$userid.'<br>SecId: '.$secid.'<br>PersonId: '.$personid);
	//var_dump($_POST);
	//var_dump($_FILES['file']);
	$response=upload($userid,$personid,'PersonProfileImageUpdate.php','image',0,0);
	$query=PersonProfileSql::get_profile_image();
	$types="i";
	$params=array($personid);
	$result=query($types,$params,$query);
	while($row=$result->fetch_assoc()){
		$imageid=$row['ImageId'];
		$ext=$row['Ext'];
	}mysqli_free_result($result);
	$reply='<img src="include/personimages/'.$imageid.'.'.$ext.'" width="200" height="200" />
			<br>
			<label style="clear:left;">'.$response.'</label>
			<br>
			<input id="pimage" type="file" class="fileinput" name="file" required />
			<br>
			<button type="button" onclick="getprofilepic();">Upload</button>';
	echo($reply);

?>