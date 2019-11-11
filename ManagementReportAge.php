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
	logs($userid,'l','ManagementReportAge',$array,$tables);
//App Params	
	//var_dump($_GET);
	$reply='';
	
//Form
	$reply.='	<table style="border:1px solid #cccccc;width:800px;margin:0 auto;padding:0 0;">
					<tr>
						<td style="text-align:center;">
							<form>
								<div id="reportage" style="clear:both;" >
									<table class="alternate"  width="800px" style="margin: 0 auto;" id="reportageform">
										<thead>
										<tr style="font-weight:bold;">
											<th colspan="4" style="text-align:center;">
												<div>
													<span class="folder-button" onclick="folders(\'reportagefolder\');">Age Report</span>
												</div>
											</th>
										</tr>
										<thead>
										<tbody id="reportagefolder" class="folder-content">
										<tr style="text-align:center;">
											<td style="text-align:right;width:400px;" >
												Completed
											</td>
											<td style="text-align:left;width:400px;" >
												<select id="completestatus">
													<option value="0">All options</option>';
	$query='select CompleteId,Name from completestatus where CompleteId>?';
	$types="i";
	$params=array(0);
	$result=query($types,$params,$query);
	while($row=$result->fetch_assoc()){
		$reply.='<option value="'.$row['CompleteId'].'">'.$row['Name'].'</option>';
	}mysqli_free_result($result);
	$reply.=									'</select>
											</td>
										</tr>
										<tr>
											<td style="text-align:right;">
												From Date:
											</td>
											<td>
												<input type="date" id="fromdate"/>
											</td>
										</tr>
										<tr>
											<td style="text-align:right">
												To Date:
											</td>
											<td>
												<input type="date" id="todate" />
											</td>
										</tr>
										<tr>
											<td colspan="2" style="text-align:center;">
												<button type="button" onclick="reportage();">Draw</button>
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
							<div id="reportageresult"></div>
						</td>
					</tr>
				</table>';
	/*
				<table style="border:1px solid #cccccc;width:800px;margin:0 auto;padding:0 0;"><tr><td style="text-align:center;"><form><div id="reportage" style="clear:both;" ><table class="alternate"  width="800px" style="margin: 0 auto;" id="reportagechart"><thead><tr style="font-weight:bold;"><th colspan="4" style="text-align:center;"><div><span class="folder-button" onclick="folders(\'reportageresultfolder\');">Age Report Chart</span></div></th></tr><thead><tbody id="reportageresultfolder" class="folder-content"><tr style="text-align:center;"><td style="text-align:right;width:400px;" ><canvas id="agereportcanvas" height="300px" width="800px" border="0"></canvas></td></tr></tbody></table></div></form></td></tr></table>
	*/
	echo($reply);
?>