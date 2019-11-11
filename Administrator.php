<?php
	require_once('vmscFunctions.php');
	require_once('vmscBlockHTML.php');
	//SysParam & Auto Logout & Logging
	$userid=str_clean(valunscramble($_GET[idunscramble('userid')]));
	$secid=str_clean(valunscramble($_GET[idunscramble('secid')]));
	$navid=str_clean(valunscramble($_GET[idunscramble('navid')]));
	//timer(20,$userid);

//Sys logs
	$array=array('user',$userid);
	$tables=array('');
	logs($userid,'l','administrator',$array,$tables);
?>
<!DOCTYPE type="html">
<html lang="US">
<head>
    <link rel="shortcut icon" href="include/icons/logo.png" />
    <link rel="stylesheet" href="vmsstyle.css">
	<title>Admin</title>
</head>	
<body>
<?php
    navhtml($userid,$secid,$navid);
	BlockHTML::tabs_layout_table(3,array('Logs','System Users','Maintenance'));
	BlockHTML::content_layout_table($userid,6);
?>
<script type="text/javascript">
	tabstyle('tabbutton1','tr0','tr1');
	setTimeout(function(){
		window.location.href= 'logout.php?<?php echo(idscramble('userid').'='.valscramble($userid)); ?>';},1200000);
	refresh();
	setTimeout(function(){
             refresh();window.scrollTo(0,0);},3000);
