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
	logs($userid,'l','counsellor',$array,$tables);
	
//Check security access
	$query='select SecId from secusers where UserId=?';
	$types="i";
	$params=array($userid);
	$result=query($types,$params,$query);
	while($row=$result->fetch_assoc()){
		$secid=$row['SecId'];
	}mysqli_free_result($result);
	//Develpoment
	//if($secid >2){
	//Production
	if($secid > 2){
		header('Location:General.php?'.idscramble('userid').'='.valscramble($userid).
						'&'.idscramble('navid').'='.valscramble(0).
						'&'.idscramble('secid').'='.valscramble($secid).' ');
	}
?>
<!DOCTYPE type="html">
<html lang="US">
<head>
	<link rel="shortcut icon" href="include/icons/logo.png" />
    <link rel="stylesheet" href="vmsstyle.css">
	<title>Counsellor</title>
	<script src="sorttable.js"></script>
	<script src="lib/nacl_factory.js"></script>
</head>
<body>
<?php
	navhtml($userid,$secid,$navid);
	BlockHTML::tabs_layout_table(2,array('Search','Profile Notes'));
	BlockHTML::content_layout_table($userid,4,'');
?>
	<script type="text/javascript">
	setTimeout(function(){
		refresh();window.scrollTo(0,0);},3000);
	tabstyle('tabbutton1','tr0','tr1');
	setTimeout(function(){
		window.location.href= 'logout.php?<?php echo(idscramble('userid').'='.valscramble($userid)); ?>';},1200000);
	refresh();
