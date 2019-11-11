<?php
	require_once('vmscFunctions.php');
	require_once('vmscPersonProfileSql.php');

//SysParam & Auto Logout & Logging
	$userid=valunscramble($_GET[idunscramble('userid')]);
	$secid=valunscramble($_GET[idunscramble('secid')]);
	timer(20,$userid);

//Sys logs
	$array=array('user',$userid);
	$tables=array('');
	logs($userid,'l','PersonSearchList',$array,$tables);

//App Params
	
	//echo("<pre>");var_dump($_GET);echo("</pre>");
	
	$reply='';
	$personid=str_clean(valunscramble($_GET[idunscramble('personid')]));
	$deletes=explode(",",str_clean($_GET['deletes']));
	$ids=explode(",",str_clean($_GET['ids']));
	$employers=explode(",",str_clean($_GET['employers']));
	$names=explode(",",str_clean($_GET['names']));
	$cellnos=explode(",",str_clean($_GET['cellnos']));
	
	foreach($ids as $x => $id){
		if($id=='0' && $deletes[$x] == 'false'){
			$query='insert into personemployers(EmployId,PersonId,Employer,Name,CellNo)values(null,?,?,?,?)';
			$types="isss";
			$params=array($personid,$employers[$x],$names[$x],$cellnos[$x]);
			//echo('<br>'.implode(",",$params));
			$result=query($types,$params,$query);
		}else{
			if($deletes[$x] == 'false'){
				$query='update personemployers
						   set Employer=?,
							   Name=?,
							   CellNo=?
						 where EmployId=? and PersonId=?';
				$types="sssii";
				$params=array($employers[$x],$names[$x],$cellnos[$x],$ids[$x],$personid);
				//echo('<br>'.implode(",",$params));
				$result=query($types,$params,$query);
			}
			if($deletes[$x] == 'true'){
				$query='delete from personemployers where EmployId=? and PersonId=?';
				$types="ii";
				$params=array($ids[$x],$personid);
				//echo('<br>'.implode(",",$params));
				$result=query($types,$params,$query);
			}
		}
	}
	
	$query=PersonProfileSql::get_employment();
	$types="i";
	$params=array($personid);
	$result=query($types,$params,$query);
	
	$reply.='							<table class="alternate" width="1200px" id="employtable">
											<thead>
												<tr style="font-weight:bold;">
													<th colspan="6" style="text-align:center;">
														<div>
															<span class="folder-button" onclick="folders(\'employfolder\',\'employbutton\');">Employment</span>
															<button id="employbutton" class="folder-content" style="float:right;width:50px;" type="button" onclick="addemployer();">Add</button>
														</div>
													</th>
												</tr>
											<thead>
											<tbody id="employfolder" class="folder-content">
												<tr style="font-weight:bold;" >
													<td width="50px" style="border:1px solid #a6a6a6;text-align:center;vertical-align:middle;">Delete</td>
													<td width="175px" style="border:1px solid #a6a6a6;text-align:center;vertical-align:middle;">Employer</td>
													<td width="175px" style="border:1px solid #a6a6a6;text-align:center;vertical-align:middle;">Contact Person</td>
													<td width="100%" style="border:1px solid #a6a6a6;text-align:left;vertical-align:middle;"><span style="padding-left:30px;text-align:left;">Contact Number</span></td>
												</tr>';
		while($row=$result->fetch_assoc()){
		$reply.='								<tr>
													<td style="text-align:center;width:50px;">
														<input type="checkbox" name="employdelete" />
													</td>
													<td style="text-align:center;">
														<input type="hidden" id="employid" name="employid" value="'.valscramble($row['EmployId']).'" />
														<input type="text" name="employer" value="'.$row['Employer'].'" />
													</td>
													<td>
														<input type="text" name="employname" value="'.$row['Name'].'" />
													</td>
													<td>
														<input type="text" name="employcellno" value="'.$row['CellNo'].'" />
													</td>
												</tr>';
		}
		$reply.='								<tr>
													<td colspan="6" style="text-align:center;">
														<button type="button" onclick="employmentupdate();">Update</button>
													</td>
												</tr>
											</tbody>
										</table>';
	echo($reply);
?>