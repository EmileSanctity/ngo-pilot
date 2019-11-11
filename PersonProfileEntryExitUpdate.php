<?php
	require_once('vmscFunctions.php');
    require_once('vmscPersonProfileSql.php');
//SysParam & Auto Logout & Logging
	$userid=valunscramble($_GET[idunscramble('userid')]);
	//$secid=valunscramble($_GET[idunscramble('secid')]);
	timer(20,$userid);

//Sys logs
	$array=array('user',$userid);
	$tables=array('');
	logs($userid,'l','PersonSearchList',$array,$tables);

//App Params
	//echo("<pre>");var_dump($_GET);//echo("</pre>");
	$reply='';
	$personid=str_clean(valunscramble($_GET['personid']));
	$deletes=explode(",",str_clean($_GET['deletes']));
	$dateids=explode(",",str_clean($_GET['ids']));
	$entries=explode(",",str_clean($_GET['entries']));
	$exits=explode(",",str_clean($_GET['exits']));
	$comments=explode(",",str_clean($_GET['comments']));
	
	//echo(	'<br>PersonId'.$personid.
	//		'<br>'.implode(",",$dateids).
	//		'<br>'.implode(",",$entries).
	//		'<br>'.implode(",",$exits).
	//		'<br>'.implode(",",$comments).
	//		'<br>');
	
	foreach($dateids as $cnt => $dateid){
		//echo('<br>Inside Loop: '.$cnt);	
		if($entries[$cnt]===''){
			$entries[$cnt]=NULL;
		}
		if($exits[$cnt]===''){
			$exits[$cnt]=NULL;
		}
		if($dateid == '0'){
			//echo('<br>Inside 1');
			//echo('<br>'.$cnt.' '.$dateid.' '.$entries[$cnt].' '.$exits[$cnt].' '.$comments[$cnt].' '.$deletes[$cnt]);
			$query=PersonProfileSql::add_entry_exit();
			$types="sssi";
			$params=array($entries[$cnt],$exits[$cnt],$comments[$cnt],$personid);
			//echo('<br>'.$query.'<br>'.implode(",",$params));
			$result=query($types,$params,$query);
		}
		if($deletes[$cnt] == 'false'){
			//echo('<br>Inside 2');
			//echo('<br>'.$cnt.' '.$dateid.' '.$entries[$cnt].' '.$exits[$cnt].' '.$comments[$cnt].' '.$deletes[$cnt]);
			$query=PersonProfileSql::update_entry_exit();
			$types='sssii';
			$params=array($entries[$cnt],$exits[$cnt],$comments[$cnt],$dateid,$personid);
			$result=query($types,$params,$query);
		}
		if( $deletes[$cnt] == 'true'){
			//echo('<br>Inside 3');
			//echo('<br>'.$cnt.' '.$dateid.' '.$entries[$cnt].' '.$exits[$cnt].' '.$comments[$cnt].' '.$deletes[$cnt]);
			$query=PersonProfileSql::delete_entry_exit();
			$types='i';
			$params=array($dateid);
			$result=query($types,$params,$query);
		}
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
	$reply.='							<table class="alternate"  width="1200px" style="margin: 0 auto;" id="entryexitform">
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
		$reply.='							<tr style="text-align:center;">
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
	$reply.='								<tr>
												<td colspan="5" style="text-align:center;">
													<button type="button" onclick="entryexitupdate();">Update</button>
												</td>
											</tr>
											</tbody>
										</table>';

	mysqli_free_result($result);
	echo($reply);
?>