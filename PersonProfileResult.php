<?php
	require_once('vmscFunctions.php');
	require_once('vmscPersonProfileSql.php');

//SysParam & Auto Logout & Logging
	$userid=str_clean(valunscramble($_GET[idunscramble('userid')]));
	$secid=str_clean(valunscramble($_GET[idunscramble('secid')]));
	//timer(20,$userid);
//Sys logs
	$array=array('user',$userid);
	$tables=array('');
	logs($userid,'l','PersonSearchList',$array,$tables);

//App Params
	$personid=str_clean(valunscramble($_GET[idunscramble('personid')]));
	$edit=0;
	if(isset($_GET[idunscramble('edit')])){
		$edit=str_clean(valunscramble($_GET[idunscramble('edit')]));
	}
echo('PersonId: '.$personid.'<br>Edit: '.$edit);

//Variables
	$reply='';

//Personal Info & Profile picture
    $visits=0;
    $query="select count(*) as Visits from counsellor where PersonId=?";
    $types="i";
    $params=array($personid);
    $result=query($types, $params, $query);
    while($row=$result->fetch_assoc()){
        $visits=$row['Visits'];
    }mysqli_free_result($result);

	$query=PersonProfileSql::get_info_profile();
	$types="i";
	$params=array($personid);
	$result=query($types,$params,$query);
	$reply.='';

//Personal Info & Profile picture
	while($row=$result->fetch_assoc()){
		$ids=explode(",",$row['AddictionIdList']);
		$completeid=$row['CompletedId'];
		$gender=$row['Gender'];
		$spouse=$row['Spouse'];
		$dependants=$row['Dependants'];
		$sickdays=$row['SickDays'];
		$addictint=$row['AddictInt'];
		//Result & Editor
		if($edit==1){
			$reply.='	<form>
							<input type="hidden" id="person" value="'.valscramble($personid).'" />
							<div id="personimage" style="float:left;padding:10px;">
								<img src="'.$row['Image'].'" width="200" height="200" />
								<br>
								<input style="clear:left;" id="pimage" type="file" class="fileinput" name="file">
								<br>
								<button type="button" onclick="getprofilepic();">Upload</button>
							</div>';
			$reply.='		<div id="personalinfo" style="width:600px;margin:0 auto;">
								<h4 style="font-size:20px">
									<input type="text" id="pname" value="'.$row['Name'].'"/>
									<input type="text" id="psurname" value="'.$row['Surname'].'"/>
								</h4>
								<table style="border:1px solid #cccccc;" width="100%" id="profile">
									<tr>
										<td style="text-align:right;">ID Number: </td>
										<td><input type="text" id="pidno" value="'.$row['IDNo'].'"/></td>
									</tr>
									<tr>
										<td style="text-align:right;">Age: </td>
										<td>'.$row['Age'].'</td>
									</tr>
									<tr>
										<td style="text-align:right;">Counsellor visits: </td>
										<td>'.$visits.'</td>
									</tr>
									<tr>
										<td style="text-align:right;">Drivers: </td>
										<td>
											<select name="drivers" id="drivers">
												<option value="0"';if($row['Drivers']=='Yes'){$reply.=' selected';}$reply.='>Yes</option>
												<option value="1"';if($row['Drivers']=='No'){$reply.=' selected';}$reply.='>No</option>
											</select>
										</td>
									</tr>
									<tr>
										<td style="text-align:right;">Sassa: </td>
										<td>
											<select name="sassa" id="sassa">
												<option value="0"';if($row['Sassa']=='Yes'){$reply.=' selected';}$reply.='>Yes</option>
												<option value="1"';if($row['Sassa']=='No'){$reply.=' selected';}$reply.='>No</option>
											</select>
										</td>
									</tr>
									<tr>
										<td style="text-align:right;">Completed: </td><td>
											<select id="pcompleted">
												<option value="0" ';if(0==$completeid){$reply.="selected";}$reply.='>No data captured</option>';
			$query="select CompleteId as Complete, Name from completestatus where CompleteId>?";
			$types="i";
			$params=array(0);
			$res=query($types,$params,$query);
			while($row=$res->fetch_assoc()){
				$reply.='						<option value="'.$row['Complete'].'" ';if($completeid==$row['Complete']){$reply.='selected';}$reply.=' >'.$row['Name'].'</option>';
			}mysqli_free_result($res);
			$reply.='						</select>
										</td>
									</tr>
									<tr>
										<td style="text-align:right;">Gender: </td>
										<td>'.$gender.'</td>
									</tr>
									<tr>
										<td style="text-align:right;">Spouse: </td>
										<td>'.$spouse.'</td>
									</tr>
									<tr>
										<td style="text-align:right;">Dependants: </td>
										<td>'.$dependants.'</td>
									</tr>
									<tr>
										<td style="text-align:right;">Sick Days: </td>
										<td>'.$sickdays.'</td>
									</tr>
									<tr>
										<td style="text-align:right;">Has addictions: </td>
										<td>
											<select id="paddict" onchange="loadaddictions(this);">
												<option value="1" ';if(1==$addictint){$reply.="selected";}$reply.='>No</option>
												<option value="2" ';if(2==$addictint){$reply.="selected";}$reply.='>Yes</option>
											</select>
										</td>
									</tr>';
			if(2==$addictint){
				$reply.='			<tr>
										<td style="text-align:right;">List of drugs: </td>
										<td id="ajaxresponseR">
											<select id="pdruglist" multiple="multiple">';
				$sql='select AddictionId,Abbr,Name from addictions where AddictionId>?';
				$types="i";
				$params=array(0);
				$result1=query($types,$params,$sql);
				while($row=$result1->fetch_assoc()){
					$reply.='					<option value="'.$row['AddictionId'].'"';
					foreach($ids as $id){
						if($id==$row['AddictionId']){
							$reply.=' selected="selected" ';
						}
					}
					$reply.='>'.$row['Abbr'].' '.$row['Name'].'</option>';
				}mysqli_free_result($result1);
			$reply.=		'   			</select>
										</td>
									</tr>';
			}
			$reply.='				<tr>
										<td colspan="2" style="text-align:center;">
											<button type="button" onclick="personupdate();">Update</button>
										</td>
									</tr>
								</table>
							</div>
						</form>';
		}
		//Personal Info & Profile picture
		//Result
		if($edit==0){
			$reply.='<form>

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
										<td style="text-align:right;">Counsellor visits: </td>
										<td>'.$visits.'</td>
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
										<td style="text-align:right;">Spouse: </td>
										<td>'.$row['Spouse'].'</td>
									</tr>
									<tr>
										<td style="text-align:right;">Dependants: </td>
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
		}
	}mysqli_free_result($result);

