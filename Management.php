<?php
	require_once('vmscFunctions.php');
	require_once('vmscBlockHTML.php');
	//SysParam & Auto Logout & Logging
	$userid=str_clean(valunscramble($_GET[idunscramble('userid')]));
	$secid=str_clean(valunscramble($_GET[idunscramble('secid')]));
	$navid=str_clean(valunscramble($_GET[idunscramble('navid')]));
	timer(20,$userid);
//Sys logs
	$array=array('user',$userid);
	$tables=array('');
	logs($userid,'l','manager',$array,$tables);
?>
<!DOCTYPE type="html">
<html lang="US">
<head>
	<link rel="shortcut icon" href="include/icons/logo.png" />
    <link rel="stylesheet" href="vmsstyle.css">
	<title>Manager</title>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.bundle.js"></script>
</head>
<body>
<?php
	navhtml($userid,$secid,$navid);
	BlockHTML::tabs_layout_table(2,array('Personnel Reports','Leave Reports'));
	BlockHTML::content_layout_table($userid,4,'');
?>
<script type="text/javascript">
	tabstyle('tabbutton1','tr0','tr1');
	setTimeout(function(){
		window.location.href= 'logout.php?<?php echo(idscramble('userid').'='.valscramble($userid)); ?>';},1200000);
	refresh();
	
