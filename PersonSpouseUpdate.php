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
	$personid=str_clean(valunscramble($_GET['personid']));
	$deletes=explode(",",str_clean($_GET['deletes']));
	$ids=explode(",",str_clean($_GET['ids']));
	$names=explode(",",str_clean($_GET['names']));
	$idnos=explode(",",str_clean($_GET['idnos']));
	$onsystems=explode(",",str_clean($_GET['onsystems']));
	
	foreach($ids as $x => $id){
		if($id=='0' && $deletes[$x] == 'false'){
			$query='insert into personspouse(SpouseId,PersonId,Name,IDNo,OnSystem)values(null,?,?,?,?);';
			$types="issi";
			$params=array($personid,$names[$x],$idnos[$x],$onsystems[$x]);
			$result=query($types,$params,$query);
			//echo('<br>'.$query.'<br>'.implode(",",$params).'<br>');
		}else{
			if($deletes[$x] == 'false'){
				$query='update personspouse
						   set Name=?,
							   IDNo=?,
							   OnSystem=?
						 where SpouseId=? 
						   and PersonId=?';
				$types='ssiii';
				$params=array($names[$x],$idnos[$x],$onsystems[$x],$ids[$x],$personid);
				$result=query($types,$params,$query);
			}
			if($deletes[$x] == 'true'){
				$query='delete from personspouse where SpouseId=? and PersonId=?';
				$types="ii";
				$params=array($ids[$x],$personid);
				$result=query($types,$params,$query);
			}
		}
	}
	
	$query=PersonProfileSql::get_spouse();
	$types="i";
	$params=array($personid);
	$result=query($types,$params,$query);
	
	$reply.='	<table class="alternate" width="1200px" id="spousetable">
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
							<td width="20px" style="border:1px solid #a6a6a6;text-align:center;vertical-align:middle;">Delete</td>
							<td width="195px" style="border:1px solid #a6a6a6;text-align:center;vertical-align:middle;">Name</td>
							<td width="195px" style="border:1px solid #a6a6a6;text-align:center;vertical-align:middle;">Age</td>
							<td width="195px" style="border:1px solid #a6a6a6;text-align:center;vertical-align:middle;">ID No</td>
							<td width="100%" style="border:1px solid #a6a6a6;text-align:left;vertical-align:middle;"><span style="padding-left:30px;text-align:left;">On System</span></td>
						</tr>';
    while($row=$result->fetch_assoc()){
	$reply.='			<tr>
							<td style="text-align:center;">
								<input type="checkbox" name="spousedelete" />
							</td>
							<td>
								<input type="hidden" id="spouseid" name="spouseid" value="'.valscramble($row['SpouseId']).'" />
								<input type="text" id="spousename" name="spousename" value="'.$row['Name'].'" />
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
	$reply.='			<tr>
							<td colspan="6" style="text-align:center;">
								<button type="button" onclick="spouseupdate();">Update</button>
							</td>
						</tr>
					</tbody>
				</table>';
	echo($reply);
?>
