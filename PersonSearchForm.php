<?php
	require_once('vmscFunctions.php');
	//SysParam & Auto Logout & Logging
	$userid=str_clean(valunscramble($_GET[idunscramble('userid')]));
	$secid=str_clean(valunscramble($_GET[idunscramble('secid')]));
	timer(20,$userid);
//Sys logs
	$array=array('user',$userid);
	$tables=array('');
	logs($userid,'l','PersonSearchForm',$array,$tables);
	
	$reply='';
	$reply='<h4 style="font-size:20px;margin:2% 35% 0% 45%;">Personnel Search Form</h4>
            <h4 style="font-size:20px;margin:0% 34% 2% 43%;">Please enter the search criteria</h4>
			<form style="width:500px;margin:0 auto;padding:0 auto;">
				<table width="100%" style="border:0px;" id="searchform">
					<tr>
						<td style="text-align:right;" width="50%">Name</td>
						<td>
							<input type="text" id="firstname" placeholder="If empty it\'s omitted in search" length="45" />
						</td>
					</tr>
					<tr>
						<td style="text-align:right;">Surname</td>
						<td>
							<input type="text" id="surname" placeholder="If empty it\'s omitted in search" />
						</td>
					</tr>
					<tr>
						<td style="text-align:right;">ID Number</td>
						<td>
							<input type="text" id="idno" placeholder="If empty it\'s omitted in search" />
						</td>
					</tr>
					<tr>
						<td style="text-align:right;">Dependants</td>
						<td>
							<select id="kids">
								<option value="0">Leave out of search criteria</option>
								<option value="1">Yes</option>
								<option value="2">No</option>
							</select>
						</td>
					</tr>
					<tr>
						<td style="text-align:right;">Spouse</td>
						<td>
							<select id="spouse">
								<option value="0">Leave out of search criteria</option>
								<option value="1">Yes</option>
								<option value="2">No</option>
							</select>
						</td>
					</tr>
					<tr>
						<td style="text-align:right;">Age Group</td>
						<td>
							<select id="age">
								<option value="0">Leave out of search criteria</option>';	
								for($x=15 ;$x<100;$x+=10){
									$y=$x+10;
									$reply.='<option value="'.$x.'">'.$x.'-'.$y.'</option>';
									//$y=$y+10;
								}
	$reply.='				</select>
						</td>
					</tr>
					<tr>
						<td style="text-align:right;">Gender</td>
						<td>
							<select id="sex">
								<option value="0">Leave out of search criteria</option>
								<option value="1">Female</option>
								<option value="2">Male</option>
							</select>
						</td>
					</tr>
					<tr>
						<td style="text-align:right;">Addictions</td><td>
							<select id="addict" onchange="loadaddictions(this);">
								<option value="0">Include both</option>
								<option value="1">No</option>
								<option value="2">Yes</option>
							</select>
						</td>
					</tr>
					<tr>
						<td style="text-align:right;">From Date</td>
						<td><input type="date" id="entry" /></td>
					</tr>
					<tr>
						<td style="text-align:right;">To Date</td>
						<td><input type="date" id="exit" /></td>
					</tr>
					<tr>
						<td style="text-align:right;">Completed</td><td>
							<select id="completed">
								<option value="0">Leave out of search criteria</option>';
	$query='select CompleteId, Name from completestatus where CompleteId>?';
	$types="i";
	$params=array(0);
	$result=query($types,$params,$query);
	while($row=$result->fetch_assoc()){
		$reply.='				<option value="'.$row['CompleteId'].'">'.$row['Name' ].'</option>';
	}mysqli_free_result($result);
	$reply.='				</select>
						</td>
					</tr>
					<tr>
						<td colspan="2" style="text-align:center;">
							<button type="button" onclick="personsearch();">Search</button>
						</td>
					</tr>
				</table>
			</form>';
	
	echo($reply);
	
?>