function tabstyle(el,A,B){
	//alert("tabstyle()");
	//Perssonel Reports tab
	setTimeout(function(){
             refresh();window.scrollTo(0,0);},3000);
	var el1=document.getElementById('tabbutton1');
	el1.setAttribute("style","border-bottom:2px solid grey;background-color:#a6a6a6;color:#ffffff;border-radius:15px 15px 0px 0px;");
	var tr0=document.getElementById("tr0");
	var tr1=document.getElementById("tr1");
	tr0.style.visibility="hidden";
	tr1.style.visibility="hidden";
	//Leave Reports tab
	var el2=document.getElementById('tabbutton2');
	el2.setAttribute("style","border-bottom:2px solid grey;background-color:#a6a6a6;color:#ffffff;border-radius:15px 15px 0px 0px;");
	var tr2=document.getElementById("tr2");
	var tr3=document.getElementById("tr3");
	tr2.style.visibility="hidden";
	tr3.style.visibility="hidden";
	//Selected tab

	var el=document.getElementById(el);
	el.setAttribute("style","outline:none;border-bottom:0px solid #cccccc;background-color:#cccccc;");
	var trA=document.getElementById(A);
	var trB=document.getElementById(B);
	trA.style.visibility="visible";
	trA.setAttribute("index",0);
	trB.style.visibility="visible";
	trB.setAttribute("index",1);
	var wrapper = trB.parentNode;
	wrapper.insertBefore(trB, wrapper.firstChild);
	wrapper.insertBefore(trA, wrapper.firstChild);
}
function refresh(){
	//alert("In refresh");
	if(document.getElementById("tr0").style.visibility=="visible"){
		//alert("tr0");
		ajax('agereport');
		//ajax('addictionreport');
	}
	if(document.getElementById("tr2").style.visibility=="visible"){
		//alert("tr2");
	}
}
function checkajax(id){
	if(id=='tabbutton1'){
		if(document.getElementById("ajaxresponse0").innerHTML==""){
			ajax('agereport');
		}
	}
}
function ajax(type,string){
	//alert(type+' '+string);
	var xhttp;
	var result;
	var response="";
	if(window.XMLHttpRequest){
		//IE7+, Firefox, Chrome, Opera, Safari
		xhttp = new XMLHttpRequest();
	}else{
		//IE6, IE5
		xhttp = new ActiveXObject("Microsoft.XMLHTTP");
	}
	xhttp.onreadystatechange=function(){
		if(this.readyState==4 && this.status==200){
			switch(type){
				case 'agereport':
					response="ajaxresponse0";
				break;
				case 'addictionreport':
					response='ajaxresponse1';
				break;
			}
			document.getElementById(response).innerHTML=this.responseText;
		}
	};
	//Gets the Search form.
	switch(type){
		case 'agereport':
			xhttp.open("Get","ManagementReportAge.php?<?php echo(idscramble('userid').'='.valscramble($userid).
				'&'.idscramble('secid').'='.valscramble($secid).
				'&'.idscramble('nav').'='.valscramble(1)); ?>",true);
		break;
		case 'agereportresult':
			xhttp.open("Get","ManagementReportAgeResult.php?<?php echo(idscramble('userid').'='.valscramble($userid).
				'&'.idscramble('secid').'='.valscramble($secid).
				'&'.idscramble('nav').'='.valscramble(1)); ?>"+string,true);
		break;
		case 'addictionreport':
			xhttp.open("Get","ManagementReportAddictions.php?<?php echo(idscramble('userid').'='.valscramble($userid).
				'&'.idscramble('secid').'='.valscramble($secid).
				'&'.idscramble('nav').'='.valscramble(1)); ?>"+string,true);
		break;
	}
	xhttp.send();
}
function folders(id1,id2){
	var content=document.getElementById(id1);
	if(content.style.display === "block"){
		content.style.display = "none";
	}else{
		content.style.display = "block";
	}
	if(id2){
		var add=document.getElementById(id2);
		if(add.style.display === "block"){
			add.style.display = "none";
		}else{
			add.style.display = "block";
		}
	}
}
function spanbuttonhover(obj){
	obj.setAttribute("style","background-color:#737373;cursor:pointer;");
	setTimeout(function(){ obj.setAttribute("style","background-color:transparent"); }, 3000);
}
function reportage(){
	var status=document.getElementById("completestatus").value;
	var fromdate=document.getElementById("fromdate").value;
	var todate=document.getElementById("todate").value;
	if(fromdate==''){
		fromdate='1900-01-01';
	}
	if(todate==''){
		todate='2050-01-01';
	}
	var string='&status='+status+'&fromdate='+fromdate+'&todate='+todate;
	var div=document.getElementById("reportageresult");
	div.width="800px";
	div.innerHTML='   <table style="border:1px solid #cccccc;width:800px;margin:0 auto;padding:0 0;">'
					+'	<tr>'
					+'		<td style="text-align:center;">'
					+'			<form>'
					+'				<div id="reportage" style="clear:both;" >'
					+'					<table class="alternate"  width="800px" style="margin: 0 auto;" id="reportagechart">'
					+'						<thead>'
					+'							<tr style="font-weight:bold;">'
					+'								<th colspan="4" style="text-align:center;">'
					+'									<div>'
					+'										<span class="folder-button" onclick="folders(\'reportageresultfolder\');">Age Report Chart <span style="font-size:0.8em;">From: '+fromdate+' To: '+todate+'</span></span>'
					+'									</div>'
					+'								</th>'
					+'							</tr>'
					+'						<thead>'
					+'						<tbody id="reportageresultfolder" class="folder-content">'
					+'							<tr style="text-align:center;">'
					+'								<td style="text-align:center;width:400px;" >'
					+'									<div id="chart_result" height="200" width="800px" border="0"><h4>Total people in the selection :<span id="age-result-total"></span></h4></div>'
					+'								</td>'
					+'							</tr>'
					+'							<tr style="text-align:center;">'
					+'								<td style="text-align:right;width:400px;" >'
					+'									<canvas id="chart_div" height="600px" width="800px" border="0"></canvas>'
					+'								</td>'
					+'							</tr>'
					+'						</tbody>'
					+'					</table>'
					+'				</div>'
					+'			</form>'
					+'		</td>'
					+'	</tr>'
					+'</table>';
	var xhttp;
	var result;
	if(window.XMLHttpRequest){
		//IE7+, Firefox, Chrome, Opera, Safari
		xhttp=new XMLHttpRequest();
	}else{
		//IE6, IE5
		xhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xhttp.onreadystatechange=function(){
		if(this.readyState==4 && this.status==200){
			result=this.responseText;
			var myString = JSON.parse(result);
			//alert(typeof myString);
			var myArray = Object.values(myString);
			////alert(typeof myArray);
			//alert(myArray);
			//alert(myArray.length);
			var tot=0;
			for(var x = 0 ;x < myArray.length; x++){
				tot += Number(myArray[x]);
				//alert(myArray[x]);
			}
			//alert(tot);
			document.getElementById("age-result-total").innerHTML = tot;
			const CHRT=document.getElementById("chart_div").getContext("2d");
			folders('reportageresultfolder');
			Chart.defaults.global.animation.duration=500;
			var chartObj=new Chart(CHRT,{
				type:"bar",
				data:{
					labels:["1920's","1930,s","1940,s","1950,s","1960,s","1970,s","1980,s","1990,s","2000,s","2010,s"],
					datasets:[
						{
							label:"Number of people grouped in decades.",
							//backgroundColor:['#a6a6a6','##737373','#a6a6a6','##737373','#a6a6a6','##737373','#a6a6a6','##737373','#a6a6a6','##737373'],
							data:JSON.parse(result)
						}
					]
				}
			});
		}
	};
	xhttp.open("Get","ManagementReportAgeResult.php?<?php echo(idscramble('userid').'='.valscramble($userid).
		'&'.idscramble('secid').'='.valscramble($secid).
		'&'.idscramble('nav').'='.valscramble(1)); ?>"+string,true);
	xhttp.send();
}
function reportaddictions(){
	var fromdate=document.getElementById("addictionsfromdate").value;
	var todate=document.getElementById("addictionstodate").value;
		if(fromdate==''){
		fromdate='1900-01-01';
	}
	if(todate==''){
		todate='2050-01-01';
	}
	var druglist=new Array();
	var drugnames=new Array();
	if(document.getElementById("addictionslist")){
		var select=document.getElementById("addictionslist");
		for(var x=0;x<select.options.length;x++){
			if(select.options[x].selected){
				druglist.push(select.options[x].value);
				drugnames.push(select.options[x].innerHTML);
			}
		}
	}
	var string='&druglist='+druglist+'&fromdate='+fromdate+'&todate='+todate;
	//alert(string);
	//alert(select.options[0].innerHTML);
	var div=document.getElementById("addictionreportresult");
	div.width="800px";
	div.innerHTML='   <div id="addictionsdebug"></div><table style="border:1px solid #cccccc;width:800px;margin:0 auto;padding:0 0;">'
					+'	<tr>'
					+'		<td style="text-align:center;">'
					+'			<form>'
					+'				<div id="reportaddiction" style="clear:both;" >'
					+'					<table class="alternate"  width="800px" style="margin: 0 auto;" id="reportaddictionchart">'
					+'						<thead>'
					+'							<tr style="font-weight:bold;">'
					+'								<th colspan="4" style="text-align:center;">'
					+'									<div>'
					+'										<span class="folder-button" onclick="folders(\'reportaddictionresultfolder\');">Addiction Report Chart <span style="font-size:0.8em;">From: '+fromdate+' To: '+todate+'</span></span>'
					+'									</div>'
					+'								</th>'
					+'							</tr>'
					+'						<thead>'
					+'						<tbody id="reportaddictionresultfolder" class="folder-content">'
					+'							<tr style="text-align:center;">'
					+'								<td style="text-align:right;width:400px;" >'
					+'									<canvas id="addictions_div" height="600px" width="800px" border="0"></canvas>'
					+'								</td>'
					+'							</tr>'
					+'						</tbody>'
					+'					</table>'
					+'				</div>'
					+'			</form>'
					+'		</td>'
					+'	</tr>'
					+'</table>';
					
	var xhttp;
	var result;
	if(window.XMLHttpRequest){
		//IE7+, Firefox, Chrome, Opera, Safari
		xhttp=new XMLHttpRequest();
	}else{
		//IE6, IE5
		xhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xhttp.onreadystatechange=function(){
		if(this.readyState==4 && this.status==200){
			var debug=document.getElementById("addictionsdebug");
			result=this.responseText;
			var myData=JSON.parse(result);
			
			//debug.innerHTML=result;
			//debug.innerHTML=myData[0]+"<br>"+myData[1];
			//debug.innerHTML=JSON.parse(result);
			const CHRT=document.getElementById("addictions_div").getContext("2d");
			folders('reportaddictionresultfolder');
			Chart.defaults.global.animation.duration=500;
			var chartObj=new Chart(CHRT,{
				type:"bar",
				data:{
					labels:myData[0],
					datasets:[
						{
							label:"List of reported drugs used.",
							//backgroundColor:['#a6a6a6','##737373','#a6a6a6','##737373','#a6a6a6','##737373','#a6a6a6','##737373','#a6a6a6','##737373'],
							data:myData[1]
						}
					]
				}
			});
		}
	};
	xhttp.open("Get","ManagementReportAddictionsResult.php?<?php echo(idscramble('userid').'='.valscramble($userid).
		'&'.idscramble('secid').'='.valscramble($secid).
		'&'.idscramble('nav').'='.valscramble(1)); ?>"+string,true);
	xhttp.send();
}

	</script>
</body>
</html>