//Person Intake Docs
    $cnt=0;
    $query=PersonProfileSql::get_person_intake_docs_count();
    $types="i";
    $params=array($personid);
    $res=query($types,$params,$query);
    while($row = $res -> fetch_assoc()){
        $cnt=$row['Count'];
    }mysqli_free_result($res);

	$query=PersonProfileSql::get_person_intake_docs();
	$types="i";
	$params=array($personid);
	$result=query($types,$params,$query);
	if($edit==1){
		$reply.='<table style="border:1px solid #cccccc;max-width:95%;margin:0 auto;padding:0 0;">
					<tr>
						<td style="text-align:center;">
							<form>
								<div id="personintakedocs" style="clear:both;" >
									<table class="alternate"  width="1200px" style="margin: 0 auto;" id="personintakedocstable">
										<thead>
										<tr style="font-weight:bold;">
											<th colspan="4" style="text-align:center;">
												<div>
													<span class="folder-button" onclick="folders(\'personintakedocsfolder\',\'personintakedocsbutton\');">Person Intake Documents</span>
													<button id="personintakedocsbutton" class="folder-content" style="float:right;width:50px;" type="button" onclick="addintakedocs();">Add</button>
												</div>
											</th>
										</tr>
										<thead>
										<tbody id="personintakedocsfolder" class="folder-content" style="padding: 5px auto;">
										<tr style="font-weight:bold;">
											<td style="border:1px solid #a6a6a6;text-align:center;vertical-align:middle;width:50px;">Delete</td>
											<td style="border:1px solid #a6a6a6;text-align:center;vertical-align:middle;width:150px;">Uploaded On</td>
											<td style="border:1px solid #a6a6a6;text-align:left;vertical-align:middle;width:100%">Document</td>
										</tr>';

        if($cnt > 0){
		    while($row=$result->fetch_assoc()){
                $intakeid=$row['IntakeId'];
                $ext=$row['Ext'];
                $notename=$row['NoteName'];
                $uploadedon=$row['UploadedOn'];
			    $reply.='				<tr style="text-align:center;">
											<td style="text-align:center;width:50px;">
												<input type="checkbox" id="deleteintakedoc" name="deleteintakedoc" />
											</td>
										    <td>
											    <input type="hidden" id="intakeid" name="intakeid" value="'.$intakeid.'" />
											    <input type="date" id="intakeuploadedon" name="intakeuploadedon" value="'.$uploadedon.'" />
										    </td>
											<td style="text-align:left;">
                                                <div id="personintakedocument'.$intakeid.'">
											        <a href="include/personintakedocs/'.$intakeid.'.'.$ext.'" >'.$notename.'</a>
                                                       &nbsp;
												    <input style="clear:both;" id="personintakedoc'.$intakeid.'" type="file" class="fileinput" name="file">
												    <button type="button" onclick="getpersonintakedoc('.$intakeid.');">Upload</button>
											    </div>
                                            </td>

										</tr>';
		    }
        }
		$reply.='						<tr>
											<td colspan="3" style="text-align:center;">
												<button type="button" onclick="personintakedocupdate();">Update</button>
											</td>
										</tr>
										</tbody>
									</table>
									</div>
							</form>
						</td>
					</tr>';
	}
	//
	//Result
	if($edit==0){
		$reply.='   <table style="border:1px solid #cccccc;width:800px;margin:0 auto;">
					<tr>
						<td style="text-align:center;">
							<form>
							<div id="personintakedocs" style="clear:both;" >
									<table class="alternate" width="800px" style="margin: 0 auto;" id="personintakedocsform">
										<thead>
										<tr style="font-weight:bold;">
											<th colspan="4" style="text-align:center;">
												<div>
													<span class="folder-button" onclick="folders(\'personintakedocsfolder\');">Person Intake Documents</span>

												</div>
											</th>
										</tr>
										<thead>
										<tbody id="personintakedocsfolder" class="folder-content">
										<tr style="font-weight:bold;">
											<td style="border:1px solid #a6a6a6;text-align:center;vertical-align:middle;width:250px;">Uploaded On</td>
											<td style="border:1px solid #a6a6a6;text-align:left;vertical-align:middle;width:80%;">Document</td>
										</tr>';


        if($cnt > 0){
            while($row=$result->fetch_assoc()){
			    $reply.='						<tr>
											    <td style="text-align:center;">'.$row['UploadedOn'].'</td>
											    <td style="text-align:left;"><a href="include/personintakedocs/'.$row['IntakeId'].'.'.$row['Ext'].'" >'.$row['NoteName'].'</a></td>
										    </tr>';
		    }
        }

		$reply.='							</tbody>
									</table>
									</div>
							</form>
						</td>
					</tr>';
	}

//Person Entry & Exit
	$query=PersonProfileSql::get_entry_exit();
	$types="ii";
	$params=array($personid,$personid);
	$result=query($types,$params,$query);
	$total=0;
	if($row=$result->fetch_assoc()){
		$total=$row['TotalSum'];
		mysqli_data_seek($result,0);
	}
//Person Entry & Exit
//Result & Editor
	if($edit==1){
		$reply.='		<tr>
							<td style="text-align:center;">
								<form>
									 <div id="entryexit" style="clear:both;" >
										<table class="alternate"  width="1200px" style="margin: 0 auto;" id="entryexitform">
											<thead>
											<tr style="font-weight:bold;">
												<th colspan="4" style="text-align:center;">
													<div>
														<span class="folder-button" onclick="folders(\'entryexitfolder\',\'entryexitbutton\');">Entry & Exit</span>
														<button id="entryexitbutton" class="folder-content" style="float:right;width:50px;" type="button" onclick="addentryexit();">Add</button>
													</div>
												</th>
											</tr>
											<thead>
											<tbody id="entryexitfolder" class="folder-content" style="padding: 5px auto;">
											<tr style="font-weight:bold;">
												<td style="border:1px solid #a6a6a6;text-align:center;vertical-align:middle;">Delete</td>
												<td style="border:1px solid #a6a6a6;text-align:center;vertical-align:middle;">Entry Date</td>
												<td style="border:1px solid #a6a6a6;text-align:center;vertical-align:middle;">Exit Date</td>
												<td style="border:1px solid #a6a6a6;text-align:center;vertical-align:middle;">Days  ('.$total.')</td>
												<td style="border:1px solid #a6a6a6;text-align:left;vertical-align:middle;"><span style="padding-left:30px;text-align:left;">Note</span></td>
											</tr>';

		while($row=$result->fetch_assoc()){
			$reply.='						<tr style="text-align:center;">
												<td style="text-align:center;width:50px;">
													<input type="checkbox" id="deleteentryexit" name="deleteentryexit" />
												</td>
												<td width="150px">
													<input type="hidden" id="pdateid" name="pdateid" value="'.$row['DateId'].'" />
													<input type="date" id="pentrydate" name="pentrydate" value="'.$row['EntryDate'].'" />
												</td>
												<td width="150px">
													<input type="date" id="pexitdate" name="pexitdate" value="'.$row['ExitDate'].'" />
												</td>
												<td width="75px">
													'.$row['Total'].'
												</td>
												<td width="775px">
													<textarea style="resize:vertical;min-height:60px;width:773px;" id="pnote" name="pnote" placeholder="Please make a note here.">'.$row['Note'].'</textarea>
												</td>
											</tr>';
		}
		$reply.='							<tr>
												<td colspan="5" style="text-align:center;">
													<button type="button" onclick="entryexitupdate();">Update</button>
												</td>
											</tr>
											</tbody>
										</table>
									 </div>
								</form>
							</td>
						</tr>';
	}
