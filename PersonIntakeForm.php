<?php
	require_once('vmscFunctions.php');
	//SysParam & Auto Logout & Logging
	$userid=valunscramble($_GET[idunscramble('userid')]);
	$secid=valunscramble($_GET[idunscramble('secid')]);
	timer(20,$userid);
	//Sys logs
	$array=array('user',$userid);
	$tables=array('');
	logs($userid,'l','PersonIntakeForm',$array,$tables);

	$reply='';
	$reply='<h4 style="font-size:20px;margin:2% 35% 2% 35%;">Personnel Intake Form 
				<a style="text-decoration:none;font-weight:normal;font-size:0.8em;" href="PersonIntakePrintForm.php" target="_blank">Print</a>
			</h4>
            <h5 style="font-size:12px;margin:2% 35% 2% 35%;">ID Number field: 7 digits must be included.<br>The first 6 digits are for their birthday (YYMMDD).<br>The 7th digit is for their gender.<br>5 and greater for male.<Br>4 and smaller for female.
            </h5>
				<form style="width:600px;margin:0 auto;padding:0 auto;">
					<table width="100%" style="border:0;" id="intakeform">
					<tbody>
						
						<tr>
							<td colspan="2">
								<table class="alternate" id="personalinfotable" width="600px">
									<thead>
										<tr>
											<td colspan="2" style="text-align:center;font-weight:bold;">
												<span class="folder-button" onclick="folders(\'personalinfofolder\');">
													Personal Information :
												</span>
											</td>
										</tr>			
									</thead>
									<tbody id="personalinfofolder" class="folder-content">
										<tr>
											<td style="text-align:right;">Full Name</td>
											<td>
												<input type="text" id="ifirstname" placeholder="Full Names"  size="38"  />
											</td>
										</tr>
										<tr>
											<td style="text-align:right;">Surname</td>
											<td>
												<input type="text" id="isurname" placeholder="Surname"  size="38" />
											</td>
										</tr>
										<tr>
											<td style="text-align:right;">ID Number</td>
											<td>
												<input type="text" id="iidno" placeholder="Identity Number"  size="20" />
											</td>
										</tr>
										<tr>
											<td style="text-align:right;">Driver\'s Licence</td>
											<td>
												<label><input type="radio" name="idrivers"/> Yes</label><br>
												<label><input type="radio" name="idrivers"/> No</label>
											</td>
										</tr>
										<tr>
											<td style="text-align:right;">S.A.S.S.A. Registered</td>
											<td>
												<label><input type="radio" name="isassa" /> Yes</label><br>
												<label><input type="radio" name="isassa" /> No</label>
											</td>
										</tr>
										<tr>
											<td style="text-align:right;">Addictions (Multiple)</td>
											<td>';
					$query='select AddictionId,Name,Abbr 
							  from addictions 
							 where AddictionId > ?';
					$types='i';
					$params=array(0);
					$result=query($types,$params,$query);
					while($row=$result->fetch_assoc()){
						$reply.=				'<label><input type="checkbox" name="iaddictions" value="'.$row['AddictionId'].'"/> '.$row['Name'].' '.$row['Abbr'].'</label><br>';
					}
					$reply.=				'</td>
										</tr>			
									</tbody>
								</table>
							</td>
						</tr>
						
						<tr>
							<td>
								<table class="alternate" id="eduandemploytable"  width="600px">
									<thead>
										<tr>
											<td colspan="2" style="text-align:center;font-weight:bold;">
												<span class="folder-button" onclick="folders(\'eduandemployfolder\');">
													Education & Employment :
												</span>
											</td>
										</tr>
									</thead>
									<tbody id="eduandemployfolder" class="folder-content">
										<tr>
											<td style="text-align:right;" width="50%">Highest Qualification</td>
											<td>
												<textarea style="resize:vertical;min-height:60px;width:298px;" id="iqualification"></textarea>
											</td>
										</tr>
										<tr>
											<td style="text-align:right;">Previous Employer</td>
											<td><input type="text" id="iemployname" placeholder="Employer"  size="38" /></td>
										</tr>
										<tr>
											<td style="text-align:right;">Contact Person</td>
											<td><input type="text" id="iemploycontactperson" placeholder="Contact Person" size="20" /></td>
										</tr>
										<tr>
											<td style="text-align:right;">Contact number</td>
											<td><input type="text" id="iemploycontact" placeholder="Contact number" size="20" /></td>
										</tr>			
									</tbody>
								</table>
							</td>
						</tr>
						
						<tr>
							<td>
								<table class="alternate" id="maritalstatustable"  width="600px">
									<thead>
										<tr>
											<td colspan="2" style="text-align:center;font-weight:bold;">
												<span class="folder-button" onclick="folders(\'maritalstatusfolder\');">
													Marital Status :
												</span>
											</td>
										</tr>
									</thead>
									<tbody id="maritalstatusfolder" class="folder-content">
										<tr>
											<td style="text-align:right;" width="50%">Marital Status</td>
											<td>
												<label><input type="radio" name="istatus"/> Single</label><br>
												<label><input type="radio" name="istatus"/> Married</label><br>
												<label><input type="radio" name="istatus"/> Divorced</label><br>
												<label><input type="radio" name="istatus"/> Widowed</label><br>
											</td>
										</tr>
										<tr>
											<td colspan="2">If married, please provide your spouse\'s details:</td>
										</tr>
										<tr>
											<td style="text-align:right;">Full Name</td>
											<td>
												<input type="text" id="ispousename" placeholder="Full Name"  size="38" />
											</td>
										</tr>
										<tr>
											<td style="text-align:right;">Surname</td>
											<td>
												<input type="text" id="ispousesurname" placeholder="Surname"  size="38" />
											</td>
										</tr>
										<tr>
											<td style="text-align:right;">ID Number</td>
											<td>
												<input type="text" id="ispouseidno" placeholder="Identity Number"  size="20" />
											</td>
										</tr>						
									</tbody>
								</table>
							</td>
						</tr>
						
						<tr>
							<td>
								<table class="alternate" id="dependantstable" width="600px">
									<thead>
										<tr>
											<td colspan="2" style="text-align:center;font-weight:bold;">
												<span class="folder-button" onclick="folders(\'dependantsfolder\');">
													Dependants :
												</span>
											</td>
										</tr>
									</thead>
									<tbody id="dependantsfolder" class="folder-content">
										<tr>
											<td style="text-align:center;font-weight:bold;">Age</td>
											<td style="text-align:center;font-weight:bold;" width="50%">Child\'s Name</td>
										</tr>
										<tr>
											<td style="text-align:right;"><input type="date" name="ispousekidsbdays" /></td>
											<td><input type="text" name="ispousekidsnames" placeholder="Full Name" size="38" /></td>
										</tr>
										<tr>
											<td style="text-align:right;"><input type="date" name="ispousekidsbdays" /></td>
											<td><input type="text" name="ispousekidsnames" placeholder="Full Name" size="38" /></td>
										</tr>
										<tr>
											<td style="text-align:right;"><input type="date" name="ispousekidsbdays" /></td>
											<td><input type="text" name="ispousekidsnames" placeholder="Full Name" size="38" /></td>
										</tr>
										<tr>
											<td style="text-align:right;"><input type="date" name="ispousekidsbdays" /></td>
											<td><input type="text" name="ispousekidsnames" placeholder="Full Name" size="38" /></td>
										</tr>						
									</tbody>
								</table>
							</td>
						</tr>
						
						<tr>
							<td>
								<table class="alternate" id="emergconttable" width="600px">
									<thead>
										<tr>
											<td colspan="2" style="text-align:center;font-weight:bold;">
												<span class="folder-button" onclick="folders(\'emergcontfolder\');">
													Emergency Contact Details :
												</span>
											</td>
										</tr>
									</thead>
									<tbody id="emergcontfolder" class="folder-content">
											<tr>
											<td style="text-align:right;" width="50%">Contact Name</td>
											<td>
												<input type="text" id="iemergencyname"  size="38" />
											</td>
										</tr>
										<tr>
											<td style="text-align:right;">Contact Number</td>
											<td>
												<input type="text" id="iemergencynumber"  size="20" />
											<td>
										</tr>
										<tr>
											<td style="text-align:right;">Contact Address</td>
											<td>
												<textarea style="resize:vertical;min-height:60px;width:298px;" id="iemergencyaddress"></textarea>
											</td>
										</tr>			
									</tbody>
								</table>
							</td>
						</tr>
						
						<tr>
							<td>
								<table class="alternate" id="table" width="600px">
									<thead>
										<tr>
											<td colspan="2" style="text-align:center;font-weight:bold;">
												<span class="folder-button" onclick="folders(\'folder\');">
													Medical Condition :
												</span>
											</td>
										</tr>
									</thead>
									<tbody id="folder" class="folder-content">
										<tr>
											<td style="text-align:right;" width="50%">Medical Status</td>
											<td>
												<label><input type="radio" name="ihealth"/> Excellent</label><br>
												<label><input type="radio" name="ihealth"/> Good</label><br>
												<label><input type="radio" name="ihealth"/> Poor</label><br>
											</td>
										</tr>
										<tr>
											<td style="text-align:right;">Medical Conditions</td>
											<td>
												<textarea style="resize:vertical;min-height:60px;width:298px;" id="ihealthnote"></textarea>
											</td>
										</tr>
										<tr>
											<td style="text-align:right;">Do you use medication</td>
											<td>
												<label><input type="radio" name="imeds" /> Yes</label><br>
												<label><input type="radio" name="imeds" /> No</label>
											</td>
										</tr>
										<tr>
											<td style="text-align:right;">List of medications</td>
											<td>
												<textarea style="resize:vertical;min-height:60px;width:298px;" id="imedlist"></textarea>
											</td>
										</tr>								
									</tbody>
								</table>
							</td>
						</tr>
						
						<tr>
							<td colspan="2" style="text-align:center;">
								<button type="button" onclick="personintakesave();">Add</button>
							</td>
						</tr>
					</tbody>
					</table>
				</form>';

	echo($reply);

?>