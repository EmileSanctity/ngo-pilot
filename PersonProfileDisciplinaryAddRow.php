<?php
	require_once('vmscFunctions.php');

//SysParam & Auto Logout & Logging
	$userid=valunscramble($_GET[idunscramble('userid')]);
	$secid=valunscramble($_GET[idunscramble('secid')]);
	timer(20,$userid);

//Sys logs
	$array=array('user',$userid);
	$tables=array('');
	logs($userid,'l','PersonProfileDisciplinary',$array,$tables);

//App Params
	$personid=valunscramble($_GET[idunscramble('personid')]);
	//echo($personid);
	$query='insert into persondisciplinary(DiscId,PersonId,CaptureDate,OffenceDate,Discipline,Restart,OffenceId)
				values(null,?,now(),"0000-00-00","Added a row to be able to upload a document",0,0)';
	$types="i";
	$params=array($personid);
	$result=query($types,$params,$query);
	$query='select max(DiscId) as DiscId from persondisciplinary where PersonId=?';
	$types="i";
	$params=array($personid);
	$result=query($types,$params,$query);
	while($row=$result->fetch_assoc()){
		$discid=$row['DiscId'];
	}mysqli_free_result($result);
	
	echo($discid);
?>