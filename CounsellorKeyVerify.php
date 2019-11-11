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
	logs($userid,'l','CounsselorProfileEditAsk',$array,$tables);

//App Params
	//var_dump($_GET);
	$counsid=str_clean(valunscramble($_GET[idunscramble('counsid')]));
	$keyid=str_clean(valunscramble($_GET[idunscramble('keyid')]));
	$key=str_clean(valunscramble($_GET[idunscramble('key')]));
	
//	echo('<br>counsid: '.$counsid.'<br>keyid: '.$keyid.'<br><textarea>key: '.$key.'<textarea>');

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
//Check keys :P
	$dbkey='';
	$title='';
	$note='';
	$capturedate='';
	$query='select CounsKey from counsellorkeys where KeyId=?';
	$types="i";
	$params=array($keyid);
	$result=query($types,$params,$query);
	while($row=$result->fetch_assoc()){
		$dbkey=$row['CounsKey'];
	}mysqli_free_result($result);
	//echo('<br><textarea>'.$dbkey.'</textarea>');
	if(hexdec($dbkey)==hexdec($key)){
		echo('<h4>Your key is correct</h4>');
		$query='select CounsId, UserId, Title, Note, CaptureDate from counsellor where CounsId=?';
		$types="i";
		$params=array($counsid);
		$result=query($types,$params,$query);
		while($row=$result->fetch_assoc()){
			$counsid=$row['CounsId'];
			$title=$row['Title'];
			$note=$row['Note'];
			$capturedate=$row['CaptureDate'];
			$counsellorid=$row['UserId'];
		}mysqli_free_result($result);
		if($counsellorid==$userid){
			$reply.='<table style="border:1px solid #cccccc;width:800px;margin:0 auto;padding:0 0;">
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
													<input type="hidden" id="counsid" value="'.$counsid.'" />
													<input style="text-align:center;" placeholder="Title" size="110" type="text" id="counsellortitle" style="padding:5px auto;" value="'.$title.'" />
												</td>
											</tr>
											<tr>
												<td style="text-align:center;">
													<textarea placeholder="Note" style="resize:vertical;min-height:60px;width:798px;" id="counsellornote">'.$note.'</textarea>
												</td>
											</tr>
											<tr>
												<td colspan="2" style="text-align:center;">
													<button type="button" onclick="updatecounsellornote();">Update</button>
												</td>
											</tr>
											</tbody>
										</table>
									 </div>
								</form>
							</td>
						</tr>';
		}else{
			$reply.='<h4 style="font:red;">You cannot edit another counsellors notes!</h4>';
		}
	}else{
		$reply.='<h4 style="font:red;">Your key is incorrect</h4>';
	}
	
	echo($reply);
?>