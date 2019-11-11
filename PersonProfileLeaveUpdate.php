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
	logs($userid,'l','PersonSearchList',$array,$tables);

//App Params
	//var_dump($_GET);
	$reply='';
	$personid=str_clean(valunscramble($_GET['personid']));
	$deletes=explode(",",str_clean($_GET['deletes']));
	$leaveids=explode(",",str_clean($_GET['ids']));
	$starts=explode(",",str_clean($_GET['entries']));
	$finishes=explode(",",str_clean($_GET['exits']));
	$comments=explode(",",str_clean($_GET['comments']));
	
	//echo(	'<br>'.implode(",",$leaveids).
	//		'<br>'.implode(",",$starts).
	//		'<br>'.implode(",",$finishes).
	//		'<br>'.implode(",",$comments).
	//		'<br>');
	
	foreach($leaveids as $cnt => $leaveid){
		if($starts[$cnt]===''){
		$starts[$cnt]=NULL;
		}
		if($finishes[$cnt]===''){
			$finishes[$cnt]=NULL;
		}
		//echo('<br>Inside Loop: '.$cnt);
		
		if($leaveid == '0' && $deletes[$cnt] == 'false'){
			//echo('<br>Inside 1');
			//echo('<br>'.$cnt.' '.$leaveid.' '.$starts[$cnt].' '.$finishes[$cnt].' '.$comments[$cnt].' '.$deletes[$cnt]);
			$query='insert into personleave(LeaveId, StartDate, FinishDate, Comment, PersonId)
					values(null,?,?,?,?)';
			$types="sssi";
			$params=array($starts[$cnt],$finishes[$cnt],$comments[$cnt],$personid);
			$result=query($types,$params,$query);
		}else{
			if($deletes[$cnt] == 'false'){
				//echo('<br>Inside 2');
				//echo('<br>'.$cnt.' '.$leaveid.' '.$starts[$cnt].' '.$finishes[$cnt].' '.$comments[$cnt].' '.$deletes[$cnt]);
				$query='update personleave
						   set StartDate=?,
							   FinishDate=?,
							   Comment=?
						 where LeaveId=?
						   and PersonId=?';
				$types='sssii';
				$params=array($starts[$cnt],$finishes[$cnt],$comments[$cnt],$leaveid,$personid);
				$result=query($types,$params,$query);
			}
			if($deletes[$cnt] == 'true'){
				//echo('<br>Inside 3');
				//echo('<br>'.$cnt.' '.$leaveid.' '.$starts[$cnt].' '.$finishes[$cnt].' '.$comments[$cnt].' '.$deletes[$cnt]);
				$query='delete from personleave where LeaveId=?';
				$types='i';
				$params=array($leaveid);
				$result=query($types,$params,$query);
			}
		}
		
		
	}
	
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

	$reply.='	<table class="alternate"  width="1200px" style="margin: 0 auto;" id="leaveform">
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
		}mysqli_free_result($result);
		$reply.='							<tr>
												<td colspan="5" style="text-align:center;">
													<button type="button" onclick="leaveupdate();">Update</button>
												</td>
											</tr>
											</tbody>
										</table>';

	
	echo($reply);
?>


















