<?php
	require_once('vmscFunctions.php');

//SysParam & Auto Logout & Logging
	$userid=str_clean(valunscramble($_GET[idunscramble('userid')]));
	//$secid=str_clean(valunscramble($_GET[idunscramble('secid')]));
	timer(20,$userid);

//Sys logs
	$array=array('user',$userid);
	$tables=array('');
	logs($userid,'l','SysMaintenanceDepartmentUpdate',$array,$tables);

//App Params
	//var_dump($_GET);
	
	$reply='';
	$deletes=explode(",",str_clean($_GET['deletes']));
	$ids=explode(",",str_clean($_GET['ids']));
	$names=explode(",",str_clean($_GET['names']));
	
	foreach($ids as $x => $id){
		if($id=='0' && $deletes[$x] == 'false'){
			$query='insert into department(DeptId,Name)values(null,?)';
			$types="s";
			$params=array($names[$x]);
			//echo('<br>'.implode(",",$params));
			$result=query($types,$params,$query);
		}else{
			if($deletes[$x] == 'false'){
				$query='update department
						   set Name=?
						 where DeptId=?';
				$types="si";
				$params=array($names[$x],$id);
				//echo('<br>'.implode(",",$params));
				$result=query($types,$params,$query);
			}
			if($deletes[$x] == 'true'){
				$query="select count(DeptId) as DeptId from persondepartment where DeptId=?";
				$types="i";
				$params=array($id);
				$result=query($types,$params,$query);
				while($row=$result->fetch_assoc()){
					$deptid=$row['DeptId'];
				}mysqli_free_result($result);
				if($deptid<1){
					$query='delete from department where DeptId=?';
					$types="i";
					$params=array($id);
					//echo('<br>'.implode(",",$params));
					$result=query($types,$params,$query);
				}else{
					$query="select Name from department where DeptId=?";
					$types="i";
					$params=array($id);
					$result=query($types,$params,$query);
					while($row=$result->fetch_assoc()){
						$name=$row['Name'];
					}mysqli_free_result($result);
					$reply.='<h4 style="color:red;font-height:0.8em;">
								The department \"'.$name.'\" cannot be deleted, there are perssonel linked to this department.<br>
								Please remove all dependancies first before attempting to delete.
							 </h4>';
				}
				
			}
		}
	}
	$query='select DeptId,Name 
			  from department
			 where DeptId>?';
	$types="i";
	$params=array(0);
	$result=query($types,$params,$query);
	
		$reply='	<form>
						<table class="alternate" id="sysmaindepartmentstable" style="width:250px;border:1;margin:0 auto;">
							<thead>
								<tr style="font-weight:bold;">
									<th colspan="4" style="text-align:center;">
										<div>
											<span class="folder-button" onclick="folders(\'sysmaindepartmentsfolder\',\'adddepartmentsbutton\');">Departments List</span>
											<button id="adddepartmentsbutton" class="folder-content" style="float:right;width:50px;" type="button" onclick="adddepartment();">Add</button>
										</div>
									</th>
								</tr>
							</thead>
							<tbody id="sysmaindepartmentsfolder" class="folder-content">
								<tr>
									<td style="text-align:center;font-weight:bold;">Delete</td>
									<td style="text-align:center;font-weight:bold;">Department</td>
								</tr>';
	while($row=$result->fetch_assoc()){
		$reply.='				<tr>
									<td style=text-align:center;">
										<input type="checkbox" id="delete" name="deletedepartment" />
									</td>
									<td>
										<input type="hidden" name="deptid" value="'.$row['DeptId'].'" />
										<input type="text" name="deptname" value="'.$row['Name'].'" />
									</td>
								</tr>';
	}							
	$reply.='					<tr>
									<td colspan="3" style="text-align:center;"><button type="button" onclick="sysdepartmentupdate();">Update</button></td>
								</tr>
							</tbody>
						</table>
					</form>';
	
	echo($reply);
?>
