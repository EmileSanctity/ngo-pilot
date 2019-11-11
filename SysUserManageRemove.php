<?php
	require_once('vmscFunctions.php');
	require_once('vmscSysUserManageSQL.php');
	//SysParam & Auto Logout & Logging
	$userid=str_clean(valunscramble($_GET[idunscramble('userid')]));
	$secid=str_clean(valunscramble($_GET[idunscramble('secid')]));
//Sys logs
	$array=array('userid',$userid);
	$tables=array('');
	logs($userid,'l','sysusermanageremove',$array,$tables);
	//AppParam
	$user=str_clean(valunscramble($_GET[idunscramble('user')]));
	
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
	logs($userid,'d','sysusermanageremove',$array,$tables);
	
	$query=SysUserManageSQL::delete_sec_user();
	$types='i';
	$params=array($user);
	query($types,$params,$query);
	
	$query=SysUserManageSQL::delete_user();
	$types='i';
	$params=array($user);
	query($types,$params,$query);
	
	$action='Add';
	$heading='Add a system user';
	$name='Name';
	$surname='Surname';
	$email='Email';
	$password='Password';
	$sec=0;
	$user=0;
	$response=' <h4 style="color:red;font-weight:bold;">The user was successfully deleted</h4>
				<div id="sysuserform" style="width:400px;border:1;margin:0 auto;">
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