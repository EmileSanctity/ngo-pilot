<?php
	require_once('vmscFunctions.php');
	require_once('vmscManagementSQL.php');
//SysParam & Auto Logout & Logging
	$userid=str_clean(valunscramble($_GET[idunscramble('userid')]));
	$secid=str_clean(valunscramble($_GET[idunscramble('secid')]));
	timer(20,$userid);
//Sys logs
	$array=array('user',$userid);
	$tables=array('');
	logs($userid,'l','ManagementReportAgeResult',$array,$tables);
//App Params	
	//var_dump($_GET);
	$fromdate='1900-01-01';
	$todate='2050-01-01';
	$status=0;
	if(str_clean($_GET[idunscramble('fromdate')])>''){
		$fromdate=valunscramble(str_clean($_GET[idunscramble('fromdate')]));
	}
	if($_GET[idunscramble('todate')]>''){
		$todate=valunscramble(str_clean($_GET[idunscramble('todate')]));
	}
	if(isset($_GET[idunscramble('status')])){
		$status=valunscramble(str_clean($_GET[idunscramble('status')]));
	}
	$query=ManagementSQL::report_age();
	$types='ssii';
	$params=array($fromdate,$todate,$status,$status);
	$result=query($types,$params,$query);
	//echo("Success");
//	var_dump($params);

	while($row=$result->fetch_assoc()){
		$values=  "[".$row['Three'].",".$row['Four'].",".$row['Five'].",".$row['Six'].",".$row['Seven'].",".$row['Eight'].",".$row['Nine'].",".$row['Ten'].",".$row['One'].",".$row['Two']."]";
	}mysqli_free_result($result);
	echo($values);
?>