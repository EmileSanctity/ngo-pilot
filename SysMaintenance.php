<?php
	require_once('vmscFunctions.php');
	//SysParam & Auto Logout & Logging
	$userid=str_clean(valunscramble($_GET[idunscramble('userid')]));
	$secid=str_clean(valunscramble($_GET[idunscramble('secid')]));
	timer(20,$userid);
//Sys logs
	$array=array('user',$userid);
	$tables=array('');
	logs($userid,'l','sysmaintenance',$array,$tables);
	
	
	
//	Addictions
	$query='select AddictionId,Name,Abbr 
			  from addictions 
			 where AddictionId>?';
	$types="i";
	$params=array(0);
	$result=query($types,$params,$query);
	
	$reply='	<div id="sysmainaddictions" style="width:420px;border:1;margin:0 auto;">
					<form>
						<table class="alternate" id="sysmainaddictionstable" style="width:420px;border:1;margin:0 auto;">
							<thead>
								<tr style="font-weight:bold;">
									<th colspan="4" style="text-align:center;">
										<div>
											<span class="folder-button" onclick="folders(\'sysmainaddictionsfolder\',\'addaddictionbutton\');">Addiction List</span>
											<button id="addaddictionbutton" class="folder-content" style="float:right;width:50px;" type="button" onclick="addaddiction();">Add</button>
										</div>
									</th>
								</tr>
							</thead>
							<tbody id="sysmainaddictionsfolder" class="folder-content">
								<tr>
									<td style="text-align:center;font-weight:bold;">Delete</td>
									<td style="text-align:center;font-weight:bold;">Name</td>
									<td style="text-align:center;font-weight:bold;">Abbreviation</td>
								</tr>';
	while($row=$result->fetch_assoc()){
		$reply.='				<tr>
									<td style=text-align:center;">
										<input type="checkbox" id="deleteaddiction" name="deleteaddiction" />
									</td>
									<td>
										<input type="hidden" name="addictionid" value="'.$row['AddictionId'].'" />
										<input type="text" name="addictionname" value="'.$row['Name'].'" />
									</td>
									<td>
										<input type="text" name="addictionabbr" value="'.$row['Abbr'].'" />
									</td>
								</tr>';
	}							
	$reply.='					<tr>
									<td colspan="3" style="text-align:center;"><button type="button" onclick="sysaddictionupdate();">Update</button></td>
								</tr>
							</tbody>
						</table>
					</form>
				</div>';
				
//Offences
	$query='select OffenceId,Offence,Description 
			  from offences 
			 where OffenceId>?';
	$types="i";
	$params=array(0);
	$result=query($types,$params,$query);
	
	$reply.='	<div id="sysmainoffences" style="width:550px;border:1;margin:0 auto;">
					<form>
						<table class="alternate" id="sysmainoffencestable" style="width:550px;border:1;margin:0 auto;">
							<thead>
								<tr style="font-weight:bold;">
									<th colspan="4" style="text-align:center;">
										<div>
											<span class="folder-button" onclick="folders(\'sysmainoffencesfolder\',\'addoffencesbutton\');">Offences List</span>
											<button id="addoffencesbutton" class="folder-content" style="float:right;width:50px;" type="button" onclick="addoffence();">Add</button>
										</div>
									</th>
								</tr>
							</thead>
							<tbody id="sysmainoffencesfolder" class="folder-content">
								<tr>
									<td style="text-align:center;font-weight:bold;">Delete</td>
									<td style="text-align:center;font-weight:bold;">Offence</td>
									<td style="text-align:center;font-weight:bold;">Description</td>
								</tr>';
	while($row=$result->fetch_assoc()){
		$reply.='				<tr>
									<td style=text-align:center;">
										<input type="checkbox" id="deleteoffence" name="deleteoffence" />
									</td>
									<td>
										<input type="hidden" name="offenceid" value="'.$row['OffenceId'].'" />
										<input type="text" name="offenceoffence" value="'.$row['Offence'].'" />
									</td>
									<td>
										<textarea style="resize:vertical;min-height:60px;width:298px;" name="offencedescrs">'.$row['Description'].'</textarea>
									</td>
								</tr>';
	}							
	$reply.='					<tr>
									<td colspan="3" style="text-align:center;"><button type="button" onclick="sysoffenceupdate();">Update</button></td>
								</tr>
							</tbody>
						</table>
					</form>
				</div>';
//Department
	$query='select DeptId,Name 
			  from department 
			 where DeptId>?';
	$types="i";
	$params=array(0);
	$result=query($types,$params,$query);
	
	$reply.='	<div id="sysmaindepartments" style="width:250px;border:1;margin:0 auto;">
					<form>
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
					</form>
				</div>';

	echo($reply);
?>