//Person Entry & Exit
//Result
	if($edit==0){
		$reply.='		<tr>
							<td style="text-align:center;">
								<form>
									<div id="entryexit"  style="width:800px;border:1;margin:0 auto;">
										<table class="alternate"  style="width:800px;border:1;margin:0 auto;">
											<thead>
											<tr style="font-weight:bold;">
												<th colspan="4" style="text-align:center;">
													<div>
														<span class="folder-button" onclick="folders(\'entryexitfolder\');">Entry & Exit</span>
													</div>
												</th>
											</tr>
											<thead>
											<tbody id="entryexitfolder" class="folder-content">
											<tr style="font-weight:bold;">
												<td width="200px" style="border:1px solid #a6a6a6;text-align:center;vertical-align:middle;">Entry Date</td>
												<td width="200px" style="border:1px solid #a6a6a6;text-align:center;vertical-align:middle;">Exit Date</td>
												<td width="75px" style="border:1px solid #a6a6a6;text-align:center;vertical-align:middle;">Days  ('.$total.')</td>
												<td width="400px" style="border:1px solid #a6a6a6;text-align:center;vertical-align:middle;">Note</td>
											</tr>';

		while($row=$result->fetch_assoc()){
			$reply.='						<tr>
												<td style="text-align:center;" >'.$row['EntryDate'].'</td>
												<td style="text-align:center;" >'.$row['ExitDate'].'</td>
												<td style="text-align:center;" >'.$row['Total'].'</td>
												<td style="text-align:center;" ><textarea style="resize:vertical;min-height:60px;width:398px;" >'.$row['Note'].'</textarea></td>
											</tr>';
		}
		$reply.='							</tbody>
										</table>
									 </div>
								</form>
							</td>
						</tr>';
	}
	mysqli_free_result($result);


//Person Medical
	$query=PersonProfileSql::get_medical();
	$types="i";
	$params=array($personid);
	$result=query($types,$params,$query);
	
//Person Medical
//Result & Editor
	if($edit==1){
		$reply.='		<tr>
							<td style="text-align:center;">
								<form>
									 <div id="medical" style="clear:both;" >
										<table class="alternate"  width="1200px" style="margin: 0 auto;" id="medicalform">
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
			$reply.='						<tr style="text-align:center;">
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
		}
		$reply.='							<tr>
												<td colspan="5" style="text-align:center;">
													<button type="button" onclick="medicalupdate();">Update</button>
												</td>
											</tr>
											</tbody>
										</table>
									 </div>
								</form>
							</td>
						</tr>';
	}
//Person Medical
//Result
	if($edit==0){
		$reply.='		<tr>
							<td style="text-align:center;">
								<form>
									<div id="medical"  style="width:800px;border:1;margin:0 auto;">
										<table class="alternate" style="width:800px;border:1;margin:0 auto;">
											<thead>
											<tr style="font-weight:bold;">
												<th colspan="4" style="text-align:center;">
													<div>
														<span class="folder-button" onclick="folders(\'medicalfolder\');">Medical</span>
													</div>
												</th>
											</tr>
											<thead>
											<tbody id="medicalfolder" class="folder-content">
											<tr style="font-weight:bold;">
												<td width="200px" style="border:1px solid #a6a6a6;text-align:center;vertical-align:middle;">HealthStatus</td>
												<td width="200px" style="border:1px solid #a6a6a6;text-align:center;vertical-align:middle;">Conditions</td>
												<td width="75px" style="border:1px solid #a6a6a6;text-align:center;vertical-align:middle;">UseMeds</td>
												<td width="400px" style="border:1px solid #a6a6a6;text-align:center;vertical-align:middle;">Medications</td>
											</tr>';

		while($row=$result->fetch_assoc()){
			$reply.='						<tr>
												<td style="text-align:center;" >';
													if($row['HealthStatus'] == 0){ $reply .= 'Excellent';}
													if($row['HealthStatus'] == 1){ $reply .= 'Good';}
													if($row['HealthStatus'] == 2){ $reply .= 'Poor';}$reply .= '</td>
												<td style="text-align:center;" ><textarea style="resize:vertical;min-height:60px;width:198px;" >'.$row['Conditions'].'</textarea></td>
												<td style="text-align:center;" >';
													if($row['UseMeds'] == 0){ $reply .= 'Yes';}
													if($row['UseMeds'] == 1){ $reply .= 'No';}$reply .= '</td>
												<td style="text-align:center;" ><textarea style="resize:vertical;min-height:60px;width:398px;" >'.$row['Medications'].'</textarea></td>
											</tr>';
		}
		$reply.='							</tbody>
										</table>
									 </div>
								</form>
							</td>
						</tr>';
	}
	mysqli_free_result($result);

//Person Leave
	$query=PersonProfileSql::get_leave();
	$types="ii";
	$params=array($personid,$personid);
	$result=query($types,$params,$query);
	$total=0;
	if($row=$result->fetch_assoc()){
		$total=$row['TotalSum'];
		mysqli_data_seek($result,0);
	}
