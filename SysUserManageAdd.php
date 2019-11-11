<?php
	require_once('vmscFunctions.php');
	require_once('vmscSysUserManageSQL.php');
	//SysParam & Auto Logout & Logging
	$userid=str_clean(valunscramble($_GET[idunscramble('userid')]));
	$secid=str_clean(valunscramble($_GET[idunscramble('secid')]));
	
//Sys logs
	$array=array('userid',$userid);
	$tables=array('');
	logs($userid,'l','sysusermanageadd',$array,$tables);
	
//AppParam
	$name=str_clean($_GET['username']);
	$surname=str_clean($_GET['usersurname']);
	$email=str_clean($_GET['useremail']);
	$password=str_clean($_GET['userpassword']);
	$secid=str_clean($_GET['usersecurity']);
	
	//echo($name.' '.$surname.' '.$email.' '.$password.' '.$secid);
	if($name == '~' || $surname == '~' || $email == '~' || $password == '~'){
		$response='<h4 style="font-size:20px">User details incomplete. the user was not added to the system.</h4>';
	}else{
		$response = '<h4 style="font-size:20px">User successfully added.</h4>';
		
		$query=SysUserManageSQL::add_user();
		$types='ssss';
		$params=array($name,$surname,$email,$password);
		query($types,$params,$query);
		$user=0;
		$query=SysUserManageSQL::get_userid();
		$types='ss';
		$params=array($email,$password);
		$result=query($types,$params,$query);
		while($row=$result->fetch_assoc()){
			$user=$row['UserId'];
		}mysqli_free_result($result);
		
	//Sys logs
		$array=array('userid',$userid);
		$tables=array('users');
		logs($userid,'r','sysusermanageadd',$array,$tables);
		
		#echo('<br>'.$user);
		
		$query=SysUserManageSQL::add_secusers();
		$types='ii';
		$params=array($secid,$user);
		query($types,$params,$query);
		
	//Sys logs
		$array=array('user',$user,'secid',$secid);
		$tables=array('secusers');
		logs($userid,'c','sysusermanageadd',$array,$tables);
		
	//Sys logs
		$array=array('name',$name,'surname',$surname,'email',$email);
		$tables=array('users');
		logs($userid,'c','sysusermanageadd',$array,$tables);
		#echo('<br>'.$user);
	}
	
	
	$action='Add';
	$heading='Add a system user';
	$name='~';
	$surname='~';
	$email='~';
	$password='~';
	$sec=0;
	$user=0;
	$response='	<div id="sysuserform" style="width:400px;border:1;margin:0 auto;">
					<form>
						<table class="alternate" style="width:400px;border:1;margin:0 auto;">
							<thead>
								<tr style="font-weight:bold;">
									<th colspan="4" style="text-align:center;">
										<div>
											<span class="folder-button" onclick="folders(\'sysuserformfolder\');">'.$heading.'</span>
										</div>
									</th>
								</tr>
							</thead>
							<tbody id="sysuserformfolder" class="folder-content">
								
								<tr>
									<td style="text-align:right;" width="200px">First name</td><td width="200px"><input size="35" id="username" type="text" ="First Name" value="'.$name.'" required /></td>
								</tr>
								<tr>
									<td style="text-align:right;">Surname</td><td><input size="35" id="usersurname" type="text" placeholder="Surname" value="'.$surname.'" required /></td>
								</tr>
								<tr>
									<td style="text-align:right;">Email</td><td><input size="35" id="useremail" type="email" placeholder="Email" value="'.$email.'" required /></td>
								</tr>
								<tr>
									<td style="text-align:right;">Password</td><td><input size="35" id="userpassword" type="password" placeholder="Password" value="'.$password.'" required /></td>
								</tr>
								<tr>
									<td style="text-align:right;">Security Access</td><td>
										<select style="max-width:100%" id="usersecurity" required>
											<option value="0">Security Level?</option>';
			
			$query=SysUserManageSQL::security();
			$types='i';
			$params=array(0);
			$result=query($types,$params,$query);
			while($row=$result->fetch_assoc()){
				$response.='				<option value="'.$row['SecId'].'"';
				if($sec==$row['SecId']){
					$response.='				   selected ';
				}
				$response.='						>'.$row['Security'].'</option>';
			}mysqli_free_result($result);
			$response.='				</select>
										<input type="hidden" id="useruser" value="'.valscramble($user).'" />
									</td>
								</tr>
								<tr>
									<td colspan="2" style="text-align:center;">
										<button type="button" onclick="usermanage(\''.$action.'\');">'.$action.'</button>
									</td>
								</tr>
							</tbody>
						</table>
					</form>
				</div>';

	echo($response);
?>