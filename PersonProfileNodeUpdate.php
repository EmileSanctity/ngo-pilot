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
	$bdays=explode(",",str_clean($_GET['bdays']));
	
	foreach($ids as $x => $id){
		if($bdays[$x]===''){
			$bdays[$x]=NULL;
		}
		if($id=='0' && $deletes[$x] == 'false'){
			$query='insert into personnodes(NodeId,PersonId,Name,BirthDate)values(null,?,?,?)';
			$types="iss";
			$params=array($personid,$names[$x],$bdays[$x]);
			//echo('<br>'.implode(",",$params));
			$result=query($types,$params,$query);
		}else{
			if($deletes[$x] == 'false'){
				$query='update personnodes
						   set Name=?,
							   BirthDate=?
						 where NodeId=? and PersonId=?';
				$types="ssii";
				$params=array($names[$x],$bdays[$x],$ids[$x],$personid);
				//echo('<br>'.implode(",",$params));
				$result=query($types,$params,$query);
			}
			if($deletes[$x] == 'true'){
				$query='delete from personnodes where NodeId=? and PersonId=?';
				$types="ii";
				$params=array($ids[$x],$personid);
				//echo('<br>'.implode(",",$params));
				$result=query($types,$params,$query);
			}
		}
	}
	$query='select NodeId,
				   Name,Date(BirthDate) as BirthDate
			  from personnodes 
			 where PersonId=?';
	$types="i";
	$params=array($personid);
	$result=query($types,$params,$query);
	$reply='<table class="alternate" width="1200px" id="nodestable">
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
					<tr style="font-weight:bold;" >
						<td width="100px" style="left:25%;border:1px solid #a6a6a6;text-align:center;vertical-align:middle;">Delete</td>
						<td width="350px" style="border:1px solid #a6a6a6;text-align:center;vertical-align:middle;">Name</td>
						<td width="100%" style="border:1px solid #a6a6a6;text-align:left;vertical-align:middle;"><span style="padding-left:30px;text-align:left;">Birth Date</span></td>
					</tr>';
    while($row=$result->fetch_assoc()){
	$reply.='		<tr>
						<td style="text-align:center;">
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
	$reply.='		<tr>
						<td colspan="6" style="text-align:center;">
							<button type="button" onclick="nodeupdate();">Update</button>
						</td>
					</tr>
				</tbody>
			</table>';
	
	echo($reply);
?>