//Person Leave
//Result & Editor
	if($edit==1){
			$reply.='	<tr>
							<td style="text-align:center;">
								<form>
									 <div id="leave" style="clear:both;" >
										<table class="alternate"  width="1200px" style="margin: 0 auto;" id="leaveform">
											<thead>
											<tr style="font-weight:bold;">
												<th colspan="4" style="text-align:center;">
													<div>
														<span class="folder-button" onclick="folders(\'leavefolder\',\'leavebutton\');">Leave</span>
														<button id="leavebutton" class="folder-content" style="float:right;width:50px;" type="button" onclick="addleave();">Add</button>
													</div>
												</th>
											</tr>
											<thead>
											<tbody id="leavefolder" class="folder-content">
											<tr style="font-weight:bold;">
												<td style="width:57px;border:1px solid #a6a6a6;text-align:center;vertical-align:middle;">Delete</td>
												<td style="width:150px;border:1px solid #a6a6a6;text-align:center;vertical-align:middle;">Start Date</td>
												<td style="width:150px;border:1px solid #a6a6a6;text-align:center;vertical-align:middle;">Finish Date</td>
												<td style="width:50px;border:1px solid #a6a6a6;text-align:center;vertical-align:middle;">Days ('.$total.')</td>
												<td style="width:100%;border:1px solid #a6a6a6;text-align:left;vertical-align:middle;"><span style="padding-left:30px;text-align:left;">Comment</span></td>
											</tr>';

		while($row=$result->fetch_assoc()){
			$reply.='						<tr>
												<td style="text-align:center;width:57px;">
													<input type="checkbox" id="deleteleave" name="deleteleave" />
												</td>
												<td width="150px">
													<input type="hidden" id="lleaveid" name="lleaveid" value="'.$row['LeaveId'].'" />
													<input type="date" id="lstartdate" name="lstartdate" value="'.$row['StartDate'].'" />
												</td>
												<td width="150px">
													<input type="date" id="lfinishdate" name="lfinishdate" value="'.$row['FinishDate'].'" />
												</td>
												<td style="text-align:center;">'.$row['Total'].'</td>
												<td width="300px">
													<textarea style="resize:vertical;min-height:60px;width:798px;" id="lcomment" name="lcomment" placeholder="Please write a comment here.">'.$row['Comment'].'</textarea>
												</td>
											</tr>';
		}
		$reply.='							<tr>
												<td colspan="5" style="text-align:center;">
													<button type="button" onclick="leaveupdate();">Update</button>
												</td>
											</tr>
											</tbody>
										</table>
									 </div>
								</form>
							</td>
						</tr>';
	}
//Person Leave
//Result
	if($edit==0){
		$reply.='		<tr>
							<td style="text-align:center;">
								<form>
									<div id="leave"  style="width:800px;border:1;margin:0 auto;">
										<table class="alternate" style="width:800px;border:1;margin:0 auto;">
											<thead>
												<tr style="font-weight:bold;">
													<th colspan="4" style="text-align:center;">
														<div>
															<span class="folder-button" onclick="folders(\'leavefolder\');">Leave</span>
														</div>
													</th>
												</tr>
											</thead>
											<tbody id="leavefolder" class="folder-content">
												<tr style="font-weight:bold;">
													<td width="200px" style="border:1px solid #a6a6a6;text-align:center;vertical-align:middle;">Start Date</td>
													<td width="200px" style="border:1px solid #a6a6a6;text-align:center;vertical-align:middle;">Finish Date</td>
													<td style="width:50px;border:1px solid #a6a6a6;text-align:center;vertical-align:middle;">Days ('.$total.')</td>
													<td width="400px" style="border:1px solid #a6a6a6;text-align:center;vertical-align:middle;">Comment</td>
												</tr>';

		while($row=$result->fetch_assoc()){
				$reply.='						<tr>
													<td style="text-align:center;">'.$row['StartDate'].'</td>
													<td style="text-align:center;">'.$row['FinishDate'].'</td>
													<td style="text-align:center;">'.$row['Total'].'</td>
													<td style="text-align:center;"><textarea style="resize:vertical;min-height:60px;width:398px;" >'.$row['Comment'].'</textarea></td>
												</tr>';
		}
		$reply.='							</tbody>
										</table>
									 </div>
								</form>
							</td>
						</tr>';
	}
	mysqli_free_result($result);

//Person Sick leave
	$query=PersonProfileSql::get_sick_leave();
	$types="ii";
	$params=array($personid,$personid);
	$result=query($types,$params,$query);

	$total=0;
	if($row=$result->fetch_assoc()){
		$total=$row['Total'];
		mysqli_data_seek($result,0);
	}
//Person Sick leave
//Result & Editor
	if($edit==1){
		$reply.='		<tr>
							<td style="text-align:center;">
								<form>
									<div id="sickleave" style="margin:0 auto;" width="800px">
										<table class="alternate" width="1200px"  id="sickleavetable">
											<thead>
											<tr style="font-weight:bold;">
												<th colspan="6" style="text-align:center;">
													<div>
														<span class="folder-button" onclick="folders(\'sickleavefolder\',\'sickleavebutton\');">Sick Leave</span>
														<button id="sickleavebutton" class="folder-content" style="float:right;width:50px;" type="button" onclick="addsickleave();">Add</button>
													</div>
												</th>
											</tr>
											<thead>
											<tbody id="sickleavefolder" class="folder-content">
											<tr style="font-weight:bold;">
												<td style="border:1px solid #a6a6a6;text-align:center;vertical-align:middle;">Delete</td>
												<td style="border:1px solid #a6a6a6;text-align:center;vertical-align:middle;">Start Date</td>
												<td style="border:1px solid #a6a6a6;text-align:center;vertical-align:middle;">Finish Date</td>
												<td style="border:1px solid #a6a6a6;text-align:center;vertical-align:middle;">Days ('.$total.')</td>
												<td style="border:1px solid #a6a6a6;text-align:center;vertical-align:middle;">Comment</td>
												<td style="border:1px solid #a6a6a6;text-align:left;vertical-align:middle;width:100%;"><span style="padding-left:30px;text-align:left;">Document</span></td>
											</tr>';
		while($row=$result->fetch_assoc()){
			$reply.='						<tr>
												<td  style="text-align:center;width:50px;">
													<input type="checkbox" name="psickdelete" />
												</td>
												<td>
													<input type="hidden" id="psickid" name="psickid" value="'.valscramble($row['SickId']).'" />
													<input type="date" id="psickstart" name="psickstart" value="'.$row['StartDate'].'" />
												</td>
												<td>
													<input type="date" id="psickfinish" name="psickfinish" value="'.$row['FinishDate'].'" />
												</td>
												<td style="text-align:center;width:60px;">'.$row['Days'].'</td>
												<td>
													<textarea id="psickcomment" name="psickcomment" style="resize:vertical;min-height:60px;width:498px;">'.$row['Comment'].'</textarea>
												</td>
												<td>
													<div id="persondoctornote'.valscramble($row['SickId']).'">';
			if($row['NoteName']=='No attachment'){
				$reply.='				'.$row['NoteName'].' ';
			}else{
				$reply.='								<a href="include/doctorsnotes/'.$row['NoteId'].'.'.$row['Ext'].'" >'.$row['NoteName'].'</a>';
			}
			$reply.='									<br>
														<input style="clear:left;" id="psicknote'.valscramble($row['SickId']).'" type="file" class="fileinput" name="file">
														<button type="button" onclick="getdoctorsnote('.valscramble($row['SickId']).');">Upload</button>
													</div>
												</td>
											</tr>';
		}
		$reply.='							<tr>
												<td colspan="6" style="text-align:center;">
													<button type="button" onclick="sickleaveupdate();">Update</button>
												</td>
											</tr>
										</tbody>
										</table>
									</div>
								</form>
							</td>
						</tr>';
	}
