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
	
	
	
//Personal Info & Profile picture
	$query=PersonProfileSql::get_info_profile();
	$types="i";
	$params=array($personid);
	$result=query($types,$params,$query);
	$reply.='';

//Personal Info & Profile picture
	while($row=$result->fetch_assoc()){
		
//Personal Info & Profile picture
//Result
	$reply.='	<form>
					<div id="personimage" style="float:left;padding:10px;">
						<img src="'.$row['Image'].'" width="200" height="200" />
					</div>
					<div id="personalinfo" width="" style="width:800px;margin:0 auto;">
						<h4 style="font-size:20px">'.$row['Name'].' '.$row['Surname'].'</h4>
						<input type="hidden" id="person" value="'.valscramble($personid).'" />
						<table style="border:1px solid #cccccc;" width="100%">
							<tr>
								<td style="text-align:right;">ID Number: </td>
								<td>'.$row['IDNo'].'</td>
							</tr>
							<tr>
								<td style="text-align:right;">Age: </td>
								<td>'.$row['Age'].'</td>
							</tr>
							<tr>
								<td style="text-align:right;">Drivers: </td>
								<td>'.$row['Drivers'].'</td>
							</tr>
							<tr>
								<td style="text-align:right;">Sassa: </td>
								<td>'.$row['Sassa'].'</td>
							</tr>
							<tr>
								<td style="text-align:right;">Completed: </td>
								<td>'.$row['Completed'].'</td>
							</tr>
							<tr>
								<td style="text-align:right;">Gender: </td>
								<td>'.$row['Gender'].'</td>
							</tr>
							<tr>
								<td style="text-align:right;">Spouse</td>
								<td>'.$row['Spouse'].'</td>
							</tr>
							<tr>
								<td style="text-align:right;">Dependants</td>
								<td>'.$row['Dependants'].'</td>
							</tr>
							<tr>
								<td style="text-align:right;">Sick Days: </td>
								<td>'.$row['SickDays'].'</td>
							</tr>
							<tr>
								<td style="text-align:right;">Has addictions: </td>
								<td>'.$row['Addict'].'</td>
							</tr>';
	if($row['Addict']=='Yes'){
		$reply.=	       '<tr>
								<td style="text-align:right;">List of drugs: </td>
								<td>'.$row['AddictionList'].'</td>
							</tr>';
	}
	$reply.=	   '	</table>
					</div>
				</form>';
	}mysqli_free_result($result);
//Counsellor Add Note
	$reply.='	<table style="border:1px solid #cccccc;width:800px;margin:0 auto;padding:0 0;">
					<tr>
						<td style="text-align:center;">
							<form>
								 <div id="addcounsellornote" style="clear:both;" >
									<table class="alternate"  width="800px" style="margin: 0 auto;" id="addcounsellornoteform">
										<thead>
										<tr style="font-weight:bold;">
											<th colspan="4" style="text-align:center;">
												<div>
													<span class="folder-button" onclick="folders(\'addcounsellornotefolder\');">Counsellor Note</span>
												</div>
											</th>
										</tr>
										<thead>
										<tbody id="addcounsellornotefolder" class="folder-content">
										<tr style="text-align:center;">
											<td style="text-align:center;" >
												<input style="text-align:center;" placeholder="Title" size="110" type="text" id="counsellortitle" style="padding:5px auto;"/>
											</td>
										</tr>
										<tr>
											<td style="text-align:center;">
												<textarea placeholder="Note" style="resize:vertical;min-height:60px;width:798px;" id="counsellornote"></textarea>
											</td>
										</tr>
										<tr>
											<td colspan="2" style="text-align:center;">
												<button type="button" onclick="addcounsellornote();">Add</button>
											</td>
										</tr>
										</tbody>
									</table>
								 </div>
							</form>
						</td>
					</tr>';
	echo($reply);
?>