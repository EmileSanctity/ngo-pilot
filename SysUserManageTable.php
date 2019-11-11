<?php
	require_once('vmscFunctions.php');
	require_once('vmscSysUserManageSQL.php');
//SysParam & Auto Logout & Logging
	$userid=str_clean(valunscramble($_GET[idunscramble('userid')]));
	$secid=str_clean(valunscramble($_GET[idunscramble('secid')]));
//Sys logs
	$array=array('userid',$userid);
	$tables=array('');
	logs($userid,'l','sysusermanagetable',$array,$tables);

//AppParam
	
	$query=SysUserManageSQL::get_user_part();
	$types="i";
	$params=array(0);
	$result=query($types,$params,$query);
	
	$response='	<div id="systemusers"  style="width:600px;border:1;margin:0 auto;">
					<table class="alternate" style="width:600px;border:1;margin:0 auto;">
						<thead>
						<tr style="font-weight:bold;">
							<th colspan="4" style="text-align:center;">
								<div>
									<span class="folder-button" onclick="folders(\'systemusersfolder\');">Current System Users</span>
								</div>
							</th>
						</tr>
						</thead>
						<tbody id="systemusersfolder" class="folder-content">
						<tr style="font-weight:bold;">
							<td style="border:1px solid #a6a6a6;text-align:center;vertical-align:middle;width:200px;">System User</td>
							<td style="border:1px solid #a6a6a6;text-align:center;vertical-align:middle;width:125px;">Email</td>
							<td style="border:1px solid #a6a6a6;text-align:center;vertical-align:middle;width:75px;">Security</td>
							<td style="border:1px solid #a6a6a6;text-align:center;vertical-align:middle;width:200px;">Delete user</td>
						</tr>';
	
	while($row=$result->fetch_assoc()){
		$response.='	<tr>
							<td style="cursor:pointer;" onclick="ajax(\'sysuseredit\',\'&'.idscramble('user').'='.valscramble($row['UserId']).'\')">'.$row['Name'].' '.$row['Surname'].'</td>
							<td>'.$row['Email'].'</td>
							<td>'.$row['Security'].'</td>
							<td style="color:red;cursor:pointer;" onclick="ajax(\'sysuserremove\',\'&'.idscramble('user').'='.valscramble($row['UserId']).'\')">'.$row['Name'].' '.$row['Surname'].'</td>
						</tr>';
	}
	mysqli_free_result($result);
	$response.='		</tbody>
					</table>
				</div>';
	
	echo($response);
?>