function tabstyle(el,A,B){
	////alert("tabstyle()");
	//Search tab
	var el1=document.getElementById('tabbutton1');
	el1.setAttribute("style","border-bottom:2px solid grey;background-color:#a6a6a6;color:#ffffff;border-radius:15px 15px 0px 0px;");
	var tr0=document.getElementById("tr0");
	var tr1=document.getElementById("tr1");
	tr0.style.visibility="hidden";
	tr1.style.visibility="hidden";
	//Profile tab
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
	////alert("In refresh");
	if(document.getElementById("tr0").style.visibility=="visible"){
		////alert("tr0");
		ajax('personsearchbox');
	}
	if(document.getElementById("tr2").style.visibility=="visible"){
		////alert("tr2");
		var person="<?php echo('&'.idscramble('personid').'='); ?>"+document.getElementById("person").value;
		ajax('profile',person);
		ajax('profilehistory',person);
	}
}
function checkajax(id){
	if(id=='tabbutton1'){
		if(document.getElementById("ajaxresponse0").innerHTML==""){
			ajax('personsearchbox');
		}
	}
}
function ajax(type,string){
	////alert(type+' '+string);
	var xhttp;
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
				case 'personsearchbox':
					response="ajaxresponse0";
				break;
				case 'personsearchlist':
					response="ajaxresponse1";
				break;
				case 'loadaddictionsS':
					response='ajaxresponseS';
				break;
				case 'profile':
					response='ajaxresponse2';
				break;
				case 'profilehistory':
					response='ajaxresponse3';
				break;
				case 'counselloreditask':
					response='addcounsellornote';
				break;
				case 'addcounsellornote':
					response='addcounsellornote';
				break;
				case 'counsellornotecheck':
					response='addcounsellornote';
				break;
				case 'updatecounsellornote':
					response='addcounsellornote';
				break;
			}
			document.getElementById(response).innerHTML=this.responseText;
			
			if(type=='personsearchlist'){
				var newtableObject = document.getElementById("result");
				sorttable.makeSortable(newtableObject);
			}
			if(type == 'profile' || type == 'addcounsellornote' || type == 'updatecounsellornote'){
				var person="<?php echo('&'.idscramble('personid').'='); ?>"+document.getElementById("person").value;
				ajax('profilehistory',person);
			}
			if(type == 'addcounsellornote'){
				folders('counsellorhistoryfolder');
			}
			if(type == 'profile'){
				setTimeout(function(){
					refresh();window.scrollTo(0,0);},3000);
			}
		}
	};
	//Gets the Search form.
	switch(type){
		case 'profile':
			xhttp.open("Get","CounsellorProfileResultNote.php?<?php echo(idscramble('userid').'='.valscramble($userid).
				'&'.idscramble('secid').'='.valscramble($secid).
				'&'.idscramble('nav').'='.valscramble(1)); ?>"+string,true);
		break;
		case 'loadaddictionsS':
			xhttp.open("Get","PersonSearchAddictionsList.php?<?php echo(idscramble('userid').'='.valscramble($userid).
				'&'.idscramble('secid').'='.valscramble($secid).
				'&'.idscramble('nav').'='.valscramble(1)); ?>"+string,true);
		break;
		case 'personsearchlist':
			xhttp.open("GET","PersonSearchList.php?<?php echo(idscramble('userid').'='.valscramble($userid).
				'&'.idscramble('secid').'='.valscramble($secid).
				'&'.idscramble('nav').'='.valscramble(1)); ?>"+string,true);
		break;
		case 'personsearchbox':
			xhttp.open("GET","PersonSearchForm.php?<?php echo(idscramble('userid').'='.valscramble($userid).
				'&'.idscramble('secid').'='.valscramble($secid).
				'&'.idscramble('nav').'='.valscramble(1)); ?>",true);
		break;
		case 'profilehistory':
			xhttp.open("GET","CounsellorProfileResultHistory.php?<?php echo(idscramble('userid').'='.valscramble($userid).
				'&'.idscramble('secid').'='.valscramble($secid).
				'&'.idscramble('nav').'='.valscramble(1)); ?>"+string,true);
		break;
		case 'counselloreditask':
			xhttp.open("GET","CounsellorProfileEditAsk.php?<?php echo(idscramble('userid').'='.valscramble($userid).
				'&'.idscramble('secid').'='.valscramble($secid).
				'&'.idscramble('nav').'='.valscramble(1)); ?>"+string,true);
		break;
		case 'addcounsellornote':
			xhttp.open("GET","CounsellorProfileNoteAdd.php?<?php echo(idscramble('userid').'='.valscramble($userid).
				'&'.idscramble('secid').'='.valscramble($secid).
				'&'.idscramble('nav').'='.valscramble(1)); ?>"+string,true);
		break;
		case 'keycounsellornote':
			xhttp.open("GET","CounsellorKeyCheck.php?<?php echo(idscramble('userid').'='.valscramble($userid).
				'&'.idscramble('secid').'='.valscramble($secid).
				'&'.idscramble('nav').'='.valscramble(1)); ?>"+string,true);
		break;
		case 'counsellornotecheck':
			xhttp.open("GET","CounsellorKeyVerify.php?<?php echo(idscramble('userid').'='.valscramble($userid).
				'&'.idscramble('secid').'='.valscramble($secid).
				'&'.idscramble('nav').'='.valscramble(1)); ?>"+string,true);
		break;
		case 'updatecounsellornote':
			xhttp.open("GET","CounsellorProfileNoteUpdate.php?<?php echo(idscramble('userid').'='.valscramble($userid).
				'&'.idscramble('secid').'='.valscramble($secid).
				'&'.idscramble('nav').'='.valscramble(1)); ?>"+string,true);
		break;
	}
	
	xhttp.send();
}
function personsearch(){
	var firstname=document.getElementById('firstname').value;
	var surname=document.getElementById('surname').value;
	var idno=document.getElementById('idno').value;
	var kids=document.getElementById('kids').value;
	var spouse=document.getElementById('spouse').value;
	var age=document.getElementById('age').value;
	var sex=document.getElementById('sex').value;
	var addict=document.getElementById('addict').value;
	var druglist=new Array();
	if(document.getElementById("druglist")){
		var select=document.getElementById("druglist");
		for(var x=0;x<select.options.length;x++){
			if(select.options[x].selected){
				druglist.push(select.options[x].value);
			}
		}
	}
	var entry=document.getElementById('entry').value;
	var exit=document.getElementById('exit').value;
	var completed=document.getElementById('completed').value;
	var string='&firstname='+firstname+
				'&surname='+surname+
				'&idno='+idno+
				'&kids='+kids+
				'&spouse='+spouse+
				'&age='+age+
				'&sex='+sex+
				'&addict='+addict+
				'&entry='+entry+
				'&exit='+exit+
				'&completed='+completed+
				'&druglist[]='+druglist+' ';
	////alert(string);
	ajax('personsearchlist',string);
}
function loadaddictions(el){
        //alert(el.value);
        if(el.value>1 && !document.getElementById("ajaxresponseS") ){
            var searchtable=document.getElementById("searchform");
            var ajaxresponse=searchtable.insertRow(8);
            var td1=ajaxresponse.insertCell(-1);
            var td2=ajaxresponse.insertCell(-1);
            td1.innerHTML='List of drugs: ';
            td1.setAttribute("style","text-align:right;");
            td2.innerHTML='';
            td2.setAttribute("id","ajaxresponseS");
            var string = '&choice=1';
            ajax('loadaddictionsS',string);
        }
        if(el.value<2 && document.getElementById("ajaxresponseS")){
            document.getElementById("searchform").deleteRow(8);
        }
        if(el.value>1 && !document.getElementById("ajaxresponseR")){
            var profiletable=document.getElementById("profile");
            var ajaxresponser=profiletable.insertRow(10);
            var td3=ajaxresponser.insertCell(-1);
            var td4=ajaxresponser.insertCell(-1);
            td3.innerHTML='List of drugs: ';
            td3.setAttribute("style","text-align:right;");
            td4.innerHTML='';
            td4.setAttribute("id","ajaxresponseR");
            var string="&choice=2";
            ajax('loadaddictionsR',string);
        }
        if(el.value<2 && document.getElementById("ajaxresponseR")){
            document.getElementById("profile").deleteRow(10);
        }
		
		/*
		
	////alert(el.value);
	if(el.value>1 && !document.getElementById("ajaxresponseS") ){
		var searchtable=document.getElementById("searchform");
		var ajaxresponse=searchtable.insertRow(8);
		var td1=ajaxresponse.insertCell(-1);
		var td2=ajaxresponse.insertCell(-1);
		td1.innerHTML='List of drugs';
		td1.setAttribute("style","text-align:right;");
		td2.innerHTML='';
		td2.setAttribute("id","ajaxresponseS");
		ajax('loadaddictions');
	}
	if(el.value<2 && document.getElementById("ajaxresponseS")){
		document.getElementById("searchform").deleteRow(8);
	}
	if(el.value>1 && !document.getElementById("ajaxresponseR")){
		var profiletable=document.getElementById("profile");
		var ajaxresponser=profiletable.insertRow(8);
		var td3=ajaxresponser.insertCell(-1);
		var td4=ajaxresponser.insertCell(-1);
		td3.innerHTML='List of drugs';
		td3.setAttribute("style","text-align:right;");
		td4.innerHTML='';
		td4.setAttribute("id","ajaxresponseR");
		ajax('loadaddictionsR');
	}
	if(el.value<2 && document.getElementById("ajaxresponseR")){
		document.getElementById("profile").deleteRow(8);
	}
*/
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
function addcounsellornote(){
	var title=document.getElementById("counsellortitle").value;
	var note=document.getElementById("counsellornote").value;
	var personid=document.getElementById("person").value;
	var string="&personid="+personid+"&title="+title+"&note="+note;
	ajax("addcounsellornote",string);
}
function editcounsellornote(id){
	////alert("clicked");
	var string="&counsid="+id;
	ajax("counselloreditask",string);
}
function updatecounsellornote(){
	var counsid=document.getElementById('counsid').value;
	////alert(counsid);
	var title=document.getElementById("counsellortitle").value;
	var note=document.getElementById("counsellornote").value;
	var personid=document.getElementById("person").value;
	var string="&personid="+personid+"&counsid="+counsid+"&title="+title+"&note="+note;
	////alert(string);
	ajax("updatecounsellornote",string);
}
function counsellornotecheckkey(){
	var counsid=document.getElementById('counsid').value;
	////alert(counsid);
	var key=document.getElementById('counsellorkey').value;
	var keyid=document.getElementById('keyid').value;
	var string="&counsid="+counsid+"&keyid="+keyid+"&key="+key;
	ajax('counsellornotecheck',string);
}
	</script>
	</body>
	</html>
