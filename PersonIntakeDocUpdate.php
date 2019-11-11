<?php
require_once('vmscFunctions.php');

//SysParam & Auto Logout & Logging
$userid=valunscramble($_POST[idunscramble('userid')]);
$secid=valunscramble($_POST[idunscramble('secid')]);
timer(20,$userid);

//Sys logs
$array=array('user',$userid);
$tables=array('');
logs($userid,'l','PersonIntakeDocUpdate',$array,$tables);

//App Params
$personid=str_clean(valunscramble($_POST[idunscramble('personid')]));
$intakeid=str_clean(valunscramble($_POST['intakeid']));
$response='';
$reply='';

//echo('<br>UserId: '.$userid.'<br>SecId: '.$secid.'<br>PersonId: '.$personid);
//var_dump($_POST);
//var_dump($_FILES['file']);

$response=upload($userid,$personid,'PersonIntakeDocUpdate.php','intake',0,0,$intakeid);
$query='select P.IntakeId,P.Ext,P.UploadedOn,P.Name, concat(P.Name,".",P.Ext) as NoteName
          from personintakedocs P
          join (select max(IntakeId) as IntakeId, PersonId
                  from personintakedocs
                 where PersonId=?
                 group by PersonId) X
            on P.IntakeId=X.IntakeId
           and P.PersonId=?';
$types="ii";
$params=array($personid,$personid);
$result=query($types,$params,$query);
while($row=$result->fetch_assoc()){
    $ext=$row['Ext'];
    $note=$row['NoteName'];
    $intakeid=$row['IntakeId'];
}mysqli_free_result($result);
$reply='	<a href="include/personintakedocs/'.$intakeid.'.'.$ext.'" />'.$note.'</a>
			&nbsp;
			<label style="clear:left;">'.$response.'</label>
			<input style="clear:left;" id="disciplinarydoc'.valscramble($intakeid).'" type="file" class="fileinput" name="file">
			<button type="button" onclick="persondisciplinedoc('.valscramble($intakeid).');">Upload</button>
			<input type="hidden" id="recentdiscid" value="'.$intakeid.'" />';
			
	echo($reply);
?>