function tabstyle(el,A,B){
	//Logs tab
	var el1=document.getElementById('tabbutton1');
	el1.setAttribute("style","	border-bottom:2px solid grey;"+
							"	background-color:#a6a6a6;"+
							"	color:#ffffff;"+
							"	border-radius:15px 15px 0px 0px;");
	var tr0=document.getElementById("tr0");
	var tr1=document.getElementById("tr1");
	tr0.style.visibility="hidden";
	tr1.style.visibility="hidden";
	//System users tab
	var el2=document.getElementById('tabbutton2');
	el2.setAttribute("style","	border-bottom:2px solid grey;"+
							"	background-color:#a6a6a6;"+
							"	color:#ffffff;"+
							"	border-radius:15px 15px 0px 0px;");
	var tr2=document.getElementById("tr2");
	var tr3=document.getElementById("tr3");
	tr2.style.visibility="hidden";
	tr3.style.visibility="hidden";
	//Personnel tab
	var el3=document.getElementById('tabbutton3');
	el3.setAttribute("style","	border-bottom:2px solid grey;"+
							"	background-color:#a6a6a6;"+
							"	color:#ffffff;"+
							"	border-radius:15px 15px 0px 0px;");
	var tr4=document.getElementById("tr4");
	var tr5=document.getElementById("tr5");
	tr4.style.visibility="hidden";
	tr5.style.visibility="hidden";
	//Selected tab
	var el=document.getElementById(el);
	el.setAttribute("style","	outline:none;"+
							"	border-bottom:0px solid #cccccc;"+
							"	background-color:#cccccc;");
	var trA=document.getElementById(A);
	var trB=document.getElementById(B);
	trA.style.visibility="visible";
	trB.style.visibility="visible";
	var wrapper = trB.parentNode;
	wrapper.insertBefore(trB, wrapper.firstChild);
	wrapper.insertBefore(trA, wrapper.firstChild);
}
function refresh(){
	//alert("In refresh");
	if(document.getElementById("tr0").style.visibility=="visible"){
		//alert("tr0 : sysuserslogs");
		ajax('sysuserslogs');
	}
	if(document.getElementById("tr2").style.visibility=="visible"){
		//alert("tr2");
		ajax('sysusers');
		ajax('sysuserstable');
	}
	if(document.getElementById("tr4").style.visibility=="visible"){
		//alert("tr4");
		ajax('maintenance');
	}
}
function checkajax(id){
	//alert("in checkajax");
	if(id=='tabbutton1'){
		if(document.getElementById("ajaxresponse0").innerHTML==""){
			ajax('sysuserslogs');
		}
	}
	if(id=='tabbutton2'){
		if(document.getElementById("ajaxresponse2").innerHTML==""){
			ajax('sysusers');
			ajax('sysuserstable');
		}
	}
	if(id=='tabbutton3'){
		if(document.getElementById("ajaxresponse4").innerHTML==""){
			ajax('maintenance');
		}
	}
}
function ajax(type,string){
	//alert(type+' '+string);
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
    //alert("xhttp.onreadystatechange=function()");
		if(this.readyState==4 && this.status==200){
			switch(type){
				case 'sysuserslogs':
					//alert("onreadystatechange : sysuserslogs");
					response='ajaxresponse0';
				break;
				case 'sysusers':
					response='ajaxresponse2';
				break;
				case 'addsysusersadd':
					response='ajaxresponse2';
				break;
				case 'sysuseredit':
					response='ajaxresponse2';
				break;
				case 'updatesysusersupdate':
					response='ajaxresponse2';
				break;
				case 'sysuserremove':
					response='ajaxresponse2';
				break;
				case 'sysuserstable':
					response='ajaxresponse3';
				break;
				case 'maintenance':
					response='ajaxresponse4';
				break;
				case 'sysaddictionupdate':
					response='sysmainaddictions';
				break;
				case 'sysoffenceupdate':
					response='sysmainoffences';
				break;
				case 'sysdepartmentupdate':
					response='sysmaindepartments';
				break;
			}
			document.getElementById(response).innerHTML=this.responseText;
			switch(type){
				case 'sysuseredit':
					folders('sysuserformfolder');
				break;
				case 'sysuserslogs':
					folders('loggedinfolder');
				break;
				case 'addsysusersadd':
					folders('sysuserformfolder');
				break;
				case 'sysoffenceupdate':
					folders('sysmainoffencesfolder','addoffencesbutton');
				break;
				case 'sysdepartmentupdate':
					folders('sysmaindepartmentsfolder','adddepartmentsbutton');
				break;
				case 'sysaddictionupdate':
					folders('sysmainaddictionsfolder','addaddictionbutton');
				break;
			}
			if(type=='addsysusersadd' || type=='updatesysusersupdate' || type=='sysuserremove'){
				ajax('sysuserstable');
			}
			if(type=='updatesysusersupdate'){
				ajax('sysusers');
			}
		}
	};
	switch(type){
	//Gets the Add form.
		case 'sysusers':
			xhttp.open("GET","SysUserManage.php?<?php echo(idscramble('userid').'='.valscramble($userid).
				'&'.idscramble('secid').'='.valscramble($secid).
				'&'.idscramble('nav').'='.valscramble(1)); ?>",true);
		break;
	//Gets the users table
		case 'sysuserstable':
			xhttp.open("GET","SysUserManageTable.php?<?php echo(idscramble('userid').'='.valscramble($userid).
				'&'.idscramble('secid').'='.valscramble($secid)); ?>",true);
		break;
	//Adds a user to the system
		case 'addsysusersadd':
			xhttp.open("GET","SysUserManageAdd.php?<?php echo(idscramble('userid').'='.valscramble($userid).
				'&'.idscramble('secid').'='.valscramble($secid)); ?>"+string,true);
		break;
	//Gets the user editor
		case 'sysuseredit':
			xhttp.open("GET","SysUserManage.php?<?php echo(idscramble('userid').'='.valscramble($userid).
				'&'.idscramble('secid').'='.valscramble($secid).
				'&'.idscramble('nav').'='.valscramble(2)); ?>"+string,true);
		break;
	//Updates the user to the system
		case 'updatesysusersupdate':
			xhttp.open("GET","SysUserManageUpdate.php?<?php echo(idscramble('userid').'='.valscramble($userid).
				'&'.idscramble('secid').'='.valscramble($secid)); ?>"+string,true);
		break;
	//Removes the user from the system
		case 'sysuserremove':
			xhttp.open("GET","SysUserManageRemove.php?<?php echo(idscramble('userid').'='.valscramble($userid).
				'&'.idscramble('secid').'='.valscramble($secid)); ?>"+string,true);
		break;
	//Gets logged in users
		case 'sysuserslogs':
			xhttp.open("GET","SysUserManageLoggedIn.php?<?php echo(idscramble('userid').'='.valscramble($userid).
				'&'.idscramble('secid').'='.valscramble($secid)); ?>",true);
		break;
	//Maintenance
		case 'maintenance':
			xhttp.open("GET","SysMaintenance.php?<?php echo(idscramble('userid').'='.valscramble($userid).
				'&'.idscramble('secid').'='.valscramble($secid).
				'&'.idscramble('nav').'='.valscramble(2)); ?>"+string,true);
		break;
	//Addictions
		case 'sysaddictionupdate':
			xhttp.open("GET","SysMaintenanceAddictionUpdate.php?<?php echo(idscramble('userid').'='.valscramble($userid).
				'&'.idscramble('secid').'='.valscramble($secid).
				'&'.idscramble('nav').'='.valscramble(2)); ?>"+string,true);
		break;
	//Offences
		case 'sysoffenceupdate':
			xhttp.open("GET","SysMaintenanceOffenceUpdate.php?<?php echo(idscramble('userid').'='.valscramble($userid).
				'&'.idscramble('secid').'='.valscramble($secid).
				'&'.idscramble('nav').'='.valscramble(2)); ?>"+string,true);
		break;
	//Department 
		case 'sysdepartmentupdate':
			xhttp.open("GET","SysMaintenanceDepartmentUpdate.php?<?php echo(idscramble('userid').'='.valscramble($userid).
				'&'.idscramble('secid').'='.valscramble($secid).
				'&'.idscramble('nav').'='.valscramble(2)); ?>"+string,true);
		break;
	}
	xhttp.send();
	
}
function usermanage(action){
	var message='';
	var usrname=document.getElementById("username").value;
	var usrsurname=document.getElementById("usersurname").value;
	var usremail=document.getElementById("useremail").value;
	var usrpassword=document.getElementById("userpassword").value;
	var usrsecid=document.getElementById("usersecurity").value;
	var usruser=document.getElementById("useruser").value;
	if(usrname=='Name' || usrsurname=='Surname' || usremail=='Email' || usrpassword=='Password' || usrpassword=='' || usrsecid==0){
		if(usrname=='Name' || usrname==''){
			message+='The user\'s firstname cannot be blank!<br>';
		}
		if(usrsurname=='Surname' || usrsurname==''){
			message+='The user\'s surname cannot be blank!<br>';
		}
		if(usremail=='Email' || usremail==''){
			message+='The user\'s email cannot be blank!<br>';
		}
		if(usrpassword=='Password' || usrpassword==''){
			message+='The user\'s password cannot be blank!<br>';
		}
		if(usrsecid==0){
			message+='The user\'s security level cannot be blank!<br>';
		}
	document.getElementById('message').innerHTML='<h5 style="color:red;">'+message+'</h5>';
	}else{
		var string='&username='+usrname+'&usersurname='+usrsurname+'&useremail='+usremail+'&userpassword='+usrpassword+'&usersecurity='+usrsecid+'&user='+usruser;
		if(action=='Add'){
			//alert(string);
			ajax('addsysusersadd',string);
		}
		if(action=='Update'){
			//alert(string);
			ajax('updatesysusersupdate',string);
		}		
	}
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
function addaddiction(){
	var table=document.getElementById("sysmainaddictionstable");
	var row=table.insertRow(2);
	
	var deletecell=row.insertCell(-1);
	var deletecheck=document.createElement("input");
	deletecell.setAttribute("valign","top");
	deletecell.setAttribute("style","text-align:center;");
	deletecell.appendChild(deletecheck);
	deletecheck.setAttribute("type","checkbox");
	deletecheck.setAttribute("name","deleteaddiction");
	
	var name=row.insertCell(-1);
	name.setAttribute("valign","top");
	name.innerHTML=	'<input type="hidden" name="addictionid" value="0" />'+
					'<input type="text" name="addictionname" />';
	
	var abbr=row.insertCell(-1);
	abbr.setAttribute("valign","top");
	abbr.innerHTML=	'<input type="text" name="addictionabbr" />';
}
function sysaddictionupdate(){
	var x;
	var string;
	
	var deletes=new Array();
	var ids=new Array();
	var names=new Array();
	var abbrs=new Array();
	
	var addictiondeletes=document.getElementsByName("deleteaddiction");
	var addictionids=document.getElementsByName("addictionid");
	var addictionnames=document.getElementsByName("addictionname");
	var addictionabbrs=document.getElementsByName("addictionabbr");

	for(x=0;x<addictionids.length;x++){
		deletes.push(addictiondeletes[x].checked);
		ids.push(addictionids[x].value);
		names.push(addictionnames[x].value);
		abbrs.push(addictionabbrs[x].value);
	}
	string=	 '&deletes='+deletes.toString()
			+'&ids='+ids.toString()
			+'&names='+names.toString()
			+'&abbrs='+abbrs.toString();
	//alert(string);
	ajax('sysaddictionupdate',string);
}
function addoffence(){
	var table=document.getElementById("sysmainoffencestable");
	var row=table.insertRow(2);
	
	var deletecell=row.insertCell(-1);
	var deletecheck=document.createElement("input");
	deletecell.setAttribute("valign","top");
	deletecell.setAttribute("style","text-align:center;");
	deletecell.appendChild(deletecheck);
	deletecheck.setAttribute("type","checkbox");
	deletecheck.setAttribute("name","deleteoffence");
	
	var name=row.insertCell(-1);
	name.setAttribute("valign","top");
	name.innerHTML=	'<input type="hidden" name="offenceid" value="0" />'+
					'<input type="text" name="offenceoffence" />';
	
	var descr=row.insertCell(-1);
	descr.setAttribute("valign","top");
	var textarea=document.createElement("textarea");
	descr.appendChild(textarea);
	textarea.setAttribute("name","offencedescrs");
	textarea.setAttribute("style","resize:vertical;min-height:60px;width:298px;");
}
function sysoffenceupdate(){
	var x;
	var string;
	
	var deletes=new Array();
	var ids=new Array();
	var names=new Array();
	var descrs=new Array();
	
	var offencedeletes=document.getElementsByName("deleteoffence");
	var offenceids=document.getElementsByName("offenceid");
	var offencenames=document.getElementsByName("offenceoffence");
	var offencedescrs=document.getElementsByName("offencedescrs");

	for(x=0;x<offenceids.length;x++){
		deletes.push(offencedeletes[x].checked);
		ids.push(offenceids[x].value);
		names.push(offencenames[x].value);
		descrs.push(offencedescrs[x].value);
	}
	string=	 '&deletes='+deletes.toString()
			+'&ids='+ids.toString()
			+'&names='+names.toString()
			+'&descrs='+descrs.toString();
	//alert(string);
	ajax('sysoffenceupdate',string);
}
function adddepartment(){
	var table=document.getElementById("sysmaindepartmentstable");
	var row=table.insertRow(2);
	
	var deletecell=row.insertCell(-1);
	var deletecheck=document.createElement("input");
	deletecell.setAttribute("valign","top");
	deletecell.setAttribute("style","text-align:center;");
	deletecell.appendChild(deletecheck);
	deletecheck.setAttribute("type","checkbox");
	deletecheck.setAttribute("name","deletedepartment");
	
	var name=row.insertCell(-1);
	name.setAttribute("valign","top");
	name.innerHTML=	'<input type="hidden" name="deptid" value="0" />'+
					'<input type="text" name="deptname" />';
}
function sysdepartmentupdate(){
	var x;
	var string;
	
	var deletes=new Array();
	var ids=new Array();
	var names=new Array();
	
	var departmentdeletes=document.getElementsByName("deletedepartment");
	var departmentids=document.getElementsByName("deptid");
	var departmentnames=document.getElementsByName("deptname");

	for(x=0;x<departmentids.length;x++){
		deletes.push(departmentdeletes[x].checked);
		ids.push(departmentids[x].value);
		names.push(departmentnames[x].value);
	}
	string=	 '&deletes='+deletes.toString()
			+'&ids='+ids.toString()
			+'&names='+names.toString();
	//alert(string);
	ajax('sysdepartmentupdate',string);
}
</script>
</body>
</html>