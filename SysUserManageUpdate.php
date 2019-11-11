<?php
	require_once('vmscFunctions.php');
	require_once('vmscSysUserManageSQL.php');
	//SysParam & Auto Logout & Logging
	$userid=str_clean(valunscramble($_GET[idunscramble('userid')]));
	$secid=str_clean(valunscramble($_GET[idunscramble('secid')]));
//Sys logs
	$array=array('userid',$userid);
	$tables=array('');
	logs($userid,'l','sysusermanageupdate',$array,$tables);
//AppParam
	$user=str_clean(valunscramble($_GET[idunscramble('user')]));
	//echo('<br>'.$user.'<br>');
//Sys logs
	$name='';
	$surname='';
	$email='';
	$password='';
	$sec=0;
	$query=SysUserManageSQL::get_user();
	$types='i';
	$params=array($user);
	$result=query($types,$params,$query);
	while($row=$result->fetch_assoc()){
		$name=$row['Name'];
		$surname=$row['Surname'];
		$email=$row['Email'];
		$password=$row['Password'];
		$sec=$row['SecId'];
	}mysqli_free_result($result);
	
	$array=array('user',$user,'name',$name,'surname',$surname,'email',$email,'password',$password,'secid',$sec);
	$tables=array('secusers','users');
	logs($userid,'u','sysusermanageupdate',$array,$tables);
//AppParam
	$name=str_clean($_GET['username']);
	$surname=str_clean($_GET['usersurname']);
	$email=str_clean($_GET['useremail']);
	$password=str_clean($_GET['userpassword']);
	$sec=str_clean($_GET['usersecurity']);
	
	$query=SysUserManageSQL::update_user();
	$types='ssssi';
	$params=array($name,$surname,$email,$password,$user);
	query($types,$params,$query);
	
	$query=SysUserManageSQL::update_sec_user();
	$types='ii';
	$params=array($sec,$user);
	query($types,$params,$query);
//Sys logs	
	$array=array('user',$user,'name',$name,'surname',$surname,'email',$email,'password',$password,'secid',$sec);
	$tables=array('secusers','users');
	logs($userid,'u','sysusermanageupdate',$array,$tables);
	
	$response='	<div style="width:300px;margin:0 auto;">
					<table width="100%" style="border:0px;">
						<tr>
							<td colspan="5" style="text-align:center;">
								<h4 style="font-size:20px">User successfully updated.</h4>
							</td>
						</tr>
					</table>
				</div>';
	echo($response);
?>
