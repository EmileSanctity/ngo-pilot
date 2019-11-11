<?php
	require_once('vmscFunctions.php');
	// This goes down at 8AM daily...
	// 
	// 
	// Email Birthday List to users in users;
	// 
	
	$query='select UserId from users where BDayList=?;';
	$types="i";
	$params=array(1);
	$result=query($types,$params,$query);
	while($row=$result->fetch_assoc()){
    	mailbdays($row['UserId']);	
    }mysqli_free_result($result);
	
	
?>