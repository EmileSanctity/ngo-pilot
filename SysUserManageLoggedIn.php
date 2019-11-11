<?php
	require_once('vmscFunctions.php');
	require_once('vmscSysUserManageSQL.php');
	//SysParam & Auto Logout & Logging
	
	$userid=0;
	$secid=0;
	$userid=str_clean(valunscramble($_GET[idunscramble('userid')]));
	$secid=str_clean(valunscramble($_GET[idunscramble('secid')]));
//Sys logs
	$array=array('userid',$userid);
	$tables=array('userlogin','users');
	logs($userid,'l','sysusermanageloggedin',$array,$tables);
	logs($userid,'l','sysusermanageloggedin',$array,$tables);
	
	$query=SysUserManageSQL::get_logged_in();
	$types="i";
	$params=array(0);
	$result=query($types,$params,$query);
	$response='	<div id="loggedin"  style="width:500px;border:1;margin:0 auto;">
					<table class="alternate" style="width:500px;border:1;margin:0 auto;">
						<thead>
							<tr style="font-weight:bold;">
								<th colspan="4" style="text-align:center;">
									<div>
										<span class="folder-button" onclick="folders(\'loggedinfolder\');">Users Currently Loggedin</span>
									</div>
								</th>
							</tr>
						<thead>
						<tbody id="loggedinfolder" class="folder-content">
						<tr>
							<td style="width:200px;border:1px solid #a6a6a6;text-align:center;vertical-align:middle;">System User</td>
							<td style="width:150px;border:1px solid #a6a6a6;text-align:center;vertical-align:middle;">Logged In</td>
							<td style="width:150px;border:1px solid #a6a6a6;text-align:center;vertical-align:middle;">Logged Out</td>
						</tr>';
	while($row=$result->fetch_assoc()){
		$response.='	<tr>
							<td>'.$row['Name'].' '.$row['Surname'].'</td>
							<td>'.$row['LoggedIn'].'</td>
							<td>'.$row['LoggedOut'].'</td>
						</tr>';
	}mysqli_free_result($result);
	$response.='	</table>
				</div>';
	
	echo($response);
	
?>