//Person Sick leave
//Result
	if($edit==0){
		$reply.='		<tr>
							<td style="text-align:center;">
								<form>
									<div id="sickleave" style="width:800px;border:1;margin:0 auto;">
										<table class="alternate" style="width:800px;border:1;margin:0 auto;">
											<thead>
											<tr style="font-weight:bold;">
												<th colspan="6" style="text-align:center;">
													<div>
														<span class="folder-button" onclick="folders(\'sickleavefolder\',\'sickleavebutton\');">Sick Leave</span>
													</div>
												</th>
											</tr>
											<thead>
											<tbody id="sickleavefolder" class="folder-content">
											<tr style="font-weight:bold;">
												<td style="border:1px solid #a6a6a6;text-align:center;vertical-align:middle;" width="15%">Start Date</td>
												<td style="border:1px solid #a6a6a6;text-align:center;vertical-align:middle;" width="15%">Finish Date</td>
												<td style="border:1px solid #a6a6a6;text-align:center;vertical-align:middle;" width="5%">Days ('.$total.')</td>
												<td style="border:1px solid #a6a6a6;text-align:center;vertical-align:middle;" width="40%">Comment</td>
												<td style="border:1px solid #a6a6a6;text-align:center;vertical-align:middle;" width="25%">Document</td>
											</tr>';
		while($row=$result->fetch_assoc()){
			$reply.='							<tr>
												<td style="text-align:center;">'.$row['StartDate'].'</td>
												<td style="text-align:center;">'.$row['FinishDate'].'</td>
												<td style="text-align:center;">'.$row['Days'].'</td>
												<td>
													<textarea style="resize:vertical;min-height:60px;width:298px;">'.$row['Comment'].'</textarea>
												</td>
												<td>';
			if($row['NoteName']=='No attachment'){
				$reply.='								'.$row['NoteName'].' ';
			}else{
				$reply.='							<a href="include/doctorsnotes/'.$row['NoteId'].'.'.$row['Ext'].'" >'.$row['NoteName'].'</a>';
			}
			$reply.='							</td>
											</tr>';
		}
		$reply.='						</tbody>
										</table>
									</div>
								</form>
							</td>
						</tr>';
	}

//Person spouse :)
	$query=PersonProfileSql::get_spouse();
	$types="i";
	$params=array($personid);
	$result=query($types,$params,$query);

//Person spouse :)
//Result & Editor
	if($edit==1){
			$reply.='	<tr>
							<td style="text-align:center;">
								<form>
									<div id="spouse" style="margin:0 auto;" width="800px">
										<table class="alternate" width="1200px" id="spousetable">
											<thead>
												<tr style="font-weight:bold;">
													<th colspan="6" style="text-align:center;">
														<div>
															<span class="folder-button" onclick="folders(\'spousefolder\',\'spousebutton\');">Spouse Details</span>
															<button id="spousebutton" class="folder-content" style="float:right;width:50px;" type="button" onclick="addspouse();">Add</button>
														</div>
													</th>
												</tr>
											<thead>
											<tbody id="spousefolder" class="folder-content">
												<tr style="font-weight:bold;">
													<td style="width:50px;border:1px solid #a6a6a6;text-align:center;vertical-align:middle;">Delete</td>
													<td style="border:1px solid #a6a6a6;text-align:center;vertical-align:middle;">Name</td>
													<td style="border:1px solid #a6a6a6;text-align:center;vertical-align:middle;">Age</td>
													<td style="border:1px solid #a6a6a6;text-align:center;vertical-align:middle;">ID No</td>
													<td style="width:100%;border:1px solid #a6a6a6;text-align:left;vertical-align:middle;"><span style="padding-left:30px;text-align:left;">On System</span></td>
												</tr>';
		while($row=$result->fetch_assoc()){
				$reply.='						<tr>
													<td style="text-align:center;width:50px;">
														<input type="checkbox" name="spousedelete" />
													</td>
													<td>
														<input type="hidden" id="spouseid" name="spouseid" value="'.valscramble($row['SpouseId']).'" />
														<input type="text" id="spousename" name="spousename" value="'.$row['Name'].' '.$row['Surname'].'" />
													</td>
													<td>
														'.$row['Age'].'
													</td>
													<td>
														<input type="text" name="spouseidno" id="spouseidno" value="'.$row['IDNo'].'"
													</td>
													<td>
														<select id="spouseonsystem" name="spouseonsystem">
															<option value="0">No data</option>
															<option value="1"';if($row['OnSystem']==1){$reply.=' selected ';}$reply.='>Yes</option>
															<option value="2"';if($row['OnSystem']==2){$reply.=' selected ';}$reply.='>No</option>
														</select>
													</td>
												</tr>';
		}
		$reply.='								<tr>
													<td colspan="6" style="text-align:center;">
														<button type="button" onclick="spouseupdate();">Update</button>
													</td>
												</tr>
										</tbody>
										</table>
									</div>
								</form>
							</td>
						</tr>';
	}
