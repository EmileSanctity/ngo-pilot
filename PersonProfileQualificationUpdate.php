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
	$names=explode(",",str_clean($_GET['names']));
	
	foreach($ids as $x => $id){
		if($id=='0' && $deletes[$x] == 'false'){
			$query='insert into personqualifications(QualId,PersonId,Qualification)values(null,?,?)';
			$types="is";
			$params=array($personid,$names[$x]);
			//echo('<br>'.implode(",",$params));
			$result=query($types,$params,$query);
		}else{
			if($deletes[$x] == 'false'){
				$query='update personqualifications
						   set Qualification=?
						 where QualId=? and PersonId=?';
				$types="sii";
				$params=array($names[$x],$ids[$x],$personid);
				//echo('<br>'.implode(",",$params));
				$result=query($types,$params,$query);
			}
			if($deletes[$x] == 'true'){
				$query='delete from personqualifications where QualId=? and PersonId=?';
				$types="ii";
				$params=array($ids[$x],$personid);
				//echo('<br>'.implode(",",$params));
				$result=query($types,$params,$query);
			}
		}
	}
	$query='select QualId,Qualification
			  from personqualifications 
			 where PersonId=?';
	$types="i";
	$params=array($personid);
	$result=query($types,$params,$query);
	$reply='<table class="alternate" width="1200px" id="educationtable">
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
						<td width="100px" style="left:25%;border:1px solid #a6a6a6;text-align:center;vertical-align:middle;">Delete</td>
						<td width="100%" style="border:1px solid #a6a6a6;text-align:left;vertical-align:middle;"><span style="padding-left:30px;text-align:left;">Qualification</span></td>
					</tr>';
    while($row=$result->fetch_assoc()){
	$reply.='		<tr>
						<td style="text-align:center;">
							<input type="checkbox" name="qualdelete" />
						</td>
						<td style="text-align:center;width:100%;">
							<input type="hidden" id="qualid" name="qualid" value="'.valscramble($row['QualId']).'" />
							<textarea name="qualification" style="resize:vertical;min-height:60px;width:1137px;">'.$row['Qualification'].'</textarea>
						</td>
					</tr>';
	}
	$reply.='		<tr>
						<td colspan="6" style="text-align:center;">
							<button type="button" onclick="qualificationupdate();">Update</button>
						</td>
					</tr>
				</tbody>
			</table>';
	
	echo($reply);
?>
