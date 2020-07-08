<?php
	require_once('vmscFunctions.php');
//SysParam & Auto Logout & Logging
	$userid=str_clean(valunscramble($_GET[idunscramble('userid')]));
	$secid=str_clean(valunscramble($_GET[idunscramble('secid')]));
	timer(20,$userid);
//Sys logs
	$array=array('user',$userid);
	$tables=array('');
	logs($userid,'l','PersonSearchList',$array,$tables);
//App Params
	$complete=0;
//Name
	if(str_clean($_GET['firstname'])==''){
		$firstname='';
	}else{
		$firstname=str_clean($_GET['firstname']);
	}
//Surname
	if(str_clean($_GET['surname'])==''){
		$surname='';
	}else{
		$surname=str_clean($_GET['surname']);
	}
//IDNO
	if(str_clean($_GET['idno'])==''){
		$idno='';
	}else{
		$idno=str_clean($_GET['idno']);
	}
//KIDS
	if(str_clean($_GET['kids'])==''){
		$kids=0;
	}else{
		$kids=str_clean($_GET['kids']);
	}
//SPOUSE
	if(str_clean($_GET['spouse'])==''){
		$spouse=0;
	}else{
		$spouse=str_clean($_GET['spouse']);
	}
//AGE
	if(str_clean($_GET['age'])==''){
		$age=0;
	}else{
		$age=str_clean($_GET['age']);
		if($age==0){
			$ageX=$age+150;
		}else{
			$ageX=$age+10;
		}
	}
//SEX
	$sex=0;
	if(str_clean($_GET['sex'])=='1' || str_clean($_GET['sex'])=='2'){
		$sex=str_clean($_GET['sex']);
	}
//ADDICT
	if(str_clean($_GET['addict'])==0){
		$addict=0;
		$druglist='-';
	}if(str_clean($_GET['addict'])==2){
		$addict=2;			
		if(str_clean(implode(",",$_GET['druglist']))==''){
			$druglist='-';
		}else{
			$druglist=str_clean(implode(",",$_GET['druglist']));
		}
	}
	if(str_clean($_GET['addict'])==1){
		$addict=1;
		$druglist='-';
	}
//Entry
	if(str_clean($_GET['entry'])==''){
		$entry='-';
	}else{
		$entry=str_clean($_GET['entry']);
	}
//Exit
	if(str_clean($_GET['exit'])==''){
		$exit='-';
	}else{
		$exit=str_clean($_GET['exit']);
	}
//Completed
	if(str_clean($_GET['completed'])!=''){
		$complete=str_clean($_GET['completed']);
	}
//Check param values
	//echo('Firstname: '.$firstname.'<br>Surname: '.$surname.'<br>IDNo: '.$idno.'<br>kids: '.$kids.'<br>Age: '.$age);
	//echo('<br>Sex: '.$sex.'<br>Addict: '.$addict.'<br>Entry: '.$entry.'<br>Exit: '.$exit.'<br>Complete:'.$complete.'<br>Druglist: '.$druglist);
	//echo("<pre>");var_dump($_GET);echo("</pre>");
//Variables
	$count=0;
	