//Person spouse :) I cant wait to be married :D :D :D :D :D :D :D :D :D
//Result
	if($edit==0){
		$reply.='		<tr>
							<td style="text-align:center;">
								<form>
									<div id="spouse" style="width:800px;border:1;margin:0 auto;">
										<table class="alternate" style="width:800px;border:1;margin:0 auto;">
											<thead>
												<tr style="font-weight:bold;">
													<th colspan="6" style="text-align:center;">
														<div>
															<span class="folder-button" onclick="folders(\'spousefolder\',\'spousebutton\');">Spouse Details</span>
														</div>
													</th>
												</tr>
											<thead>
											<tbody id="spousefolder" class="folder-content">
												<tr style="font-weight:bold;">
													<td width="200px" style="border:1px solid #a6a6a6;text-align:center;vertical-align:middle;">Name</td>
													<td width="200px" style="border:1px solid #a6a6a6;text-align:center;vertical-align:middle;">Age</td>
													<td width="200px" style="border:1px solid #a6a6a6;text-align:center;vertical-align:middle;">ID No</td>
													<td width="200px" style="border:1px solid #a6a6a6;text-align:center;vertical-align:middle;">On System</td>
												</tr>';
		while($row=$result->fetch_assoc()){
				$reply.='						<tr>
													<td>
														'.$row['Name'].' '.$row['Surname'].'
													</td>
													<td>
														'.$row['Age'].'
													</td>
													<td>
														'.$row['IDNo'].'
													</td>
													<td>';if($row['OnSystem']==1){$reply.=' yes ';}else{$reply.=' no ';}$reply.='</td>
												</tr>';
		}
		$reply.='							</tbody>
										</table>
									</div>
								</form>
							</td>
						</tr>';
	}

//Person nodes
	$query=PersonProfileSql::get_nodes();
	$types="i";
	$params=array($personid);
	$result=query($types,$params,$query);

//Person nodes
//Result & Editor
	if($edit==1){
		$reply.='		<tr>
							<td style="text-align:center;">
								<form>
									<div id="nodes" style="margin:0 auto;" width="800px">
										<table class="alternate" width="1200px" id="nodestable">
											<thead>
												<tr style="font-weight:bold;">
													<th colspan="6" style="text-align:center;">
														<div>
															<span class="folder-button" onclick="folders(\'nodesfolder\',\'nodesbutton\');">Dependants Details</span>
															<button id="nodesbutton" class="folder-content" style="float:right;width:50px;" type="button" onclick="addnode();">Add</button>
														</div>
													</th>
												</tr>
											<thead>
											<tbody id="nodesfolder" class="folder-content">
												<tr style="font-weight:bold;">
													<td width="50px" style="left:25%;border:1px solid #a6a6a6;text-align:center;vertical-align:middle;">Delete</td>
													<td style="width:175px;border:1px solid #a6a6a6;text-align:center;vertical-align:middle;">Name</td>
													<td style="width:100%;border:1px solid #a6a6a6;text-align:left;vertical-align:middle;"><span style="padding-left:30px;text-align:left;">Age</span></td>
												</tr>';
		while($row=$result->fetch_assoc()){
		$reply.='								<tr>
													<td style="text-align:center;width:50px;">
														<input type="checkbox" name="nodedelete" />
													</td>
													<td style="text-align:center;">
														<input type="hidden" id="nodeid" name="nodeid" value="'.valscramble($row['NodeId']).'" />
														<input type="text" id="nodename" name="nodename" value="'.$row['Name'].'" />
													</td>
													<td style="text-align:left;">
														<input type="date" id="nodebday" name="nodebday" value="'.$row['BirthDate'].'" />
													</td>
												</tr>';
		}
		$reply.='								<tr>
													<td colspan="6" style="text-align:center;">
														<button type="button" onclick="nodeupdate();">Update</button>
													</td>
												</tr>
										</tbody>
										</table>
									</div>
								</form>
							</td>
						</tr>';
	}
//Person nodes
//Result
	if($edit==0){
		$reply.='		<tr>
							<td style="text-align:center;">
								<form>
									<div id="nodes" style="width:800px;border:1;margin:0 auto;">
										<table class="alternate" style="width:800px;border:1;margin:0 auto;">
											<thead>
												<tr style="font-weight:bold;">
													<th colspan="6" style="text-align:center;">
														<div>
															<span class="folder-button" onclick="folders(\'nodesfolder\',\'nodesbutton\');">Dependants Details</span>
														</div>
													</th>
												</tr>
											<thead>
											<tbody id="nodesfolder" class="folder-content">
												<tr style="font-weight:bold;">
													<td width="400px" style="border:1px solid #a6a6a6;text-align:center;vertical-align:middle;">Name</td>
													<td width="400px" style="border:1px solid #a6a6a6;text-align:center;vertical-align:middle;">Birth Date</td>
												</tr>';
		while($row=$result->fetch_assoc()){
		$reply.='								<tr>
													<td style="text-align:center;">
														'.$row['Name'].'
													</td>
													<td style="text-align:center;">
														'.$row['BirthDate'].'
													</td>
												</tr>';
		}
		$reply.='							</tbody>
										</table>
									</div>
								</form>
							</td>
						</tr>';
	}

//Person Education
	$query=PersonProfileSql::get_education();
	$types="i";
	$params=array($personid);
	$result=query($types,$params,$query);

//Person Education
//Result & Editor
	if($edit==1){
		$reply.='		<tr>
							<td style="text-align:center;">
								<form>
									<div id="qualification" style="margin:0 auto;" width="800px">
										<table class="alternate" width="1200px" id="educationtable">
											<thead>
												<tr style="font-weight:bold;">
													<th colspan="6" style="text-align:center;">
														<div>
															<span class="folder-button" onclick="folders(\'educationfolder\',\'educationbutton\');">Qualifications</span>
															<button id="educationbutton" class="folder-content" style="float:right;width:50px;" type="button" onclick="addqualification();">Add</button>
														</div>
													</th>
												</tr>
											<thead>
											<tbody id="educationfolder" class="folder-content">
												<tr style="font-weight:bold;" >
													<td width="50px" style="left:25%;border:1px solid #a6a6a6;text-align:center;vertical-align:middle;">Delete</td>
													<td width="1150px" style="border:1px solid #a6a6a6;text-align:left;vertical-align:middle;"><span style="padding-left:30px;text-align:left;">Qualification</span></td>
												</tr>';
		while($row=$result->fetch_assoc()){
		$reply.='								<tr>
													<td style="text-align:center;width:50px;">
														<input type="checkbox" name="qualdelete" />
													</td>
													<td style="text-align:center;">
														<input type="hidden" id="qualid" name="qualid" value="'.valscramble($row['QualId']).'" />
														<textarea name="qualification" style="resize:vertical;min-height:60px;width:1148px;">'.$row['Qualification'].'</textarea>
													</td>
												</tr>';
		}
		$reply.='								<tr>
													<td colspan="6" style="text-align:center;">
														<button type="button" onclick="qualificationupdate();">Update</button>
													</td>
												</tr>
										</tbody>
										</table>
									</div>
								</form>
							</td>
						</tr>';
	}
