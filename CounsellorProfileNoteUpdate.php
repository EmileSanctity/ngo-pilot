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
	$personid=str_clean($_GET['personid']);
	$title=str_clean($_GET['title']);
	$note=str_clean($_GET['note']);
	$counsid=str_clean($_GET['counsid']);
	//var_dump($_GET);

//Variables
	$reply='';

//Check security access
	if($secid>2){
		header('Location:General.php?'.idscramble('userid').'='.valscramble($userid).
			'&'.idscramble('navid').'='.valscramble(0).
			'&'.idscramble('secid').'='.valscramble($secid).' ');
	}
	$query='select SecId from secusers where UserId=?';
	$types="i";
	$params=array($userid);
	$result=query($types,$params,$query);
	while($row=$result->fetch_assoc()){
		$secid=$row['SecId'];
	}mysqli_free_result($result);
	if($secid>2){
		header('Location:General.php?'.idscramble('userid').'='.valscramble($userid).
			'&'.idscramble('navid').'='.valscramble(0).
			'&'.idscramble('secid').'='.valscramble($secid).' ');
	}
//Counsellor Add Note
	$query='update counsellor set Title=?, Note=?, CaptureDate=now() where PersonId=? and UserId=? and CounsId=?';
	$types="ssiii";
	$params=array($title,$note,$personid,$userid,$counsid);
	$result=query($types,$params,$query);
	
//Counsellor form 

	$reply.='	<table style="border:1px solid #cccccc;width:800px;margin:0 auto;padding:0 0;">
					<tr>
						<td style="text-align:center;">
							<form>
								<div id="addcounsellornote" style="clear:both;" >
									<table class="alternate"  width="800px" style="margin: 0 auto;" id="addcounsellornoteform">
										<thead>
										<tr style="font-weight:bold;">
											<th colspan="4" style="text-align:center;">
												<div>
													<span class="folder-button" onclick="folders(\'addcounsellornotefolder\');">Counsellor Note</span>
												</div>
											</th>
										</tr>
										<thead>
										<tbody id="addcounsellornotefolder" class="folder-content">
										<tr style="text-align:center;">
											<td style="text-align:center;" >
												<input style="text-align:center;" placeholder="Title" size="110" type="text" id="counsellortitle" style="padding:5px auto;"/>
											</td>
										</tr>
										<tr>
											<td style="text-align:center;">
												<textarea placeholder="Note" style="resize:vertical;min-height:60px;width:798px;" id="counsellornote"></textarea>
											</td>
										</tr>
										<tr>
											<td colspan="2" style="text-align:center;">
												<button type="button" onclick="addcounsellornote();">Add</button>
											</td>
										</tr>
										</tbody>
									</table>
								</div>
							</form>
						</td>
					</tr>';
	echo($reply);
?>