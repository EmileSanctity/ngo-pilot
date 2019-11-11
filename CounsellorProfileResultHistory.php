<?php
	require_once('vmscFunctions.php');
	require_once('vmscPersonProfileSql.php');

//SysParam & Auto Logout & Logging
	$userid=str_clean(valunscramble($_GET[idunscramble('userid')]));
	$secid=str_clean(valunscramble($_GET[idunscramble('secid')]));
	timer(20,$userid);
//Sys logs
	$array=array('user',$userid);
	$tables=array('');
	logs($userid,'l','PersonSearchList',$array,$tables);

//App Params
	$personid=str_clean(valunscramble($_GET[idunscramble('personid')]));
//echo('PersonId: '.$personid);

//Variables
	$reply='';

//Check security access
	if($secid>2){
		header('Location:General.php?'.idscramble('userid').'='.valscramble($userid).
						'&'.idscramble('navid').'='.valscramble(0).
						'&'.idscramble('secid').'='.valscramble($secid).' ');
	}
	$query='select SecId from secusers where UserId=?';
	$types="i";
	$params=array($userid);
	$result=query($types,$params,$query);
	while($row=$result->fetch_assoc()){
		$secid=$row['SecId'];
	}mysqli_free_result($result);
	if($secid>2){
		header('Location:General.php?'.idscramble('userid').'='.valscramble($userid).
						'&'.idscramble('navid').'='.valscramble(0).
						'&'.idscramble('secid').'='.valscramble($secid).' ');
	}
	
//Counsellor Notes History
	$query=PersonProfileSql::get_counsellor_notes();
	$types="i";
	$params=array($personid);
	$result=query($types,$params,$query);

	$reply.='		<tr>
						<td style="text-align:center;">
							<form>
								 <div id="counsellorhistory" style="clear:both;" >
									<table class="alternate"  width="900px" style="margin: 0 auto;" id="counsellorhistoryform">
										<thead>
											<tr style="font-weight:bold;">
												<th colspan="4" style="text-align:center;">
													<div>
														<span class="folder-button" onclick="folders(\'counsellorhistoryfolder\');">Counsellor History</span>
													</div>
												</th>
											</tr>
										<thead>
										<tbody id="counsellorhistoryfolder" class="folder-content">
											<tr style="font-weight:bold;">
												<td style="border:1px solid #a6a6a6;text-align:center;vertical-align:middle;">Capture Date</td>
												<td style="border:1px solid #a6a6a6;text-align:center;vertical-align:middle;">Title</td>
												<td style="border:1px solid #a6a6a6;text-align:center;vertical-align:middle;">Note</td>
												<td style="border:1px solid #a6a6a6;text-align:center;vertical-align:middle;">Counsellor</td>
											</tr>';
		
		while($row=$result->fetch_assoc()){
			$reply.='						<tr>
												<td width="150px" >
													<div>
														<span class="span-button" onmouseover="spanbuttonhover(this);" onclick="editcounsellornote('.$row['CounsId'].');">'.$row['CaptureDate'].'</span>
													</div>
												</td>
												<td width="150px">
													'.$row['Title'].'
												</td>
												<td width="300px">
													<textarea style="resize:vertical;min-height:60px;width:470px;" >'.$row['Note'].'</textarea>
												</td>
												<td>
													'.$row['Name'].' '.$row['Surname'].'
												</td>
											</tr>';
	}
	$reply.='							</tbody>
									</table>
								 </div>
							</form>
						</td>
					</tr>
				</table>';
	

	echo($reply);
	
	
	
	
	
	
	
?>