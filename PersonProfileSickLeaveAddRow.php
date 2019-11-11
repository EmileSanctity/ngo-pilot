<?php
	require_once('vmscFunctions.php');

//SysParam & Auto Logout & Logging
	$userid=str_clean(valunscramble($_GET[idunscramble('userid')]));
	$secid=str_clean(valunscramble($_GET[idunscramble('secid')]));
	timer(20,$userid);

//Sys logs
	$array=array('user',$userid);
	$tables=array('');
	logs($userid,'l','PersonSearchList',$array,$tables);

//App Params
	$personid=str_clean(valunscramble($_GET[idunscramble('personid')]));
	//echo($personid);
	$query='insert into personsickleave(SickId,PersonId,StartDate,FinishDate,Comment)
				values(null,?,"0000-00-00","0000-00-00","Uploaded a document")';
	$types="i";
	$params=array($personid);
	$result=query($types,$params,$query);
	$query='select max(SickId) as SickId from personsickleave where PersonId=?';
	$types="i";
	$params=array($personid);
	$result=query($types,$params,$query);
	while($row=$result->fetch_assoc()){
		$sickid=$row['SickId'];
	}mysqli_free_result($result);
	
	echo($sickid);
?>