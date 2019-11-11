<?php
	require_once('vmscFunctions.php');
?>
<!DOCTYPE type="html">
<html lang="US">
<head>
    <link rel="shortcut icon" href="include/icons/logo.png" />
    <link rel="stylesheet" href="vmsstyle.css">
	<title>Vistarus Mission Station : Intake Document</title>
</head>
<body style="padding:3%;text-align:left;">
<div width="800px" style="margin:0 auto;">
	<h4>Vistarus Intake Form</h4>
	<table width="100%" style="border:0;">
		<tbody>
			<tr>
				<td colspan="2">
					<table width="700px" style="margin:0 auto;">
						<thead>
							<tr>
								<td colspan="2" style="text-align:center;font-weight:bold;">Personal Information :</td>
							</tr>			
						</thead>
						<tbody>
							<tr>
								<td style="text-align:right;" width="50%">Date of arrival</td>
								<td>
									<input type="text" id="intakedate" />
								</td>
							</tr>
							<tr>
								<td style="text-align:right;">Full Name</td>
								<td>
									<input type="text" id="ifirstname" size="38"  />
								</td>
							</tr>
							<tr>
								<td style="text-align:right;">Surname</td>
								<td>
									<input type="text" id="isurname" size="38" />
								</td>
							</tr>
							<tr>
								<td style="text-align:right;">ID Number</td>
								<td>
									<input type="text" id="iidno" size="20" />
								</td>
							</tr>
							<tr>
								<td style="text-align:right;">Driver\'s Licence</td>
								<td>
									<label><input type="radio" name="idrivers"/> Yes</label>
									<label><input type="radio" name="idrivers"/> No</label>
								</td>
							</tr>
							<tr>
								<td style="text-align:right;">S.A.S.S.A. Registered</td>
								<td>
									<label><input type="radio" name="isassa" /> Yes</label>
									<label><input type="radio" name="isassa" /> No</label>
								</td>
							</tr>
							<tr>
								<td style="text-align:right;">Addictions (Multiple)</td>
								<td>
<?php
$query='select AddictionId,Name,Abbr 
		  from addictions 
		 where AddictionId > ?';
