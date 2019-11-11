<?php
	require_once('vmscFunctions.php');
	require_once('vmscSysUserManageSQL.php');
	//SysParam & Auto Logout & Logging
	$userid=str_clean(valunscramble($_GET[idunscramble('userid')]));
	$secid=str_clean(valunscramble($_GET[idunscramble('secid')]));
	timer(20,$userid);
//Sys logs
	$array=array('user',$userid);
	$tables=array('');
	logs($userid,'l','sysusermanage',$array,$tables);
//AppParam
	$nav=str_clean(valunscramble($_GET[idunscramble('nav')]));
	$action='Add';
	$heading='Add a system user';
	$name='~';
	$surname='~';
	$email='~';
	$password='~';
	$sec=0;
	$user=0;
	
	if($nav==2){
		$user=str_clean(valunscramble($_GET[idunscramble('user')]));
		$action='Update';
		$heading='Edit the system user';
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
	}

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
									<td style="text-align:right;" width="200px">First name</td><td width="200px"><input size="35" id="username" type="text"  value="'.$name.'" required /></td>
								</tr>
								<tr>
									<td style="text-align:right;">Surname</td><td><input size="35" id="usersurname" type="text"  value="'.$surname.'" required /></td>
								</tr>
								<tr>
									<td style="text-align:right;">Email</td><td><input size="35" id="useremail" type="email"  value="'.$email.'" required /></td>
								</tr>
								<tr>
									<td style="text-align:right;">Password</td><td><input size="35" id="userpassword" type="password"  value="'.$password.'" required /></td>
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