//Build query	
	$sql='	  select P.PersonId,P.Name,P.Surname,P.IDNo,
					 case when char_length(P.IDNo)>7 then floor(datediff(date(now()),date(concat(case when substring(ltrim(P.IDNo),1,1)<=2 then concat("20","",substring(ltrim(P.IDNo),1,2)) else concat("19","",substring(ltrim(P.IDNo),1,2)) end,"-",
       substring(ltrim(P.IDNo),3,2),"-",substring(ltrim(P.IDNo),5,2))))/365.25) else "Undetermined" end as Age,
					 CS.Name as Completed,
					 case when isnull(K.HasKids)=1 then "No" else "Yes" end as Kids,
					 case when isnull(S.Spouse)=1 then "No" else "Yes" end as Spouse,
					 case when char_length(ltrim(IDNo))>=7 then 
							   case when substr(ltrim(IDNo),7,1)>4 then "Male" else "Female" end
									else "Undetermined" end as Gender,
					 case when isnull(X.Count)=1 then "No" else "Yes" end as Addictions,
					 case when isnull(Y.AddictionList)=1 then "None" else Y.AddictionList end as AddictionList,
					 concat("include/personimages/",I.ImageId,".",I.Ext) as Image,
					 concat(case when PEE.Entrydate is null or PEE.Entrydate=date("0000-00-00") then date("0000-00-00") else PEE.Entrydate end," - ",case when PEE.Exitdate is null or PEE.Exitdate=date("0000-00-00") then date("0000-00-00") else PEE.Exitdate end," ") as DateList
				from perssonel P
				left join (select PersonId,max(EntryDate) as EntryDate,max(ExitDate) as ExitDate 
				        from personentryexit
					   group by PersonId) PEE
				  on P.PersonId=PEE.PersonId
				left join (select max(ImageId) as ImageId, Ext, PersonId
							 from personimages 
							group by PersonId) I
			      on P.PersonId=I.PersonId
				left join (select count(NodeId) as HasKids,PersonId
							 from personnodes
							group by PersonId )K
				  on P.PersonId=K.PersonId
				left join (select max(SpouseId) as Spouse,PersonId
							 from personspouse
							group by PersonId) S
				  on P.PersonId=S.PersonId
				left join (select PersonId, count(AddictionId) as Count
							 from personaddictions 
							group by PersonId) X
				  on P.PersonId=X.PersonId
				left join (select PA.PersonId,group_concat(distinct A.Abbr order by A.Abbr ASC separator ", ") as AddictionList 
							 from personaddictions PA 
							 join addictions A
							   on PA.AddictionId=A.AddictionId
							group by PersonId) Y
				  on P.PersonId=Y.PersonId
				 and PEE.EntryDate != PEE.ExitDate
				left join personaddictions PA
				  on P.PersonId=PA.PersonId
				left join completestatus CS
				  on P.Complete=CS.CompleteId
			   where (case when (? = "-" and ? != "-") then (PEE.EntryDate between "0000-00-00" and ?)
						   when (? != "-" and ? = "-") then (PEE.EntryDate between ? and "2100-01-01")
						   when (? != "-" and ? != "-") then (PEE.EntryDate between ? and ?)
						   else 1=1 end)
				 and (case when ?=1 then P.Addictions=0
						   when ?=2 then (PA.AddictionId in (?))
						   else 1=1 end)
				 and P.Name like concat("%",?,"%")
				 and P.Surname like concat("%",?,"%")
				 and case when 0!=? and 150!=? then (case when char_length(ltrim(P.IDNo))>7 and substring(ltrim(P.IDNo),3,2)<=12 and substring(ltrim(P.IDNo),4,2)<=31 then floor(datediff(date(now()),date(concat(case when substring(ltrim(P.IDNo),1,1)<=2 then concat("20","",substring(ltrim(P.IDNo),1,2)) else concat("19","",substring(ltrim(P.IDNo),1,2)) end,"-",
       substring(ltrim(P.IDNo),3,2),"-",substring(ltrim(P.IDNo),5,2))))/365.25) else 0 end between ? and ?) else 1=1 end
				 and (case when ? =2 then substr(ltrim(IDNo),7,1) >= 5
						   when ? =1 then substr(ltrim(IDNo),7,1)<=4
						   else 1=1 end)
				 and P.IDNo like concat("%",?,"%")
				 and (case when ?=0 then 1=1 else P.Complete=? end)
				 and (case when ?=1 then isnull(K.HasKids)!=1
						   when ?=2 then isnull(K.HasKids)=1
						   else 1=1 end)
				 and (case when ?=1 then isnull(S.Spouse)!=1
						   when ?=2 then isnull(S.Spouse)=1
						   else 1=1 end)
			   group by P.PersonId;';
	$types="ssssssssssiisssiiiiiisssiiii";
	$params=array($entry,$exit,$exit,$entry,$exit,$entry,$entry,$exit,$entry,$exit,
					$addict,$addict,$druglist,$firstname,$surname,
					$age,$ageX,$age,$ageX,$sex,$sex,
					$idno,$complete,$complete,$kids,$kids,$spouse,$spouse);
	//echo('<br>types: '.$types.'<br>params: '.implode(",",$params).'<br>sql: '.$sql);
	$result=query($types,$params,$sql);
	$count=mysqli_num_rows($result);
	$reply='<div style="width:1400px;margin:0 auto;padding:0 auto;overflow:auto;">
			<hr>
			<h4 style="font-size:20px">Personnel table - Showing '.$count.' results.</h4><h5 style="margin-top:0px;padding-top:0px;">Click on table headers to order ASC or DESC</h5>
				<table class="sortable" id="result" style="max-width:100%;">
					<tr>
						<td style="font-weight:bold;vertical-align:middle;border:1px solid #a6a6a6;">Image</td>
						<td class="clickable" style="font-weight:bold;vertical-align:middle;border:1px solid #a6a6a6;">Firstname</td>
						<td class="clickable" style="font-weight:bold;vertical-align:middle;border:1px solid #a6a6a6;">Surname</td>
						<td class="clickable" style="font-weight:bold;vertical-align:middle;border:1px solid #a6a6a6;">ID Number</td>
						<td class="clickable" style="font-weight:bold;vertical-align:middle;border:1px solid #a6a6a6;">Dependants</td>
						<td class="clickable" style="font-weight:bold;vertical-align:middle;border:1px solid #a6a6a6;">Age</td>
						<td class="clickable" style="font-weight:bold;vertical-align:middle;border:1px solid #a6a6a6;">Completed</td>
						<td class="clickable" style="font-weight:bold;vertical-align:middle;border:1px solid #a6a6a6;">Gender</td>
						<td class="clickable" style="font-weight:bold;vertical-align:middle;border:1px solid #a6a6a6;">Has addictions</td>
						<td class="clickable" style="font-weight:bold;vertical-align:middle;border:1px solid #a6a6a6;">Known usage</td>
						<td class="clickable" style="font-weight:bold;vertical-align:middle;border:1px solid #a6a6a6;">Entry & Exit history</td>
					</tr>
					';
	while($row=$result->fetch_assoc()){
		$reply.=	'<tr>
						<td style="cursor:pointer;" name="firstname" onclick="ajax(\'profile\',\'&'.idscramble('personid').'='.valscramble($row['PersonId']).'\');tabstyle(\'tabbutton2\',\'tr2\',\'tr3\');" ><img src="'.$row['Image'].'" width="50" height="50" /></td>
						<td style="cursor:pointer;word-break: break-all;width:150px;" name="firstname" onclick="ajax(\'profile\',\'&'.idscramble('personid').'='.valscramble($row['PersonId']).'\');tabstyle(\'tabbutton2\',\'tr2\',\'tr3\');" >'.$row['Name'].'</td>
						<td name="surname" style="word-break: break-all;max-width:150px;">'.$row['Surname'].'</td>
						<td name="idno">'.$row['IDNo'].'</td>
						<td name="inode">'.$row['Kids'].'</td>
						<td name="age">'.$row['Age'].'</td>
						<td name="completed">'.$row['Completed'].'</td>
						<td name="gender">'.$row['Gender'].'</td>
						<td name="addict">'.$row['Addictions'].'</td>
						<td name="list"><textarea style="resize:vertical;min-height:35px;width:198px;overflow:wrap;">'.$row['AddictionList'].'</textarea></td>
						<td name="dates"><textarea style="resize:vertical;min-height:35px;width:98px;overflow:wrap;">'.$row['DateList'].'</textarea></td>
					 </tr>';
	}mysqli_free_result($result);
	$reply.='	</table>
			</div>';
	echo($reply);
?>