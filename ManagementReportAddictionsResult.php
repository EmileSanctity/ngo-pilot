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
	//echo("<pre>");var_dump($_GET);//echo("</pre>");
	$fromdate='1900-01-01';
	$todate='2050-01-01';
	if(str_clean($_GET['fromdate'])>''){
		$fromdate=str_clean(valunscramble($_GET[idunscramble('fromdate')]));
	}
	if(str_clean($_GET['todate'])>''){
		$todate=str_clean(valunscramble($_GET[idunscramble('todate')]));
	}
	$list=str_clean($_GET['druglist']);
	if(strlen(str_clean($_GET['druglist']))>0){
		$abbr=array();
		$druglist=array();
		$query='select AddictionId,Abbr from addictions where AddictionId in (?)';
		$types="s";
		$params=array($list);
		$result=query($types,$params,$query);
		while($row=$result->fetch_assoc()){
			array_push($druglist,$row['AddictionId']);
			array_push($abbr,$row['Abbr']);
		}mysqli_free_result($result);
		//echo($query."<br>".implode(",",$params)."<br>");
	}
	if(strlen(str_clean($_GET['druglist']))==0){
		$druglist=array();
		$abbr=array();
		$query='select AddictionId,Abbr from addictions where AddictionId>?';
		$types="i";
		$params=array(0);
		$result=query($types,$params,$query);
		while($row=$result->fetch_assoc()){
			array_push($druglist,$row['AddictionId']);
			array_push($abbr,$row['Abbr']);
		}mysqli_free_result($result);
	}
	//echo("<br>Druglist :".$list.":<br>");
	$query=ManagementSQL::report_addictions($druglist,$abbr);
	$types='sss';
	//echo($query);
	$params=array($fromdate,$todate,implode(",",$druglist));
	$result=query($types,$params,$query);
	
	$x=0;
	$fields=mysqli_fetch_fields($result);
	//var_dump($fields);
	$columns=array();
		foreach($fields as $key){
			array_push($columns,$key->name);
		}
		
	$data=mysqli_fetch_row($result);
	
	//echo("<pre>");var_dump($params);//echo("</pre>");
	//echo("<pre>");var_dump($data);//echo("</pre>");

	echo(json_encode(array($columns,$data)));


?>