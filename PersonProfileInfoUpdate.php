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
	//echo("<pre>");var_dump($_GET);echo("</pre>");
	$length=0;
	$personid=str_clean(valunscramble($_GET['personid']));
	$name=str_clean($_GET['name']);
	$surname=str_clean($_GET['surname']);
//IDNO
	if(str_clean($_GET['idno'])==''){
		$idno='';
	}else{
		$idno=str_clean($_GET['idno']);
	}
//Drivers
	$drivers=str_clean($_GET['drivers']);
//Sassa
	$sassa=str_clean($_GET['sassa']);
//ADDICT
	if(str_clean($_GET['addict'])==0){
		$addict=0;
		$druglist='-';
	}if(str_clean($_GET['addict'])==2){
		$addict=2;			
		if(str_clean(implode(",",$_GET['druglist']))==''){
			$druglist='-';
		}else{
			$length=sizeof($_GET['druglist']);
			$druglist=str_clean(implode(",",$_GET['druglist']));
		}
	}
	if(str_clean($_GET['addict'])==1){
		$addict=1;
		$druglist='-';
	}
//Completed
	if(str_clean($_GET['completed'])==''){
		$complete=0;
	}else{
		$complete=str_clean($_GET['completed']);
	}
	
	$query=PersonProfileSql::update_profile_info();;
	$types="sssiiiii";
	$params=array($name,$surname,$idno,$addict,$drivers,$sassa,$complete,$personid);
	$result=query($types,$params,$query);
	
	$query='delete from personaddictions where PersonId=?';
	$types="i";
	$params=array($personid);
	$result=query($types,$params,$query);
	
	if($length>0){
		$list=explode(',',$druglist);
		foreach($list as $drug){
			$query='insert into personaddictions(PersonId,AddictionId)values(?,?)';
			$types="ii";
			$params=array($personid,$drug);
			$result=query($types,$params,$query);
		}
	}
	$query=PersonProfileSql::get_info_profile();
	$types="i";
	$params=array($personid);
	$result=query($types,$params,$query);
	$reply='';
	
	while($row=$result->fetch_assoc()){
		$ids=explode(",",$row['AddictionIdList']);
		$completeid=$row['CompletedId'];
		$gender=$row['Gender'];
		$spouse=$row['Spouse'];
		$dependants=$row['Dependants'];
		$sickdays=$row['SickDays'];
		$addictint=$row['AddictInt'];

			$reply.='			<h4 style="font-size:20px">
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
										<td style="text-align:right;">Drivers: </td>
										<td>
											<select name="drivers" id="drivers">
												<option value="0"';if($row['Drivers']=='Not Recorded'){$reply.=' selected';}$reply.='>Not Recorded</option>
												<option value="1"';if($row['Drivers']=='Yes'){$reply.=' selected';}$reply.='>Yes</option>
												<option value="2"';if($row['Drivers']=='No'){$reply.=' selected';}$reply.='>No</option>
											</select>
										</td>
									</tr>
									<tr>
										<td style="text-align:right;">Sassa: </td>
										<td>
											<select name="sassa" id="sassa">
												<option value="0"';if($row['Sassa']=='Not Recorded'){$reply.=' selected';}$reply.='>Not Recorded</option>
												<option value="1"';if($row['Sassa']=='Yes'){$reply.=' selected';}$reply.='>Yes</option>
												<option value="2"';if($row['Sassa']=='No'){$reply.=' selected';}$reply.='>No</option>
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
										<td style="text-align:right;">Spouse</td>
										<td>'.$spouse.'</td>
									</tr>
									<tr>
										<td style="text-align:right;">Dependants</td>
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
										<td style="text-align:right;">List of drugs</td>
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
				$reply.=		'   		</select>
										</td>
									</tr>';
			}
			$reply.='				<tr>
										<td colspan="2" style="text-align:center;">
											<button type="button" onclick="personupdate();">Update</button>
										</td>
									</tr>
								</table>';
	}
	echo($reply);

?>
