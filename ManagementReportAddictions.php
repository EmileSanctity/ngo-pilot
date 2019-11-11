<?php
	require_once('vmscFunctions.php');
	require_once('vmscManagementSQL.php');
//SysParam & Auto Logout & Logging
	$userid=str_clean(valunscramble($_GET[idunscramble('userid')]));
	$secid=str_clean(valunscramble($_GET[idunscramble('secid')]));
	timer(20,$userid);
//Sys logs
	$array=array('user',$userid);
	$tables=array('');
	logs($userid,'l','ManagementReportAddictions',$array,$tables);
//App Params	
	//var_dump($_GET);
	$reply='';
	
//Form
	$reply.='	<table style="border:1px solid #cccccc;width:800px;margin:0 auto;padding:0 0;">
					<tr>
						<td style="text-align:center;">
							<form>
								<div id="reportaddictions" style="clear:both;" >
									<table class="alternate"  width="800px" style="margin: 0 auto;" id="reportaddictionsform">
										<thead>
										<tr style="font-weight:bold;">
											<th colspan="4" style="text-align:center;">
												<div>
													<span class="folder-button" onclick="folders(\'reportaddictionsfolder\');">Addictions Report</span>
												</div>
											</th>
										</tr>
										<thead>
										<tbody id="reportaddictionsfolder" class="folder-content">
										<tr style="text-align:center;">
											<td style="text-align:right;width:400px;" >
												List of drugs
											</td>
											<td style="text-align:left;width:400px;" >';
	$query='select AddictionId,Name,Abbr 
			  from addictions 
			 where AddictionId > ?';
	$types='i';
	$params=array(0);
	$result=query($types,$params,$query);
	$reply.='									<select id="addictionslist" multiple="multiple">';
	while($row=$result->fetch_assoc()){
		$reply.='									<option value="'.$row['AddictionId'].'" >'.$row['Abbr'].' '.$row['Name'].'</option>';
	}
	$reply.='									</select>
											</td>
										</tr>
										<tr>
											<td style="text-align:right;">
												From Date:
											</td>
											<td>
												<input type="date" id="addictionsfromdate"/>
											</td>
										</tr>
										<tr>
											<td style="text-align:right">
												To Date:
											</td>
											<td>
												<input type="date" id="addictionstodate" />
											</td>
										</tr>
										<tr>
											<td colspan="2" style="text-align:center;">
												<button type="button" onclick="reportaddictions();">Draw</button>
											</td>
										</tr>
										</tbody>
									</table>';
	
	$reply.='					</div>
							</form>
						</td>
					</tr>
					<tr>
						<td style="text-align:center;">
							<div id="addictionreportresult"></div>
						</td>
					</tr>
				</table>';
	echo($reply);
?>