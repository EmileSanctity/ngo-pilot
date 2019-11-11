<?php
	require_once('vmscFunctions.php');

//SysParam & Auto Logout & Logging
	$userid=str_clean(valunscramble($_POST[idunscramble('userid')]));
	$secid=str_clean(valunscramble($_POST[idunscramble('secid')]));
	timer(20,$userid);

//Sys logs
	$array=array('user',$userid);
	$tables=array('');
	logs($userid,'l','PersonSearchList',$array,$tables);

//App Params
	$personid=str_clean(valunscramble($_POST[idunscramble('personid')]));
	$sickid=str_clean($_POST['sickid']);
	$response='';
	$reply='';
	
	//echo('<br>UserId: '.$userid.'<br>SecId: '.$secid.'<br>PersonId: '.$personid);
	//var_dump($_POST);
	//var_dump($_FILES['file']);
	
	$response=upload($userid,$personid,'PersonProfileSickNoteUpdate.php','doc',0,$sickid,0);
	$query='select D.NoteId, 
				   concat(D.Name,".",D.Ext) as NoteName, 
				   D.SickId, 
				   D.Ext
			  from doctorsnotes D
			  join (select max(NoteId) as NoteId,SickId
					  from doctorsnotes
					 where SickId=?) X
			    on D.SickId=X.SickId
			   and D.NoteId=X.NoteId
			 where D.SickId=?';
    $types="ii";
    $params=array($sickid,$sickid);
    $result=query($types,$params,$query);
	while($row=$result->fetch_assoc()){
		$noteid=$row['NoteId'];
		$ext=$row['Ext'];
		$note=$row['NoteName'];
		$sickid=$row['SickId'];
	}mysqli_free_result($result);
	$reply='<a href="include/doctorsnotes/'.$noteid.'.'.$ext.'" />'.$note.'</a>
			<br>
			<label style="clear:left;">'.$response.'</label>
			<input style="clear:left;" id="psicknote'.valscramble($sickid).'" type="file" class="fileinput" name="file">
			<button type="button" onclick="getdoctorsnote('.valscramble($sickid).');">Upload</button>
			<input type="hidden" id="recentsickid" value="'.$sickid.'" />';
	
	
	echo($reply);
?>