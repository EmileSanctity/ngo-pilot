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
	$deletes=explode(",",str_clean($_GET['deletes']));
	$ids=explode(",",str_clean($_GET['ids']));
	$names=explode(",",str_clean($_GET['names']));
	$abbrs=explode(",",str_clean($_GET['abbrs']));
	
	foreach($ids as $x => $id){
		if($id=='0' && $deletes[$x] == 'false'){
			$query='insert into addictions(AddictionId,Name,Abbr)values(null,?,?)';
			$types="ss";
			$params=array($names[$x],$abbrs[$x]);
			//echo('<br>'.implode(",",$params));
			$result=query($types,$params,$query);
		}else{
			if($deletes[$x] == 'false'){
				$query='update addictions
						   set Name=?,
							   Abbr=?
						 where AddictionId=?';
				$types="ssi";
				$params=array($names[$x],$abbrs[$x],$id);
				//echo('<br>'.implode(",",$params));
				$result=query($types,$params,$query);
			}
			if($deletes[$x] == 'true'){
				$query="select count(AddictionId) as AddictionId from personaddictions where AddictionId=?";
				$types="i";
				$params=array($id);
				$result=query($types,$params,$query);
				while($row=$result->fetch_assoc()){
					$addictionid=$row['AddictionId'];
				}mysqli_free_result($result);
				if($addictionid<1){
					$query='delete from addictions where AddictionId=?';
					$types="i";
					$params=array($id);
					$result=query($types,$params,$query);
				}else{
					$query="select Name from addictions where AddictionId=?";
					$types="i";
					$params=array($id);
					$result=query($types,$params,$query);
					while($row=$result->fetch_assoc()){
						$name=$row['Name'];
					}mysqli_free_result($result);
					$reply.='<h4 style="color:red;font-height:0.8em;">
								The addiction \"'.$name.'\" cannot be deleted, there are perssonel linked to this addiction.<br>
								Please remove all dependancies first before attempting to delete.
							 </h4>';
				}
			}
		}
	}
	$query='select AddictionId,Name,Abbr 
			  from addictions 
			 where AddictionId>?';
	$types="i";
	$params=array(0);
	$result=query($types,$params,$query);
	
		$reply='	<form>
						<table class="alternate" id="sysmainaddictionstable" style="width:400px;border:1;margin:0 auto;">
							<thead>
								<tr style="font-weight:bold;">
									<th colspan="4" style="text-align:center;">
										<div>
											<span class="folder-button" onclick="folders(\'sysmainaddictionsfolder\',\'addaddictionbutton\');">Addiction List</span>
											<button id="addaddictionbutton" class="folder-content" style="float:right;width:50px;" type="button" onclick="addaddiction();">Add</button>
										</div>
									</th>
								</tr>
							</thead>
							<tbody id="sysmainaddictionsfolder" class="folder-content">
								<tr>
									<td style="text-align:center;font-weight:bold;">Delete</td>
									<td style="text-align:center;font-weight:bold;">Name</td>
									<td style="text-align:center;font-weight:bold;">Abbreviation</td>
								</tr>';
	while($row=$result->fetch_assoc()){
		$reply.='				<tr>
									<td style=text-align:center;">
										<input type="checkbox" id="deleteaddiction" name="deleteaddiction" />
									</td>
									<td>
										<input type="hidden" name="addictionid" value="'.$row['AddictionId'].'" />
										<input type="text" name="addictionname" value="'.$row['Name'].'" />
									</td>
									<td>
										<input type="text" name="addictionabbr" value="'.$row['Abbr'].'" />
									</td>
								</tr>';
	}							
	$reply.='					<tr>
									<td colspan="3" style="text-align:center;"><button type="button" onclick="sysaddictionupdate();">Update</button></td>
								</tr>
							</tbody>
						</table>
					</form>';
	
	echo($reply);
?>