$types='i';
$params=array(0);
$result=query($types,$params,$query);
while($row=$result->fetch_assoc()){
	echo('<label><input type="checkbox" /> '.$row['Name'].' '.$row['Abbr'].'</label><br>');
}	
?>
							</td>
						</tr>
						</tbody>
					</table>
				</td>
			</tr>
			
			<tr>
				<td>
					<table width="700px" style="margin:0 auto;">
						<thead>
							<tr>
								<td colspan="2" style="text-align:center;font-weight:bold;">
										Education & Employment :
								</td>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td style="text-align:right;" width="50%">Highest Qualification</td>
								<td>
									<textarea style="resize:vertical;min-height:60px;width:298px;" id="iqualification"></textarea>
								</td>
							</tr>
							<tr>
								<td style="text-align:right;">Previous Employer</td>
								<td><input type="text" size="38" /></td>
							</tr>
							<tr>
								<td style="text-align:right;">Contact Person</td>
								<td><input type="text" size="20" /></td>
							</tr>
							<tr>
								<td style="text-align:right;">Contact number</td>
								<td><input type="text" size="20" /></td>
							</tr>			
						</tbody>
					</table>
				</td>
			</tr>
			
			<tr>
				<td>
					<table width="700px" style="margin:0 auto;">
						<thead>
							<tr>
								<td colspan="2" style="text-align:center;font-weight:bold;">
										Marital Status :
								</td>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td style="text-align:right;" width="50%">Marital Status</td>
								<td>
									<label><input type="radio" name="istatus"/> Single</label><br>
									<label><input type="radio" name="istatus"/> Married</label><br>
									<label><input type="radio" name="istatus"/> Divorced</label><br>
									<label><input type="radio" name="istatus"/> Widowed</label><br>
								</td>
							</tr>
							<tr>
								<td colspan="2">If married, please provide your spouse\'s details:</td>
							</tr>
							<tr>
								<td style="text-align:right;">Full Name</td>
								<td>
									<input type="text" id="ispousename" size="38" />
								</td>
							</tr>
							<tr>
								<td style="text-align:right;">Surname</td>
								<td>
									<input type="text" id="ispousesurname" size="38" />
								</td>
							</tr>
							<tr>
								<td style="text-align:right;">ID Number</td>
								<td>
									<input type="text" id="ispouseidno" size="20" />
								</td>
							</tr>							
						</tbody>
					</table>
				</td>
			</tr>
			</tbody>
		</table>
		<table width="100%" style="border:0;page-break-before:always;">
			<tbody>
			<tr>
				<td>
					<table width="600px" style="margin:0 auto;">
						<thead>
							<tr>
								<td colspan="2" style="text-align:center;font-weight:bold;">
									Dependants :
								</td>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td style="text-align:center;font-weight:bold;">Age</td>
								<td style="text-align:center;font-weight:bold;" width="50%">Child\'s Name</td>
							</tr>
							<tr>
								<td style="text-align:right;"><input type="date" name="ispousekidsbdays" /></td>
								<td><input type="text" name="ispousekidsnames" size="38" /></td>
							</tr>
							<tr>
								<td style="text-align:right;"><input type="date" name="ispousekidsbdays" /></td>
								<td><input type="text" name="ispousekidsnames" size="38" /></td>
							</tr>
							<tr>
								<td style="text-align:right;"><input type="date" name="ispousekidsbdays" /></td>
								<td><input type="text" name="ispousekidsnames" size="38" /></td>
							</tr>
							<tr>
								<td style="text-align:right;"><input type="date" name="ispousekidsbdays" /></td>
								<td><input type="text" name="ispousekidsnames" size="38" /></td>
							</tr>						
						</tbody>
					</table>
				</td>
			</tr>
			
			<tr>
				<td>
					<table width="700px" style="margin:0 auto;">
						<thead>
							<tr>
								<td colspan="2" style="text-align:center;font-weight:bold;">
									Emergency Contact Details :
								</td>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td style="text-align:right;" width="50%">Contact Name</td>
								<td>
									<input type="text" id="iemergencyname"  size="38" />
								</td>
							</tr>
							<tr>
								<td style="text-align:right;">Contact Number</td>
								<td>
									<input type="text" id="iemergencynumber"  size="20" />
								</td>
							</tr>
							<tr>
								<td style="text-align:right;">Contact Address</td>
								<td>
									<textarea style="resize:vertical;min-height:60px;width:298px;" id="iemergencyaddress"></textarea>
								</td>
							</tr>			
						</tbody>
					</table>
				</td>
			</tr>
		
			<tr>
				<td>
					<table width="700px" style="margin:0 auto;">
						<thead>
							<tr>
								<td colspan="2" style="text-align:center;font-weight:bold;">
										Medical Condition :
								</td>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td style="text-align:right;" width="50%">Medical Status</td>
								<td>
									<label><input type="radio" name="ihealth"/> Excellent</label><br>
									<label><input type="radio" name="ihealth"/> Good</label><br>
									<label><input type="radio" name="ihealth"/> Poor</label><br>
								</td>
							</tr>
							<tr>
								<td style="text-align:right;">Medical Conditions</td>
								<td>
									<textarea style="resize:vertical;min-height:60px;width:298px;" id="ihealthnote"></textarea>
								</td>
							</tr>
							<tr>
								<td style="text-align:right;">Do you use medication</td>
								<td>
									<label><input type="radio" name="imeds" /> Yes</label><br>
									<label><input type="radio" name="imeds" /> No</label>
								</td>
							</tr>
							<tr>
								<td style="text-align:right;">List of medications</td>
								<td>
									<textarea style="resize:vertical;min-height:60px;width:298px;" id="imedlist"></textarea>
								</td>
							</tr>								
						</tbody>
					</table>
				</td>
			</tr>
		</tbody>
	</table>

	<p style="page-break-before:always"><p>
	<h4>WELCOME TO VISTARUS</h4>
	<p>
		This facility is designed to live out our Christian values, especially with regard to the people in need.<br>
		All members that are part of our job creation will submit to each other and to God's Word.<br>
		This facility is part of COMSERVE MINISTRIES.
	</p>
	<ol>
		<li style="font-weight:bold;">GENERAL</li>
		<p style="clear:left;">
			As part of the program you will be helped for a period of 6 months, only if you obey the rules.<br>
			After 3 months there will be an interview and then it will be decided if your contract period will be extended.<Br> 
			Vistarus does not employ people taken onto the program.<br>
			While you are under Vistarus rules and job creation program, your accommodation, food, bedding and toiletries are for free.<br>
			There are counsellors and a fulltime Social Worker to assist you with any problems you might have.<br>
			Vistarus does not pay a salary to people who are taken onto the program and the benefits as described are given in lieu of a salary.<br>
			Any money that you receive from Vistarus is a gratuity.<br>
			If you get maintenance, a SASSA pension or grant, half of it must be paid into Vistarus.<br><br>
			I have read the above and agree to the terms and conditions.<br><br>
			<table style="border:0;">
				<tr>
					<td>Signed :</td>
					<td>_________________</td>
					<td>Date :</td>
					<td>_________________</td>
				</tr>
			</table>
		</p>
		<li style="font-weight:bold;">MEALS</li>
		<p style="clear:left;">
			EATING TIMES MUST BE ADHERED TO.<br>
			NO FOOD WILL BE KEPT OR TAKEN OUT OF THE DINING HALL<br>UNLESS ARRANGEMENTS HAVE BEEN MADE THROUGH YOUR MANAGER.
		</p>
		<table class="alternate">
			<thead>
				<tr style="text-align:center;font-weight:bold;">
					<td colspan="2" width="50%">Monday - Saturday</td>
					<td colspan="2" width="50%">Sunday</td>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td style="text-align:right;">Breakfast:</td>
					<td>06:30 to 07:00</td>
					<td style="text-align:right;">Breakfast:</td>
					<td>07:00 to 07:30</td>
				</tr>
				<tr>
					<td style="text-align:right;">Tea time:</td>
					<td>10:00 to 10:15</td>
					<td style="text-align:right;">Tea time:</td>
					<td></td>
				</tr>
				<tr>
					<td style="text-align:right;">Lunch:</td>
					<td>13:00 to 14:00</td>
					<td style="text-align:right;">Lunch:</td>
					<td>12:00 to 13:00</td>
				</tr>
				<tr>
					<td style="text-align:right;">Supper:</td>
					<td>17:30 to 18:00</td>
					<td style="text-align:right;">Supper:</td>
					<td>Refreshments provided after<br>Sunday evening Church service.</td>
				</tr>
			</tbody>
		</table><br>
		<li style="font-weight:bold;">WORKING HOURS</li>
		<p style="clear:left;">
			While you are on the personnel program you are requested to keep to the working hours.<br>
			Working hours are as follow:<br>
		</p>
		<table class="alternate">
			<thead>
				<tr style="text-align:center;font-weight:bold;">
				<td>Monday - Friday</td><td>Saturday</td>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>08:00 - 13:00</td><td>08:00 - 13:00</td>
				</tr>
				<tr>
					<td>14:00 - 17:00</td><td></td>
				</tr>
			</tbody>
		</table>
		<p style="page-break-before:always;"></p>
		<li style="font-weight:bold;">LAUNDRY</li>
		<p style="clear:left;">
			Every person is allowed to give in 10 items of clothing to the laundry.
		</p>
		<table CLASS="alternate">
			<thead>
				<tr style="text-align:center;font-weight:bold;">
					<td>Group</td><td>Day's</td>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>Mens</td><td>Monday</td>
				</tr>
				<tr>
					<td>Ladies</td><td>Tuesday</td>
				</tr>
				<tr>
					<td>Bedding</td><td>Wednesday</td>
				</tr>
			</tbody>
		</table>
		<p style="clear:left;">
			No blankets are allowed to be given in.<br>
			Washing must be given in by <span style="font-weight:bold;">07:30</span> to the laundry.<br>
			Clothing items must be marked clearly and itemized in your laundry book provided.
		</p>
		<li style="font-weight:bold;">TOILETRIES</li>
		<p style="clear:left;">
			Once a week between <span style="font-weight:bold;">13:00 - 13:30</span> you will receive toiletries free of charge.<br>
			If you receive more than <span style="font-weight:bold;">R100.00</span> per week, 
			you need to supply your own toiletries. 
		</p>
		<li style="font-weight:bold;">CLOTHING BANK</li>
		<p style="clear:left;">
			The clothing bank will be open each daily between <span style="font-weight:bold;">13:00 - 14:00</span>
			for people on the program.
		</p>
		<li style="font-weight:bold;">ON COMPLETION OF THE PROGRAM</li>
		<p style="clear:left;">
			After your 3/6 months program is over you and you would like to look for a job outside,	your name will be put forward for an exit interview.<br>
			An exit interview will take place and providing certain criteria has been met, permission to look for outside work will be granted.<br>
			The criteria is as follows:
		</p>
			<ol>
				<li style="font-weight:bold;">Your counselor is happy with your progress.</li><br>
				<li style="font-weight:bold;">You have had a meeting with the Pastor.</li><br>
				<li style="font-weight:bold;">Your disciplinary record is clean.</li><br>
			</ol>
		<p>
			Every Wednesday you may go out to look for work and you must be back on the premises by 13h00.
			A form from the office needs to be taken with you which must be filled in by the manager of the company you are going to for an interview.
		</p>
		<li style="font-weight:bold;">SPIRITUAL ASPECTS</li>
		<p style="clear:left;">
			
		</p>
			<ol>
				<li style="font-weight:bold;">MONDAY - ALPHA COURSE: 19:00</li><br>
				<li style="font-weight:bold;">TUESDAY - CELL GROUP: 19:00</li><br>
				<li style="font-weight:bold;">WEDNESDAY - VICTORY SUPPORT GROUP: 19:00</li><br>
				<li style="font-weight:bold;">THURSDAY - EE3 DISCIPLESHIP 19:00</li><br>
				<li style="font-weight:bold;">FRIDAY - YOUTH 19:00</li><br>
				<li style="font-weight:bold;">SUNDAY EVENING - CHILDREN'S CHURCH: 18:00</li><br>
				<li style="font-weight:bold;">SUNDAY EVENING - CHURCH SERVICE: 18:00</li><br>
			</ol>
		<p style="clear:left;">
			Only valid reasons will be accepted if you are unable to attend any services.<br>
			We would like to encourage you to build a solid relationship with our Lord Christ Jesus.
		</p>
		<li style="font-weight:bold;">BED TIMES</li>
		<p style="clear:left;">
			All personnel must be in their bungalows or rooms at <span style="font-weight:bold;">20:30</span>, from Sunday to Thursday.<br>
			Fridays and Saturdays at <span style="font-weight:bold;">22:30</span>. Permission may be granted to personnel to go to bed later, if they work late.<br>
			Devotions will be held at <span style="font-weight:bold;">20h30</span> in the bungalows or in your room with the door remaining open.
		</p>
		<p style="page-break-before:always;"></p>
		<li style="font-weight:bold;">VISITING</li>
		<p style="clear:left;">
			NO MEN ON THE PROGRAM ARE ALLOWED TO VISIT IN THE WOMEN'S ROOMS AND NO WOMEN ON THE PROGRAM ARE ALLOWED TO VISIT IN THE MEN'S ROOMS.<br>
			NO INTERACTION IS ALLOWED BETWEEN PEOPLE ON THE PROGRAM AND RENTERS.
		</p>
		<li style="font-weight:bold;">CELL-PHONES</li>
		<p style="clear:left;">
			People who are accepted onto the program must hand in their cellphones to the office.<br>
			These cellphones will be kept in a safe for the first 30 days of a person being on the program<br>
			and then returned to them.
		</p>
		<li style="font-weight:bold;">IDENTITY DOCUMENTS</li>
		<p style="clear:left;">
			Identity Document Books of people that are accepted onto the program must be handed in to the office for safekeeping. 
		</p>
		<li style="font-weight:bold;">CLINIC</li>
		<p style="clear:left;">
			A day pass must be filled in the day before you are going to the clinic.<br>
			It must state the reason for going to the clinic and must be signed by the Leader in your workplace.<br>
			It must then be returned to Security before <span style="font-weight:bold;">19:30</span>, prior to the clinic day, for authorization.
		</p>
	</ol>
	<p>I ________________________________________   HAVE TO THE BEST OF MY KNOWLEDGE,<br>
	READ AND UNDERSTOOD THE RULES AND CONDITIONS WHICH I HAVE FILLED IN.<br>
	SIGNED ON THIS__________DAY OF _____________2018.<br><br><br>
	
	<table style="width:525px;border:0;">
		<tr>
			<td width="175px">____________________</td>
			<td width="175px"></td>
			<td width="175px">____________________</td>
		</tr>
		<tr style="font-weight:bold;text-align:center;">
			<td width="175px">SIGNATURE</td>
			<td width="175px"></td>
			<td width="175px">WITNESS</td>
		</tr>
	</table>
	<br><br>
	</p>
	<p style="page-break-before:always;"></p>
	<table style="border:0;">
		<tr>
			<td>DATE:</td>
			<td>___ / </td>
			<td>___ / </td>
			<td>20___  </td>
			
			<td>&nbsp; NAME & SURNAME:</td>
			<td>_____________________</td>
		</tr>
	</table>
	
	<h4>GENERAL RULES OF VISTARUS.</h4>
	<p>
		OUR AIM IS TO ENSURE THAT VISTARUS IS A SAFE AND RELAXED ENVIRONMENT.
	</p>
	<ol>
		<li>NO ALCOHOL OR DRUGS ARE ALLOWED IN YOUR SYSTEM OR ON THE PREMISES.<br>(TESTS WILL BE DONE IF WE SUSPECT THAT THE RULE IS NOT APPLIED TO.)</li><br>
		<li style="clear:left;">NO PORNOGRAPHY WILL BE ALLOWED ON THE PREMISES.</li><br>
		<li style="clear:left;">PROSTITUTION IS NOT ALLOWED.</li><br>
		<li style="clear:left;">NO VISITING IN THE OPPOSITE GENDER’S ROOMS WILL BE ALLOWED.</li><br>
		<li style="clear:left;">RELATIONSHIPS (LOVE) BETWEEN MEN AND WOMAN ARE NOT ALLOWED.</li><br>
		<li style="clear:left;">MEN AND WOMAN EAT AT SEPARATE TABLES IN THE DINING ROOM.</li><br>
		<li style="clear:left;">NO FOUL LANGUAGE OR BLASPHEMY WILL BE TOLERATED.</li><br>
		<li style="clear:left;">NO INTERACTION IS ALLOWED BETWEEN PEOPLE ON THE PROGRAM AND RENTERS</li><br>
		<li style="clear:left;">IF ANY CONTRABAND IS SUSPECTED, WE WILL SEARCH YOUR ROOM OR BUNGALOW.</li><br>
		<li style="clear:left;">NO FIREARMS, AMMUNITION OR DANGEROUS WEAPONS ARE ALLOWED ON THE PREMISES, PLEASE HAND SUCH ITEMS IN AT THE OFFICE.</li>
		<li style="clear:left;">SCHOOL CHILDREN MUST ATTEND SCHOOL. ONLY VALID REASONS WILL BE ACCEPTED IF THEY DO NOT ATTEND SCHOOL.</li><br>
		<li style="clear:left;">PRE SCHOOL AND PRIMARY SCHOOL CHILDREN MUST BE IN THEIR ROOMS BY <span style="font-weight:bold;">19:30</span> WEEKDAYS. ON WEEKENDS AND SCHOOL HOLIDAYS <span style="font-weight:bold;">20:30</span>.</li><br>
		<li style="clear:left;">HIGH SCHOOL CHILDREN MUST BE IN THEIR ROOMS BY <span style="font-weight:bold;">20:30</span>. ON WEEKENDS AND SCHOOL HOLIDAYS <span style="font-weight:bold;">21:00</span>.</li><br>
		<li style="clear:left;">DISCIPLINING OF YOUR CHILDREN IS YOUR OWN RESPONSIBILITY.</li><br>
		<li style="clear:left;">ONLY PRE SCHOOL CHILDREN ARE ALLOWED TO BE IN THE BATHROOMS WITH THEIR PARENTS.</li><br>
		<li style="clear:left;">NO BUSINESS IS ALLOWED TO BE PRACTICED FROM YOUR ROOMS.</li><br>
		<li style="clear:left;">NO FURNITURE, MATTRESSES, OR BEDDING OF VISTARUS IS ALLOWED TO BE REMOVED OR SHIFTED<br>WITHOUT PERMISSION FROM THE MANAGEMENT.</li><br>
		<li style="clear:left;">WALLS OR THE PAINT ON THE WALLS OF ROOMS ARE NOT TO BE DAMAGED THROUGH NAILS OR SHELVING.</li><br>
		<li style="clear:left;">PLEASE USE WATER AND ELECTRICITY SPARINGLY</li><br>
		<li style="clear:left;">NO VISITORS ARE ALLOWED AFTER <span style="font-weight:bold;">20:00</span> DURING THE WEEK AND <span style="font-weight:bold;">21:00</span> ON THE WEEKENDS.</li><br>
		<li style="clear:left;">DAMAGING OF VISTARUS PROPERTY WILL NOT BE TOLERATED<br>AND THE COST OF DAMAGES WILL BE RECOUPED FROM THE GUILTY PARTY </li><br>
		<li style="clear:left;">PLEASE TAKE YOUR NEIGHBOURS INTO ACCOUNT, BY KEEPING NOISE LEVELS DOWN.</li><br>
		<li style="clear:left;">LAUNDRY MAY NOT BE DONE IN THE BATHROOMS.</li><br>
		<li style="clear:left;">URINE BUCKETS ARE NOT ALLOWED IN YOUR ROOM EXCEPT IF YOU HAVE A DOCTORS NOTE.</li><br>
		<li style="clear:left;">GARBAGE OR FLUIDS MAY NOT BE THROWN OUT OF THE WINDOWS.</li><br>
		<li style="clear:left;">DONT THROW ANY GARBAGE OR CIGARETTE BUTTS AROUND THE BUILDING.<br>PLEASE THROW IT INTO THE DUST BINS PROVIDED.</li><br>
		<li style="clear:left;">ALL MEDICATION IS TO BE HANDED IN AT THE OFFICE.</li><br>
	</ol>
	<p style="page-break-before:always;"></p>
	<p style="clear:both;"><br><br>
		WE HERE AT VISTARUS ARE TRYING TO MAKE OUR HOME,<br>
		A SAFE AND ENJOYABLE PLACE TO STAY FOR ALL RENTERS AND PERSONNEL.<br>
		WE WOULD GREATLY APPRECIATE YOUR CO-OPERATION.
	</p>
	<br>
	<table style="border:0;">
		<tr>
			<td>SIGNED AT VISTARUS ON THE __________ DAY OF ____________ 2018</td><td></td>
		</tr>
	</table>
	<br>
	<table style="border:0;width:500px;">
		<tr style="text-align:center;">
			<td>SIGNATURE</td><td>WITNESS</td>
		</tr>
		<tr style="text-align:center;">
			<td>___________________</td><td>1. ___________________</td>
		</tr>
		<tr style="text-align:center;">
			<td></td><td>2. ___________________</td>
		</tr>
	</table>
	<p style="page-break-before:always;"></p>
	<h4>INDEMNIFICATION/SKADELOOSSTELLING</h4>
	<p>
		I, ________________________________________________________   [please print]
		<br>
		Herby indemnify VISTARUS CENTRE and COMSERVE MINISTRIES [or any of its employees, servants or agents] <br>
		against any loss or injury of whatever nature sustained by myself or any minor member of my family<br>
		in the course of any activities of COMSERVE MINISTRIES and VISTARUS CENTRE.<br>
		Signed by me in the presence of the undersigned witnesses.
	</p>
	<table style="border:0;width:500px;">
		<tr><td>This the ____ day of   ______________________  2018</td></tr>
	</table>
	<br>
	<table style="border:0;width:500px;">
		<tr style="text-align:center;">
			<td>SIGNATURE</td><td>WITNESS</td>
		</tr>
		<tr style="text-align:center;">
			<td>___________________</td><td>1. ___________________</td>
		</tr>
		<tr style="text-align:center;">
			<td></td><td>2. ___________________</td>
		</tr>
	</table>
	

	<p>
		Ek _______________________________________________________  [drukskrif asb]
		<br>
		Stel hiermee vir COMSERVE MINISRIES en VISTARUS  SENTRUM [of enige van hul werknemers, werks en agente]<br>
		skadeloos teen enige verlies of besering van enige aard gely deur my self of enige minderjarige lid van my familie<br>
		tydens enige aktiwiteit van COMSERVE MINISRIES en VISTARUS  SENTRUM.<br>
		Geteken deur my in die teenwoordigheid van die ondergetekende getuies
	</p>
	<table style="border:0;width:500px;">
		<tr><td>GETEKEN HIERDIE ___ DAG VAN ____________________  2018</td></tr>
	</table>
	<br>
	<table style="border:0;width:500px;">
		<tr style="text-align:center;">
			<td>HANDTEKENING</td><td>AS GETUIES</td>
		</tr>
		<tr style="text-align:center;">
			<td>___________________</td><td>1. ___________________</td>
		</tr>
		<tr style="text-align:center;">
			<td></td><td>2. ___________________</td>
		</tr>
	</table>
	<h4>Eviction clause</h4>
	<p>
	I, _______________________, ID number __________________
	<br><br>
	hereby agree to voluntary vacate my room or dormitory when requested to by
	Vistarus management.
	<br>
	I acknowledge that I may be requested to vacate my room or dormitory for the
	following reasons:
	<ol>
		<li>alcohol abuse</li>
		<li>drug abuse</li>
		<li>fornication</li>
		<li>family violence</li>
		<li>criminal activities</li>
		<li>non-compliance with Vistarus rules</li>
	</ol>
	</p>
	<table style="border:0;width:500px;">
		<tr>
			<td>Date</td><td>________________</td>
		</tr>
		<tr style="text-align:center;">
			<td>SIGNATURE</td><td>WITNESS</td>
		</tr>
		<tr style="text-align:center;">
			<td>___________________</td><td>1. ___________________</td>
		</tr>
		<tr style="text-align:center;">
			<td></td><td>2. ___________________</td>
		</tr>
	</table>
</div>
</body>
</html>