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
	
	$reply='';
	$personid=str_clean(valunscramble($_GET[idunscramble('personid')]));
	$deletes=explode(",",str_clean($_GET['deletes']));
	$ids=explode(",",str_clean($_GET['ids']));
	$offences=explode(",",str_clean($_GET['offences']));
	$offencedates=explode(",",str_clean($_GET['offencedates']));
	$capturedates=explode(",",str_clean($_GET['capturedates']));
	$disciplines=explode(",",str_clean($_GET['disciplines']));
	$restarts=explode(",",str_clean($_GET['restarts']));
	
	
	//echo(	'<br>personid '.$personid.
	//		'<br>deletes '.implode(",",$deletes).
	//		'<br>ids '.implode(",",$ids).
	//		'<br>offences '.implode(",",$offences).
	//		'<br>offencedates '.implode(",",$offencedates).
	//		'<br>capturedates '.implode(",",$capturedates).
	//		'<br>disciplines '.implode(",",$disciplines).
	//		'<br>restarts '.implode(",",$restarts).
	//		'<br>');
	
	
	foreach($ids as $x => $id){
		//echo('<br>'.$id.'<br>');
		if($offencedates[$x]===''){
			$offencedates[$x]=NULL;
		}
		if($capturedates[$x]===''){
			$capturedates[$x]=NULL;
		}
		if($deletes[$x] == 'false'){
			//echo('false');
			$query='update persondisciplinary
					   set CaptureDate=case when ?=null then now() else ? end,
						   Discipline=?,
						   Restart=?,
						   OffenceId=?,
						   Offencedate=?
					 where PersonId=?
					   and DiscId=?';
			$types="sssiisii";
			$params=array($capturedates[$x],$capturedates[$x],$disciplines[$x],$restarts[$x],$offences[$x],$offencedates[$x],$personid,$id);
			//echo(implode(",",$params));
			$result=query($types,$params,$query);
		}
		if($deletes[$x] == 'true'){
			//echo('true');
			$query='delete 
					  from persondisciplinary 
					 where DiscId=? 
					   and PersonId=?';
			$types="ii";
			$params=array($ids[$x],$personid);
			$result=query($types,$params,$query);
			
			$query='select count(*) as Cnt
					  from persondocs 
					 where DiscId=?';
			$types="i";
			$params=array($ids[$x]);
			$result=query($types,$params,$query);
			while($row=$result->fetch_assoc()){
				$cnt=$row['Cnt'];
			}mysqli_free_result($result);
			
			if($cnt>0){
				$query='delete from persondocs where DiscId=?';
				$types="i";
				$params=array($ids[$x]);
				$result=query($types,$params,$query);
			}
		}
	}
	$query=PersonProfileSql::get_disciplinary();
		$types='i';
		$params=array($personid);
		$result=query($types,$params,$query);
		
	$reply.='	<table class="alternate" style="width:1200px;border:1;margin:0 auto;" id="disciplinarytable">
					<thead>
						<tr style="font-weight:bold;">
							<th colspan="6" style="text-align:center;">
								<div>
									<span class="folder-button" onclick="folders(\'disciplinaryfolder\',\'disciplinarybutton\');">Disciplinary</span>
									<button id="disciplinarybutton" class="folder-content" style="float:right;width:50px;" type="button" onclick="adddisciplinary();">Add</button>
								</div>
							</th>
						</tr>
					<thead>
					<tbody id="disciplinaryfolder" class="folder-content">
						<tr style="font-weight:bold;" >
							<td width="50px" style="left:25%;border:1px solid #a6a6a6;text-align:center;vertical-align:middle;">Delete</td>
							<td width="" style="border:1px solid #a6a6a6;text-align:center;vertical-align:middle;">Offence</td>
							<td width="" style="border:1px solid #a6a6a6;text-align:center;vertical-align:middle;">Offence Date</td>
							<td width="" style="border:1px solid #a6a6a6;text-align:center;vertical-align:middle;">Captured On</td>
							<td width="" style="border:1px solid #a6a6a6;text-align:center;vertical-align:middle;">Discipline</td>
							<td width="" style="border:1px solid #a6a6a6;text-align:center;vertical-align:middle;">Restart</td>
							<td width="" style="border:1px solid #a6a6a6;text-align:center;vertical-align:middle;"><span style="padding-left:30px;text-align:left;">Document</span></td>
						</tr>';
	while($row=$result->fetch_assoc()){
		$discid=$row['DiscId'];
		$offenceid=$row['OffenceId'];
		$offencedate=$row['OffenceDate'];
		$capturedate=$row['CaptureDate'];
		$discipline=$row['Discipline'];
		$restart=$row['Restart'];
		$docid=$row['DocId'];
		$ext=$row['Ext'];
		$name=$row['Name'];
		$notename=$row['NoteName'];
		$reply.='		<tr>
							<td style="text-align:center;">
								<input type="checkbox" name="disciplinarydelete" />
							</td>
							<td style="text-align:center;">
								<input type="hidden" id="discid" name="discid" value="'.valscramble($discid).'" />
								<select name="offence">';
		$query='select OffenceId as Id,Offence as Of from offences where OffenceId>?';
		$types='i';
		$params=array(0);
		$res=query($types,$params,$query);
		while($row=$res->fetch_assoc()){
			$reply.='			<option value='.$row['Id'].' ';if($row['Id'] == $offenceid){$reply.=' selected';}$reply.=' >'.$row['Of'].'</option>';
		}mysqli_free_result($res);
		$reply.='				</select>
							</td>
							<td>
								<input type="date" id="disciplineoffencedate" name="disciplineoffencedate" value="'.$offencedate.'" />
							</td>
							<td>
								<input type="date" id="disciplinecapturedate" name="disciplinecapturedate" value="'.$capturedate.'" />
							</td>
							<td>
								<textarea name="disciplinetext" id="disciplinetext" style="resize:vertical;min-height:60px;width:298px;">'.$discipline.'</textarea>
							</td>
							<td>
								<select name="restart" id="restart">
									<option value="0" ';if($restart==0){$reply.='selected';}$reply.='>No Restart</option>
									<option value="1" ';if($restart==1){$reply.='selected';}$reply.='>Restart</option>
								</select>
							</td>
							<td>
								<div id="disciplinarydocdiv'.valscramble($discid).'">';
		if($row['NoteName']=='No attachment'){
		$reply.='									'.$notename.' ';
		}else{
		$reply.='					<a href="include/persondocs/'.$docid.'.'.$ext.'" >'.$notename.'</a>';
		}
		$reply.='					<br>
									<input style="clear:left;" id="disciplinarydoc'.valscramble($discid).'" type="file" class="fileinput" name="file">
									<button type="button" onclick="persondisciplinedoc('.valscramble($discid).');">Upload</button>
								</div>
							</td>
						</tr>';
		}
		$reply.='		<tr>
							<td colspan="7" style="text-align:center;">
								<button type="button" onclick="persondisciplineupdate();">Update</button>
							</td>
						</tr>
					</tbody>
				</table>';
	
	
	
	echo($reply);
?>