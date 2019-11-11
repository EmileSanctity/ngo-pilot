<?php
	require_once('vmscFunctions.php');
	//SysParam & Auto Logout & Logging
	$userid=str_clean(valunscramble($_GET[idunscramble('userid')]));
	$secid=str_clean(valunscramble($_GET[idunscramble('secid')]));
	timer(20,$userid);
//Sys logs
	$array=array('user',$userid);
	$tables=array('');
	logs($userid,'l','PersonSearchAddictionsList',$array,$tables);
	
	//var_dump($_GET);
	$choice=str_clean($_GET["choice"]);
	$reply='';
	$query='select AddictionId,Name,Abbr 
			  from addictions 
			 where AddictionId > ?';
	$types='i';
	$params=array(0);
	$result=query($types,$params,$query);
	$reply.='	<select id="';if($choice==2){$reply.="p";}$reply.='druglist" multiple="multiple">';
	while($row=$result->fetch_assoc()){
		$reply.='	<option value="'.$row['AddictionId'].'" >'.$row['Abbr'].' '.$row['Name'].'</option>';
	}
	$reply.='	</select>';
	echo($reply);
?>