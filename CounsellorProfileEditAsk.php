<?php
	require_once('vmscFunctions.php');
	require_once('vmscPersonProfileSql.php');

//SysParam & Auto Logout & Logging
	$userid=str_clean(valunscramble($_GET[idunscramble('userid')]));
	$secid=str_clean(valunscramble($_GET[idunscramble('secid')]));
	//timer(20,$userid);
//Sys logs
	$array=array('user',$userid);
	$tables=array('');
	logs($userid,'l','CounsselorProfileEditAsk',$array,$tables);

//App Params
	//var_dump($_GET);
	$counsid=$_GET['counsid'];
//echo('counsid: '.$counsid);

//Variables
	$reply='';

//Check security access
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
//Make keys :P
	
	$hash=sodium_bin2hex(sodium_crypto_box_publickey(sodium_crypto_box_keypair()));

	//echo($hash);
	$query='insert into counsellorkeys(CounsKey,CaptureDate,UserId,Answered)values(?,now(),?,?)';
	$types='sii';
	$params=array($hash,$userid,0);
	$result=query($types,$params,$query);
	//select * from counsellorkeys where DateDiff(now(),CaptureDate)=0 and TIME_TO_SEC(now())-TIME_TO_SEC(CaptureDate)<600
	
	$keyid=0;
	$query='select max(KeyId) as KeyId from counsellorkeys where UserId=? and CounsKey=? and Answered=0';
	$types="is";
	$params=array($userid,$hash);
	$result=query($types,$params,$query);
	while($row=$result->fetch_assoc()){
		$keyid=$row['KeyId'];
	}mysqli_free_result($result);
//Send keys	
	$reply.='<h4>A key has been emailed to the counsellor.</h4>';
	//$reply.='<textarea>'.$hash.'</textarea>';
	keymail($userid,$hash);
//Counsellor Add Key
	$reply.='	<table style="border:1px solid #cccccc;width:800px;margin:0 auto;padding:0 0;">
					<tr>
						<td style="text-align:center;">
							<form>
								<div id="keycounsellornote" style="clear:both;" >
									<table class="alternate"  width="800px" style="margin:0 auto;" id="keycounsellornoteform">
										<thead>
											<tr style="font-weight:bold;">
												<th colspan="4" style="text-align:center;">
													<div>
														<span class="folder-button" onclick="folders(\'keycounsellornotefolder\');">Counsellor Key</span>
													</div>
												</th>
											</tr>
										</thead>
										<tbody id="keycounsellornotefolder" class="folder-content">
											<tr style="text-align:center;">
												<td style="text-align:center;" >
													<input type="hidden" id="counsid" value="'.$counsid.'" />
													<input type="hidden" id="keyid" value="'.$keyid.'" />
													<textarea style="resize:vertical;min-height:60px;width:798px;" id="counsellorkey"></textarea>
												</td>
											</tr>
											<tr>
												<td colspan="2" style="text-align:center;">
													<button type="button" onclick="counsellornotecheckkey();">Submit</button>
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