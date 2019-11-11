<?php
	require_once('vmscFunctions.php');

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
	$personid=str_clean(valunscramble($_GET['personid']));
	$deletes=explode(",",str_clean($_GET['deletes']));
	$ids=explode(",",str_clean($_GET['ids']));
	$health=explode(",",str_clean($_GET['health']));
	$conditions=explode(",",str_clean($_GET['conditions']));
	$meds=explode(",",str_clean($_GET['meds']));
	$medications=explode(",",str_clean($_GET['medications']));
	
	foreach($ids as $x => $id){
		if($id=='0' && $deletes[$x] == 'false'){
			$query='insert into personmedical(MedId,PersonId,HealthStatus,Conditions,UseMeds,Medications)values(null,?,?,?,?,?)';
			$types="issss";
			$params=array($personid,$health[$x],$conditions[$x],$meds[$x],$medications[$x]);
			//echo('<br>'.implode(",",$params));
			$result=query($types,$params,$query);
		}else{
			if($deletes[$x] == 'false'){
				$query='update personmedical
						   set HealthStatus=?,
							   Conditions=?,
							   UseMeds=?,
							   Medications=?
						 where MedId=? and PersonId=?';
				$types="ssssii";
				$params=array($health[$x],$conditions[$x],$meds[$x],$medications[$x],$ids[$x],$personid);
				//echo('<br>'.implode(",",$params));
				$result=query($types,$params,$query);
			}
			if($deletes[$x] == 'true'){
				$query='delete from personnodes where MedId=? and PersonId=?';
				$types="ii";
				$params=array($ids[$x],$personid);
				//echo('<br>'.implode(",",$params));
				$result=query($types,$params,$query);
			}
		}
	}
	$query='select MedId, HealthStatus, Conditions, UseMeds, Medications
			  from personmedical
			 where PersonId=?';
	$types="i";
	$params=array($personid);
	$result=query($types,$params,$query);
	$reply='<table class="alternate"  width="1200px" style="margin: 0 auto;" id="medicalform">
											<thead>
											<tr style="font-weight:bold;">
												<th colspan="4" style="text-align:center;">
													<div>
														<span class="folder-button" onclick="folders(\'medicalfolder\',\'medicalbutton\');">Medical</span>
														<button id="medicalbutton" class="folder-content" style="float:right;width:50px;" type="button" onclick="addmedical();">Add</button>
													</div>
												</th>
											</tr>
											<thead>
											<tbody id="medicalfolder" class="folder-content" style="padding: 5px auto;">
											<tr style="font-weight:bold;">
												<td style="border:1px solid #a6a6a6;text-align:center;vertical-align:middle;">Delete</td>
												<td style="border:1px solid #a6a6a6;text-align:center;vertical-align:middle;">HealthStatus</td>
												<td style="border:1px solid #a6a6a6;text-align:center;vertical-align:middle;">Conditions</td>
												<td style="border:1px solid #a6a6a6;text-align:center;vertical-align:middle;">UseMeds</td>
												<td style="border:1px solid #a6a6a6;text-align:left;vertical-align:middle;width:100%;"><span style="padding-left:30px;text-align:left;">Medications</span></td>
											</tr>';

		while($row=$result->fetch_assoc()){
			$reply.='<tr style="text-align:center;">
						<td style="text-align:center;width:50px;">
							<input type="checkbox" id="deletemedical" name="deletemedical" />
						</td>
						<td width="150px" style="text-align:left;">
							<input type="hidden" id="pmedid" name="pmedid" value="'.$row['MedId'].'" />
							<select name="phealth">
								<option value="0" ';if($row['HealthStatus'] == 0){ $reply .= 'selected';}$reply .= '/>Excellent</option><br>
								<option value="1" ';if($row['HealthStatus'] == 1){ $reply .= 'selected';}$reply .= '/>Good</option><br>
								<option value="2" ';if($row['HealthStatus'] == 2){ $reply .= 'selected';}$reply .= '/>Poor</option><br>
							</select>
						</td>
						<td width="200px">
							<textarea style="resize:vertical;min-height:60px;width:198px;" id="pconditions" name="pconditions" >'.$row['Conditions'].'</textarea>
						</td>
						<td width="75px" style="text-align:left;">
							<select name="pmeds">
								<option value="0" ';if($row['UseMeds'] == 0){ $reply .= 'selected';}$reply .= ' />Yes</option>
								<option value="1" ';if($row['UseMeds'] == 1){ $reply .= 'selected';}$reply .= ' />No</option>
							</select>
						</td>
						<td width="775px">
							<textarea style="resize:vertical;min-height:60px;width:773px;" id="pmedications" name="pmedications" >'.$row['Medications'].'</textarea>
						</td>
					</tr>';
		}mysqli_free_result($result);
		$reply.='	<tr>
						<td colspan="5" style="text-align:center;">
							<button type="button" onclick="medicalupdate();">Update</button>
						</td>
					</tr>
				</tbody>
			</table>';
	
	echo($reply);
?>