//Person Education
//Result
	if($edit==0){
		$reply.='		<tr>
							<td style="text-align:center;">
								<form>
									<div id="education" style="width:800px;border:1;margin:0 auto;">
										<table class="alternate" style="width:800px;border:1;margin:0 auto;">
											<thead>
												<tr style="font-weight:bold;">
													<th colspan="6" style="text-align:center;">
														<div>
															<span class="folder-button" onclick="folders(\'educationfolder\',\'educationbutton\');">Qualifications</span>
														</div>
													</th>
												</tr>
											<thead>
											<tbody id="educationfolder" class="folder-content">
												<tr style="font-weight:bold;">
													<td width="800px" style="border:1px solid #a6a6a6;text-align:center;vertical-align:middle;">Qualification</td>
												</tr>';
		while($row=$result->fetch_assoc()){
		$reply.='								<tr>
													<td style="text-align:center;">
														<textarea style="resize:vertical;min-height:60px;width:798px;">'.$row['Qualification'].'</textarea>
													</td>
												</tr>';
		}
		$reply.='							</tbody>
										</table>
									</div>
								</form>
							</td>
						</tr>';
	}

//Person Employment
	$query=PersonProfileSql::get_employment();
	$types="i";
	$params=array($personid);
	$result=query($types,$params,$query);

//Person Employment
//Result & Editor
	if($edit==1){
		$reply.='		<tr>
							<td style="text-align:center;">
								<form>
									<div id="employment" style="margin:0 auto;" width="800px">
										<table class="alternate" width="1200px" id="employtable">
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
										</table>
									</div>
								</form>
							</td>
						</tr>';
	}
//Person Employment
//Result
	if($edit==0){
		$reply.='		<tr>
							<td style="text-align:center;">
								<form>
									<div id="employment" style="width:800px;border:1;margin:0 auto;">
										<table class="alternate" style="width:800px;border:1;margin:0 auto;">
											<thead>
												<tr style="font-weight:bold;">
													<th colspan="6" style="text-align:center;">
														<div>
															<span class="folder-button" onclick="folders(\'employfolder\',\'employbutton\');">Employment</span>
														</div>
													</th>
												</tr>
											<thead>
											<tbody id="employfolder" class="folder-content">
												<tr style="font-weight:bold;" >
													<td width="250px" style="border:1px solid #a6a6a6;text-align:center;vertical-align:middle;">Employer</td>
													<td width="250px" style="border:1px solid #a6a6a6;text-align:center;vertical-align:middle;">Contact Person</td>
													<td width="300px" style="border:1px solid #a6a6a6;text-align:center;vertical-align:middle;">Contact Number</td>
												</tr>';
		while($row=$result->fetch_assoc()){
		$reply.='								<tr>
													<td>
														'.$row['Employer'].'
													</td>
													<td>
														'.$row['Name'].'
													</td>
													<td>
														'.$row['CellNo'].'
													</td>
												</tr>';
		}
		$reply.='							</tbody>
										</table>
									</div>
								</form>
							</td>
						</tr>';
	}

//Disciplinary
	$query='select SecId,UserId from secusers where UserId=?';
	$types="i";
	$params=array($userid);
	$result=query($types,$params,$query);
	while($row=$result->fetch_assoc()){
		$tsecid=$row['SecId'];
		$tuserid=$row['UserId'];
	}mysqli_free_result($result);
	if($tuserid==$userid && $tsecid==$secid && $secid<4 && $secid>0){
		$query=PersonProfileSql::get_disciplinary();
		$types='i';
		$params=array($personid);
		$result=query($types,$params,$query);

		if($edit==1){
			$reply.='		<tr>
								<td style="text-align:center;">
									<form>
										<div id="disciplinary" style="margin:0 auto;" width="800px">
											<table class="alternate" style="width:1200px;border:1;margin:0 auto;" id="disciplinarytable">
												<thead>
													<tr style="font-weight:bold;">
														<th colspan="6" style="text-align:center;">
															<div>
																<span class="folder-button" onclick="folders(\'disciplinaryfolder\',\'disciplinarybutton\');">Disciplinary</span>
																<button id="disciplinarybutton" class="folder-content" style="float:right;width:50px;" type="button" onclick="adddisciplinary();">Add</button>
															</div>
														</th>
													</tr>
												<thead>
												<tbody id="disciplinaryfolder" class="folder-content">
													<tr style="font-weight:bold;" >
														<td width="50px" style="border:1px solid #a6a6a6;text-align:center;vertical-align:middle;">Delete</td>
														<td width="" style="border:1px solid #a6a6a6;text-align:center;vertical-align:middle;">Offence</td>
														<td width="" style="border:1px solid #a6a6a6;text-align:center;vertical-align:middle;">Offence Date</td>
														<td width="" style="border:1px solid #a6a6a6;text-align:center;vertical-align:middle;">Captured On</td>
														<td width="" style="border:1px solid #a6a6a6;text-align:center;vertical-align:middle;">Discipline</td>
														<td width="" style="border:1px solid #a6a6a6;text-align:center;vertical-align:middle;">Restart</td>
														<td width="" style="border:1px solid #a6a6a6;text-align:center;vertical-align:middle;">Document</td>
													</tr>';
			while($row=$result->fetch_assoc()){
				$discid=$row['DiscId'];
				$offenceid=$row['OffenceId'];
				$offencedate=$row['OffenceDate'];
				$capturedate=$row['CaptureDate'];
				$discipline=$row['Discipline'];
				$restart=$row['Restart'];
				$docid=$row['DocId'];
				$ext=$row['Ext'];
				$name=$row['Name'];
				$notename=$row['NoteName'];
			$reply.='								<tr>
														<td style="text-align:center;width:50px;">
															<input type="checkbox" name="disciplinarydelete" />
														</td>
														<td style="text-align:center;">
															<input type="hidden" id="discid" name="discid" value="'.valscramble($discid).'" />
															<select name="offence">';
				$query='select OffenceId as Id,Offence as Of from offences where OffenceId>?';
				$types='i';
				$params=array(0);
				$res=query($types,$params,$query);
				while($row=$res->fetch_assoc()){
					$reply.='									<option value='.$row['Id'].' ';if($row['Id'] == $offenceid){$reply.=' selected';}$reply.=' >'.$row['Of'].'</option>';
				}mysqli_free_result($res);
			$reply.='										</select>
														</td>
														<td>
															<input type="date" id="disciplineoffencedate" name="disciplineoffencedate" value="'.$offencedate.'" />
														</td>
														<td>
															<input type="date" id="disciplinecapturedate" name="disciplinecapturedate" value="'.$capturedate.'" />
														</td>
														<td>
															<textarea name="disciplinetext" id="disciplinetext" style="resize:vertical;min-height:60px;width:298px;">'.$discipline.'</textarea>
														</td>
														<td>
															<select name="restart" id="restart">
																<option value="0" ';if($restart==0){$reply.='selected';}$reply.='>No Restart</option>
																<option value="1" ';if($restart==1){$reply.='selected';}$reply.='>Restart</option>
															</select>
														</td>
														<td>
															<div id="disciplinarydocdiv'.valscramble($discid).'">';
				if($row['NoteName']=='No attachment'){
					$reply.='									'.$notename.' ';
				}else{
					$reply.='									<a href="include/persondocs/'.$docid.'.'.$ext.'" >'.$notename.'</a>';
				}
				$reply.='										<br>
																<input style="clear:left;" id="disciplinarydoc'.valscramble($discid).'" type="file" class="fileinput" name="file">
																<button type="button" onclick="persondisciplinedoc('.valscramble($discid).');">Upload</button>
															</div>
														</td>
													</tr>';
			}
			$reply.='								<tr>
														<td colspan="7" style="text-align:center;">
															<button type="button" onclick="persondisciplineupdate();">Update</button>
														</td>
													</tr>
												</tbody>
											</table>
										</div>
									</form>
								</td>
							</tr>';
		}
		if($edit==0){
			$reply.='		<tr>
								<td style="text-align:center;">
									<form>
										<div id="disciplinary" style="width:800px;border:1;margin:0 auto;">
											<table class="alternate" style="width:800px;border:1;margin:0 auto;" id="disciplinarytable">
												<thead>
													<tr style="font-weight:bold;">
														<th colspan="6" style="text-align:center;">
															<div>
																<span class="folder-button" onclick="folders(\'disciplinaryfolder\',\'disciplinarybutton\');">Disciplinary</span>
															</div>
														</th>
													</tr>
												<thead>
												<tbody id="disciplinaryfolder" class="folder-content">
													<tr style="font-weight:bold;" >
														<td width="" style="border:1px solid #a6a6a6;text-align:center;vertical-align:middle;">Offence</td>
														<td width="" style="border:1px solid #a6a6a6;text-align:center;vertical-align:middle;">Offence Date</td>
														<td width="" style="border:1px solid #a6a6a6;text-align:center;vertical-align:middle;">Captured On</td>
														<td width="" style="border:1px solid #a6a6a6;text-align:center;vertical-align:middle;">Discipline</td>
														<td width="" style="border:1px solid #a6a6a6;text-align:center;vertical-align:middle;">Restart</td>
														<td width="" style="border:1px solid #a6a6a6;text-align:center;vertical-align:middle;">Document</td>
													</tr>';
			while($row=$result->fetch_assoc()){
				$discid=$row['DocId'];
				$offence=$row['Offence'];
				$offencedate=$row['OffenceDate'];
				$capturedate=$row['CaptureDate'];
				$discipline=$row['Discipline'];
				$restart=$row['Restart'];
				$docid=$row['DocId'];
				$ext=$row['Ext'];
				$name=$row['Name'];
			$reply.='								<tr>
														<td style="text-align:center;">
															'.$offence.'
														</td>
														<td>
															'.$offencedate.'
														</td>
														<td>
															'.$capturedate.'
														</td>
														<td>
															<textarea name="discipline" id="discipline" style="resize:vertical;min-height:60px;width:298px;">'.$discipline.'</textarea>
														</td>
														<td>
															';if($restart==0){$reply.='No Restart';}$reply.='
															';if($restart==1){$reply.='Restart';}$reply.='
														</td>
														<td>
															<a href="include/persondocs/'.$row['DocId'].'.'.$row['Ext'].'" >'.$row['NoteName'].'</a>
														</td>
													</tr>';
			}
			$reply.='							</tbody>
											</table>
										</div>
									</form>
								</td>
							</tr>';
		}
	}

