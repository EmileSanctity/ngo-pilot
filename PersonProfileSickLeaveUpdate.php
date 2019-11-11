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
	//var_dump($_GET);
	
	$reply='';
	$personid=str_clean(valunscramble($_GET[idunscramble('personid')]));
	$deletes=explode(",",str_clean($_GET['deletes']));
	$ids=explode(",",str_clean($_GET['ids']));
	$starts=explode(",",str_clean($_GET['starts']));
	$finishes=explode(",",str_clean($_GET['finishes']));
	$comments=explode(",",str_clean($_GET['comments']));
	
	/*
	echo(	'<br>'.$personid.
			'<br>'.implode(",",$deletes).
			'<br>'.implode(",",$ids).
			'<br>'.implode(",",$starts).
			'<br>'.implode(",",$finishes).
			'<br>'.implode(",",$comments).
			'<br>');
	*/
	foreach($ids as $x => $id){
		//echo('<br>'.$id.'<br>');
				if($starts[$x]===''){
		$starts[$x]=NULL;
		}
		if($finishes[$x]===''){
			$finishes[$x]=NULL;
		}
		if($deletes[$x] == 'false'){
			//echo('false');
			$query='update personsickleave
					   set StartDate=?,
						   FinishDate=?,
						   Comment=?
					 where SickId=?
					   and PersonId=?';
			$types="sssii";
			$params=array($starts[$x],$finishes[$x],$comments[$x],$id,$personid);
			//echo(implode(",",$params));
			$result=query($types,$params,$query);
		}
		if($deletes[$x] == 'true'){
			//echo('true');
			$query='delete 
					  from personsickleave 
					 where SickId=? 
					   and PersonId=?';
			$types="ii";
			$params=array($ids[$x],$personid);
			$result=query($types,$params,$query);
			
			$query='select count(*) as Cnt
					  from doctorsnotes 
					 where SickId=?';
			$types="i";
			$params=array($ids[$x]);
			$result=query($types,$params,$query);
			while($row=$result->fetch_assoc()){
				$cnt=$row['Cnt'];
			}mysqli_free_result($result);
			
			if($cnt>0){
				$query='delete from doctorsnotes where SickId=?';
				$types="i";
				$params=array($ids[$x]);
				$result=query($types,$params,$query);
			}
		}
	}
	$query=PersonProfileSql::get_sick_leave();
	$types="ii";
	$params=array($personid,$personid);
	$result=query($types,$params,$query);

	$total=0;
	if($row=$result->fetch_assoc()){
		$total=$row['Total'];
		mysqli_data_seek($result,0);
	}
    $reply.='						<table class="alternate" width="1200px"  id="sickleavetable">
										<thead>
										<tr style="font-weight:bold;">
											<th colspan="6" style="text-align:center;">
												<div>
													<span class="folder-button" onclick="folders(\'sickleavefolder\',\'sickleavebutton\');">Sick Leave</span>
													<button id="sickleavebutton" class="folder-content" style="float:right;width:50px;" type="button" onclick="addsickleave();">Add</button>
												</div>
											</th>
										</tr>
										<thead>
										<tbody id="sickleavefolder" class="folder-content">
										<tr style="font-weight:bold;">
											<td style="border:1px solid #a6a6a6;text-align:center;vertical-align:middle;">Delete</td>
											<td style="border:1px solid #a6a6a6;text-align:center;vertical-align:middle;">Start Date</td>
											<td style="border:1px solid #a6a6a6;text-align:center;vertical-align:middle;">Finish Date</td>
											<td style="border:1px solid #a6a6a6;text-align:center;vertical-align:middle;">Days ('.$total.')</td>
											<td style="border:1px solid #a6a6a6;text-align:center;vertical-align:middle;">Comment</td>
											<td style="border:1px solid #a6a6a6;text-align:left;vertical-align:middle;"><span style="padding-left:30px;text-align:left;">Document</span></td>
										</tr>';
	while($row=$result->fetch_assoc()){
		$reply.='						<tr>
											<td  style="text-align:center;width:50px;">
												<input type="checkbox" name="psickdelete" />
											</td>
											<td>
												<input type="hidden" id="psickid" name="psickid" value="'.valscramble($row['SickId']).'" />
												<input type="date" id="psickstart" name="psickstart" value="'.$row['StartDate'].'" />
											</td>
											<td>
												<input type="date" id="psickfinish" name="psickfinish" value="'.$row['FinishDate'].'" />
											</td>
											<td style="text-align:center;width:60px;">'.$row['Days'].'</td>
											<td>
												<textarea id="psickcomment" name="psickcomment" style="resize:vertical;min-height:60px;width:498px;">'.$row['Comment'].'</textarea>
											</td>
											<td>
												<div id="persondoctornote'.valscramble($row['SickId']).'">';
		if($row['NoteName']=='No attachment'){
			$reply.='				'.$row['NoteName'].' ';
		}else{
			$reply.='								<a href="include/doctorsnotes/'.$row['NoteId'].'.'.$row['Ext'].'" >'.$row['NoteName'].'</a>';
		}
		$reply.='									<br>
													<input style="clear:left;" id="psicknote'.valscramble($row['SickId']).'" type="file" class="fileinput" name="file">
													<button type="button" onclick="getdoctorsnote('.valscramble($row['SickId']).');">Upload</button>
												</div>
											</td>
										</tr>';
	}
	$reply.='							<tr>
											<td colspan="6" style="text-align:center;">
												<button type="button" onclick="sickleaveupdate();">Update</button>
											</td>
										</tr>
										</tbody>
									</table>';
	echo($reply);
?>