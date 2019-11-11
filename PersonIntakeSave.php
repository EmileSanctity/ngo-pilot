<?php
	require_once('vmscFunctions.php');
//SysParam & Auto Logout & Logging
	$userid=valunscramble($_GET[idunscramble('userid')]);
	$secid=valunscramble($_GET[idunscramble('secid')]);
	timer(20,$userid);
//Sys logs
	$array=array('user',$userid);
	$tables=array('');
	logs($userid,'l','PersonIntakeSave',$array,$tables);
//App Params
	//echo('<pre>');var_dump($_GET);echo('</pre>');
//Person
	$firstname=str_clean($_GET['firstname']);
	$surname=str_clean($_GET['surname']);
	$idno=str_clean($_GET['idno']);
	$driver=int_clean($_GET['driver']);
	$sassa=int_clean($_GET['sassa']);
//Drugs
	$addictionlist=str_clean(implode(",",$_GET['addictionlist']));
//Qualifications	
	$qualification=str_clean($_GET['qualification']);
//Employer	
	$employname=str_clean($_GET['employname']);
	$employcontactperson=str_clean($_GET['employcontactperson']);
	$employcontact=str_clean($_GET['employcontact']);
//Married with Kids :P	
	$status=int_clean($_GET['status']);
	//$status == 0 //Single
	$spousename=str_clean($_GET['spousename']);
	$spousesurname=str_clean($_GET['spousesurname']);
	$spouseidno=str_clean($_GET['spouseidno']);
	$kidsbdaylist=str_clean(implode(",",$_GET['kidsbdaylist']));
	$kidsnamelist=str_clean(implode(",",$_GET['kidsnamelist']));
//Next of kin || emergency
	$emergencyname=str_clean($_GET['emergencyname']);
	$emergencynumber=str_clean($_GET['emergencynumber']);
	$emergencyaddress=str_clean($_GET['emergencyaddress']);
//Health
	$health=int_clean($_GET['health']);
	$healthnote=str_clean($_GET['healthnote']);
	$med=int_clean($_GET['med']);
	$medlist=str_clean($_GET['medlist']);
//Calcs
	$addict=0;
	$personid=0;

	if($addictionlist>''){
		$addict=1;
		$addictions=explode(",",$addictionlist);
	}
	if(strlen($kidsnamelist)>3){
		$kids=explode(",",$kidsnamelist);
		$bdays=explode(",",$kidsbdaylist);
	}
	//var_dump($kids);
	if($firstname>'' && $surname>'' && strlen($idno)>6){
	//insert perssonel  
		// Complete table : completestatus = 3 = Still Busy
		//echo("<br>In perssonel");
		$query='insert into perssonel(PersonId,Name,Surname,IDNo,Addictions,Drivers,Sassa,CreatedOn,Status,Complete)values(null,?,?,?,?,?,?,now(),?,3)';
		$types="sssiiii";
		$params=array($firstname,$surname,$idno,$addict,$driver,$sassa,$status);
		$result=query($types,$params,$query);
	//get PersonId	
		$query='select max(PersonId) as PersonId from perssonel where Name=? and Surname=?';
		$types="ss";
		$params=array($firstname,$surname);
		$result=query($types,$params,$query);
		while($row=$result->fetch_assoc()){
			$personid=$row['PersonId'];
		}mysqli_free_result($result);

		//echo("<br>PersonId :".$personid);
	//insert into personaddictions
		if($addictionlist>''){
			//echo("In addiction list<br>");
			foreach($addictions as $addiction){
				$query='insert into personaddictions(PersonId,AddictionId)values(?,?)';
				$types="ii";
				$params=array($personid,$addiction);
				//echo("AddictionId:".$addiction);
				$result=query($types,$params,$query);
			}
		}
	//insert into personqualifications
		if($qualification>''){
			//echo("In qualification<br>");
			$query='insert into personqualifications(PersonId,Qualification)values(?,?)';
			$types="is";
			$params=array($personid,$qualification);
			$result=query($types,$params,$query);
		}
	//insert into personemployers
		if($employname>'' || $employcontactperson>'' || $employcontact>''){
			//echo("In personemployers<br>");
			$query='insert into personemployers(PersonId,Employer,Name,CellNo)values(?,?,?,?)';
			$types="isss";
			$params=array($personid,$employname,$employcontactperson,$employcontact);
			$result=query($types,$params,$query);
		}
	//insert into personspouse
		if($status>0){
			//echo("In personspouse<br>".$personid.' '.$spousename.' '.$spousesurname.' '.$spouseidno);
			if($spousename >'' || $spouseidno > '' || $spousesurname>''){
				$query='insert into personspouse(PersonId,Name,Surname,IDNo,OnSystem)values(?,?,?,?,0)';
				$types="isss";
				$params=array($personid,$spousename,$spousesurname,$spouseidno);
				$result=query($types,$params,$query);
			}
		}
	//insert into personkids
		if(strlen($kidsnamelist)>3){
			//echo("In personkids<br>");
			$cnt=0;
			for($cnt=0;$cnt<sizeof($kids);$cnt++){
				if(strlen($kids[$cnt])>2 && strlen($bdays[$cnt])==10){
					$query='insert into personnodes(PersonId,Name,BirthDate)values(?,?,?)';
					$types="iss";
					$params=array($personid,$kids[$cnt],$bdays[$cnt]);
					$result=query($types,$params,$query);
				}
			}
		}
	// insert into personcontact
		if($emergencyname>'' || $emergencynumber>'' || $emergencyaddress>''){
			//echo("<br>In personcontact");
			$query='insert into personcontact(ContactName,ContactNumber,ContactAddress,PersonId)values(?,?,?,?)';
			$types="sssi";
			$params=array($emergencyname,$emergencynumber,$emergencyaddress,$personid);
			$result=query($types,$params,$query);
		}
	//insert into personmedical
		if($health>0 || $healthnote>'' || $med>0 || $medlist>''){
			//echo("<Br>In personmedical");
			$query='insert into personmedical(PersonId,HealthStatus,Conditions,UseMeds,Medications)values(?,?,?,?,?)';
			$types="iisis";
			$params=array($personid,$health,$healthnote,$med,$medlist);
			$result=query($types,$params,$query);
		}
	//insert into personentryexit
		$query="insert into personentryexit(DateId,EntryDate,ExitDate,Note,PersonId)values(null,now(),'0000-00-00','',?)";
		$types="i";
		$params=array($personid);
		$result=query($types,$params,$query);

		echo("<h4>Person was successfully added.</h4>");

    }else{
		echo("<h4>Please make sure the Name, Surname and IDNo fields are filled in.<br> The first 7 digits of the IDNo must be filled in.</h4>");
	}
?>