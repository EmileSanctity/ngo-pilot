<?php
	require_once('vmscFunctions.php');

//SysParam & Auto Logout & Logging
	$userid=str_clean(valunscramble($_GET[idunscramble('userid')]));
	//$secid=str_clean(valunscramble($_GET[idunscramble('secid')]));
	timer(20,$userid);

//Sys logs
	$array=array('user',$userid);
	$tables=array('');
	logs($userid,'l','SysMaintenanceOffenceUpdate',$array,$tables);

//App Params
	//var_dump($_GET);
	
	$reply='';
	$deletes=explode(",",str_clean($_GET['deletes']));
	$ids=explode(",",str_clean($_GET['ids']));
	$offences=explode(",",str_clean($_GET['names']));
	$descrs=explode(",",str_clean($_GET['descrs']));
	
	foreach($ids as $x => $id){
		if($id=='0' && $deletes[$x] == 'false'){
			$query='insert into offences(Offence,Description)values(?,?)';
			$types="ss";
			$params=array($offences[$x],$descrs[$x]);
			//echo('<br>'.implode(",",$params));
			$result=query($types,$params,$query);
		}else{
			if($deletes[$x] == 'false'){
				$query='update offences
						   set Offence=?,
							   Description=?
						 where OffenceId=?';
				$types="ssi";
				$params=array($offences[$x],$descrs[$x],$id);
				//echo('<br>'.implode(",",$params));
				$result=query($types,$params,$query);
			}
			if($deletes[$x] == 'true'){
				$query="select count(OffenceId) as OffenceId from persondisciplinary where OffenceId=?";
				$types="i";
				$params=array($id);
				$result=query($types,$params,$query);
				while($row=$result->fetch_assoc()){
					$offenceid=$row['OffenceId'];
				}mysqli_free_result($result);
				if($offenceid<1){
					$query='delete from offences where OffenceId=?';
					$types="i";
					$params=array($id);
					$result=query($types,$params,$query);
				}else{
					$query="select Offence from offences where OffenceId=?";
					$types="i";
					$params=array($id);
					$result=query($types,$params,$query);
					while($row=$result->fetch_assoc()){
						$name=$row['Offence'];
					}mysqli_free_result($result);
					$reply.='<h4 style="color:red;font-height:0.8em;">
								The offence \"'.$name.'\" cannot be deleted, there are perssonel linked to this offence.<br>
								Please remove all dependancies first before attempting to delete.
							 </h4>';
				}
			}
		}
	}
	$query='select OffenceId,Offence,Description 
			  from offences 
			 where OffenceId>?';
	$types="i";
	$params=array(0);
	$result=query($types,$params,$query);
	
		$reply='	<form>
						<table class="alternate" id="sysmainoffencestable" style="width:400px;border:1;margin:0 auto;">
							<thead>
								<tr style="font-weight:bold;">
									<th colspan="4" style="text-align:center;">
										<div>
											<span class="folder-button" onclick="folders(\'sysmainoffencesfolder\',\'addoffencesbutton\');">Offence List</span>
											<button id="addoffencesbutton" class="folder-content" style="float:right;width:50px;" type="button" onclick="addoffence();">Add</button>
										</div>
									</th>
								</tr>
							</thead>
							<tbody id="sysmainoffencesfolder" class="folder-content">
								<tr>
									<td style="text-align:center;font-weight:bold;">Delete</td>
									<td style="text-align:center;font-weight:bold;">Offence</td>
									<td style="text-align:center;font-weight:bold;">Descrseviation</td>
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
					</form>';
	
	echo($reply);
?>
