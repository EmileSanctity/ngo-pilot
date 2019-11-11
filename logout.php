<?php
	require_once('vmscFunctions.php');
	//SysParam
	$userid=str_clean(valunscramble($_GET[idunscramble('userid')]));
	$logid=0;
	
	$query='update userlogin UL
			   set UL.LoggedOut=(select case when (UNIX_TIMESTAMP(now())-max(UNIX_TIMESTAMP(LoggedOn)))>1800 
											 then max(LoggedOn) else "" end 
								   from logs L 
								  where UL.UserId=L.UserId)
			 where UL.LoggedOut=?
				or UL.LoggedOut is null';
	$types="s";
	$params=array('0000-00-00 00:00:00');
	$result=query($types,$params,$query);
	
	if($_GET[idunscramble('logid')]){
		$logid=str_clean(valunscramble($_GET[idunscramble('logid')]));
	}
	#$secid=valunscramble($_GET[idunscramble('secid')]);
	//AppParam
	
	$query='select max(ActiveId) as ID 
			  from userlogin
			 where UserId=? 
			   and LoggedOut="0000-00-00 00:00:00"';
	$types='i';
	$params=array($userid);
	$result=query($types,$params,$query);
	while($row=$result->fetch_assoc()){
		$id=$row['ID'];	
	}
	mysqli_free_result($result);
	//$date=date('y-m-d h:i:s');
	$query='update userlogin
			   set LoggedOut=now() 
			 where UserId=? 
			   and ActiveId=?';
	$types='ii';
	$params=array($userid,$id);
	query($types,$params,$query);
	
	//Log
	$pname="logout";
	$array=array('userid',$userid,'logid',$logid);
	$tables=array('userlogin');
	logs($userid,'u',$pname,$array,$tables);

	header('Location:index.php');
?>