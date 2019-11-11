<?php
	require_once('vmscFunctions.php');
	require_once('vmscPersonProfileSql.php');

//SysParam & Auto Logout & Logging
	$userid=str_clean(valunscramble($_GET[idunscramble('userid')]));
	//$secid=str_clean(valunscramble($_GET[idunscramble('secid')]));
	timer(20,$userid);

//Sys logs
	$array=array('user',$userid);
	$tables=array('');
	logs($userid,'l','PersonSpouseUpdate',$array,$tables);

//App Params
	//var_dump($_GET);
	
	$reply='';
	$personid=str_clean($_GET['personid']);
	//echo('<br>'.$personid.'<br>');
	$deletes=explode(",",str_clean($_GET['deletes']));
	$ids=explode(",",str_clean($_GET['ids']));
	$names=explode(",",str_clean($_GET['names']));
	$starts=explode(",",str_clean($_GET['starts']));
	$finishes=explode(",",str_clean($_GET['finishes']));
	
	foreach($ids as $x => $id){
		if($starts[$x]===''){
			$starts[$x]=NULL;
		}
		if($finishes[$x]===''){
			$finishes[$x]=NULL;
		}
		if($id=='0' && $deletes[$x] == 'false'){
			$query='insert into persondepartment(PersonDeptId,DeptId,PersonId,StartDate,FinishDate)values(null,?,?,?,?)';
			$types="isss";
			$params=array($names[$x],$personid,$starts[$x],$finishes[$x]);
			//echo('<br>'.implode(",",$params));
			$result=query($types,$params,$query);
		}else{
			if($deletes[$x] == 'false'){
				$query='update persondepartment
						   set DeptId=?,
							   PersonId=?,
							   StartDate=?,
							   FinishDate=?
						 where PersonDeptId=?';
				$types="iissi";
				$params=array($names[$x],$personid,$starts[$x],$finishes[$x],$id);
				//echo('<br>'.implode(",",$params));
				$result=query($types,$params,$query);
			}
			if($deletes[$x] == 'true'){
				$query='delete from persondepartment where PersonDeptId=?';
				$types="i";
				$params=array($id);
				//echo('<br>'.implode(",",$params));
				$result=query($types,$params,$query);
			}
		}
	}
	$query=PersonProfileSql::get_department();
	$types="i";
	$params=array($personid);
	$result=query($types,$params,$query);
	$reply.='							<table class="alternate" width="1200px" id="departmenttable">
											<thead>
												<tr style="font-weight:bold;">
													<th colspan="6" style="text-align:center;">
														<div>
															<span class="folder-button" onclick="folders(\'departmentfolder\',\'departmentbutton\');">Department</span>
															<button id="departmentbutton" class="folder-content" style="float:right;width:50px;" type="button" onclick="adddepartment();">Add</button>
														</div>
													</th>
												</tr>
											<thead>
											<tbody id="departmentfolder" class="folder-content">
												<tr style="font-weight:bold;" >
													<td width="50px" style="left:25%;border:1px solid #a6a6a6;text-align:center;vertical-align:middle;">Delete</td>
													<td width="250px" style="border:1px solid #a6a6a6;text-align:center;vertical-align:middle;">Department</td>
													<td width="250px" style="border:1px solid #a6a6a6;text-align:center;vertical-align:middle;">Start Date</td>
													<td width="100%" style="border:1px solid #a6a6a6;text-align:left;vertical-align:middle;"><span style="padding-left:30px;text-align:left;">Finish Date</span></td>
												</tr>';
		while($row=$result->fetch_assoc()){
			$persondeptid=$row['PersonDeptId'];
			$deptid=$row['DeptId'];
			$startdate=$row['StartDate'];
			$finishdate=$row['FinishDate'];
			$reply.='							<tr>
													<td style="text-align:center;">
														<input type="checkbox" name="departmentdelete" />
													</td>
													<td style="text-align:center;">
														<input type="hidden" id="persondeptid" name="persondeptid" value="'.valscramble($persondeptid).'" />
														<select name="department">';
			$query='select DeptId, Name from department where DeptId>?';
			$types="i";
			$params=array(0);
			$res=query($types,$params,$query);
			while($row=$res->fetch_assoc()){
				$reply.='									<option value="'.$row['DeptId'].'" ';if($row['DeptId']==$deptid){$reply.=' selected';} $reply.='>'.$row['Name'].'</option>';
			}mysqli_free_result($res);
			$reply.='									</select>
													</td>
													<td style="text-align:center;">
														<input type="date" name="departmentstartdate" value="'.$startdate.'" />
													</td>
													<td style="text-align:left;">
														<input type="date" name="departmentfinishdate" value="'.$finishdate.'" />
													</td>
												</tr>';
		}
		$reply.='								<tr>
													<td colspan="6" style="text-align:center;">
														<button type="button" onclick="departmentupdate();">Update</button>
													</td>
												</tr>
											</tbody>
										</table>';
	
	
	echo($reply);
?>