//Department
	$query=PersonProfileSql::get_department();
	$types="i";
	$params=array($personid);
	$result=query($types,$params,$query);
//Person Department
//Result & Editor
if($edit==1){
		$reply.='		<tr>
							<td style="text-align:center;">
								<form>
									<div id="department" style="margin:0 auto;" width="800px">
										<table class="alternate" width="1200px" id="departmenttable">
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
													<td width="175px" style="border:1px solid #a6a6a6;text-align:center;vertical-align:middle;">Department</td>
													<td width="175px" style="border:1px solid #a6a6a6;text-align:center;vertical-align:middle;">Start Date</td>
													<td width="100%" style="border:1px solid #a6a6a6;text-align:left;vertical-align:middle;"><span style="padding-left:30px;text-align:left;">Finish Date</span></td>
												</tr>';
		while($row=$result->fetch_assoc()){
			$persondeptid=$row['PersonDeptId'];
			$deptid=$row['DeptId'];
			$startdate=$row['StartDate'];
			$finishdate=$row['FinishDate'];
			$reply.='							<tr>
													<td style="text-align:center;width:50px;">
														<input type="checkbox" name="departmentdelete" />
													</td>
													<td style="text-align:left;">
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
													<td style="text-align:left;">
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
										</table>
									</div>
								</form>
							</td>
						</tr>';
	}
//Person Department
//Result
	if($edit==0){
		$reply.='		<tr>
							<td style="text-align:center;">
								<form>
									<div id="department" style="width:800px;border:1;margin:0 auto;">
										<table class="alternate" style="width:800px;border:1;margin:0 auto;" id="departmenttable">
											<thead>
												<tr style="font-weight:bold;">
													<th colspan="6" style="text-align:center;">
														<div>
															<span class="folder-button" onclick="folders(\'departmentfolder\',\'departmentbutton\');">Department</span>
														</div>
													</th>
												</tr>
											<thead>
											<tbody id="departmentfolder" class="folder-content">
												<tr style="font-weight:bold;" >
													<td width="300px" style="border:1px solid #a6a6a6;text-align:center;vertical-align:middle;">Department</td>
													<td width="250px" style="border:1px solid #a6a6a6;text-align:center;vertical-align:middle;">StartDate</td>
													<td width="250px" style="border:1px solid #a6a6a6;text-align:center;vertical-align:middle;">FinishDate</td>
												</tr>';
		while($row=$result->fetch_assoc()){
		$reply.='								<tr>
													<td style="text-align:center;">
														'.$row['Name'].'
													</td>
													<td style="text-align:center;">
														'.$row['StartDate'].'
													</td>
													<td style="text-align:center;">
														'.$row['FinishDate'].'
													</td>
												</tr>';
		}
		$reply.='							</tbody>
										</table>
									</div>
								</form>
							</td>
						</tr>';
	}

//Return result
	echo($reply);
?>














