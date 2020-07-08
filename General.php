<?php
    require_once('vmscFunctions.php');
    require_once('vmscBlockHTML.php');
    //SysParam & Auto Logout & Logging
    $userid=str_clean(valunscramble($_GET[idunscramble('userid')]));
    $secid=str_clean(valunscramble($_GET[idunscramble('secid')]));
    $navid=str_clean(valunscramble($_GET[idunscramble('navid')]));
   /// timer(20,$userid);
    //Sys logs
    $array=array('user',$userid);
    $tables=array('');
    logs($userid,'l','general',$array,$tables);
?>
<!DOCTYPE type="html">
<html lang="US">
<head>
    <link rel="shortcut icon" href="include/icons/logo.png" />
    <link rel="stylesheet" href="vmsstyle.css">
    <title>General</title>
    <script src="sorttable.js"></script>
</head>
<body>
<?php
    navhtml($userid,$secid,$navid);
    BlockHTML::tabs_layout_table(3,array('Search','Profile','Intake'));
    BlockHTML::content_layout_table($userid,6,'profileedit');
?>

    <script type="text/javascript">
        var something=0;
        tabstyle('tabbutton1','tr0','tr1');
        //ajax('personsearchbox');
        setTimeout(function(){
            window.location.href= 'logout.php?<?php echo(idscramble('userid').'='.valscramble($userid)); ?>';},1200000);
        refresh();
        setTimeout(function(){
             refresh();window.scrollTo(0,0);},3000);
    function tabstyle(el,A,B){
        //alert("tabstyle()");
        //Search tab
        var el1=document.getElementById('tabbutton1');
        el1.setAttribute("style","border-bottom:2px solid grey;background-color:#a6a6a6;color:#ffffff;border-radius:15px 15px 0px 0px;");
        var tr0=document.getElementById("tr0");
        var tr1=document.getElementById("tr1");
        if(typeof document.getElementById("editprofile") != "object"){
            var edit=document.getElementById("editprofile");
        }
        var edit=document.getElementById("editprofile");
        tr0.style.visibility="hidden";
        tr1.style.visibility="hidden";
        if(typeof edit != "object"){
            edit.style.visibility="hidden";
        }
        //Profile tab
        var el2=document.getElementById('tabbutton2');
        el2.setAttribute("style","border-bottom:2px solid grey;background-color:#a6a6a6;color:#ffffff;border-radius:15px 15px 0px 0px;");
        var tr2=document.getElementById("tr2");
        var tr3=document.getElementById("tr3");
        tr2.style.visibility="hidden";
        tr3.style.visibility="hidden";
        //Intake tab
        var el3=document.getElementById('tabbutton3');
        el3.setAttribute("style","border-bottom:2px solid grey;background-color:#a6a6a6;color:#ffffff;border-radius:15px 15px 0px 0px;");
        var tr4=document.getElementById("tr4");
        var tr5=document.getElementById("tr5");
        tr4.style.visibility="hidden";
        tr5.style.visibility="hidden";
        //Selected tab
        if(typeof edit != "object"){
            if(el == 'tabbutton2'){
                //alert(edit.id);
                edit.style.visibility="visible";
                edit.focus();
            }
        }
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
            ajax('personsearchbox');
        }
        if(document.getElementById("tr2").style.visibility=="visible"){
            //alert("tr2");
            var person="<?php echo('&'.idscramble('personid').'='); ?>"+document.getElementById("person").value;
            ajax('profile',person);
        }
        if(document.getElementById("tr4").style.visibility=="visible"){
            //alert("tr4");
            ajax('intakebox');
        }
    }
    function checkajax(id){
        //alert("in checkajax");
        if(id=='tabbutton1'){
            if(document.getElementById("ajaxresponse0").innerHTML==""){
                ajax('personsearchbox');
            //	ajax('personsearchlist');
            }
        }
        if(id=='tabbutton3'){
            if(document.getElementById("ajaxresponse4").innerHTML==""){
                ajax('intakebox');
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
            if(this.readyState==4 && this.status==200){
                switch(type){
					case 'personintakedocupdate':
						response='personintakedocs';
					break;
                    case 'personprofilemedicalupdate':
                        response="medical";
                    break;
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
                    case 'profileedit':
                        response='ajaxresponse2';
                    break;
                    case 'loadaddictionsR':
                        response='ajaxresponseR';
                    break;
                    case 'personprofileupdateinfo':
                        response='personalinfo';
                    break;
                    case 'personprofileentryexitupdate':
                        response='entryexit';
                    break;
                    case 'personprofilesickleaveupdate':
                        response='sickleave';
                    break;
                    case 'personprofileleaveupdate':
                        response='leave';
                    break;
                    case 'intakebox':
                        response='ajaxresponse4';
                    break;
                    case 'personintakesave':
                        response='ajaxresponse5';
                    break;
                    case 'personprofilespouseupdate':
                        response='spouse';
                    break;
                    case 'personprofilenodesupdate':
                        response='nodes';
                    break;
                    case 'personprofilequalificationupdate':
                        response='qualification';
                    break;
                    case 'personprofileemploymentupdate':
                        response='employment';
                    break;
                    case 'persondisciplinaryupdate':
                        response='disciplinary';
                    break;
                    case 'persondepartmentsupdate':
                        response='department';
                    break;
                    case 'personintakedocupdate':
                        response='personintakedocs';
                    break;
                    alert("Response = "+response);
                }
                //alert("In readystate\r"+this.responseText);
                document.getElementById(response).innerHTML=this.responseText;
                switch(type){
					case 'personsearchbox':
					
                    break;
                    case 'personsearchlist':
                        var newtableObject = document.getElementById("result");
                        sorttable.makeSortable(newtableObject);
                    break;
                    case 'loadaddictionsS':
                    break;
                    case 'profile':
                        //alert("window.scrollTo(0,0); "+response);
						window.scrollTo(0,0);
                    break;
                    case 'profileedit':
					
                    break;
                    case 'loadaddictionsR':
                    break;
                    case 'personprofileupdateinfo':
					
                    break;
                    case 'personprofileentryexitupdate':
                        folders('entryexitfolder','entryexitbutton');
                    break;
                    case 'personprofilesickleaveupdate':
                        folders('sickleavefolder','sickleavebutton');
                    break;
                    case 'personprofileleaveupdate':
                        folders('leavefolder','leavebutton');
                    break;
                    case 'intakebox':
                    break;
                    case 'personintakesave':
                        el=document.getElementById('tabbutton3');
                        setTimeout(function(){
                            refresh();window.scrollTo(0,0);},3000);
                    break;
                    case 'personprofilespouseupdate':
                        folders('spousefolder','spousebutton');
                    break;
                    case 'personprofilenodesupdate':
                        folders('nodesfolder','nodesbutton');
                    break;
                    case 'personprofilequalificationupdate':
                        folders('educationfolder','educationbutton');
                    break;
                    case 'personprofileemploymentupdate':
                        folders('employfolder','employbutton');
                    break;
                    case 'persondisciplinaryupdate':
                        folders('disciplinaryfolder','disciplinarybutton');
                    break;
                    case 'persondepartmentsupdate':
                        folders('departmentfolder','departmentbutton');
                    break;
                    case 'personintakedocupdate':
                        folders('personintakedocsfolder','personintakedocsbutton');
                    break;
					case 'personprofilemedicalupdate':
						folders('medicalfolder','medicalbutton');
					break;
                }
            }
        };
        //Gets the Search form.
        switch(type){
			case 'personintakedocupdate':
				xhttp.open("Get","PersonIntakeUpdate.php?<?php echo(idscramble('userid').'='.valscramble($userid).
                    '&'.idscramble('secid').'='.valscramble($secid).
                    '&'.idscramble('nav').'='.valscramble(1)); ?>"+string,true);
			break;
            case 'personprofilemedicalupdate':
                xhttp.open("Get","PersonProfileMedicalUpdate.php?<?php echo(idscramble('userid').'='.valscramble($userid).
                    '&'.idscramble('secid').'='.valscramble($secid).
                    '&'.idscramble('nav').'='.valscramble(1)); ?>"+string,true);
            break;
            case 'personprofileleaveupdate':
                xhttp.open("Get","PersonProfileLeaveUpdate.php?<?php echo(idscramble('userid').'='.valscramble($userid).
                    '&'.idscramble('secid').'='.valscramble($secid).
                    '&'.idscramble('nav').'='.valscramble(1)); ?>"+string,true);
            break;
            case 'personprofilesickleaveupdate':
                xhttp.open("Get","PersonProfileSickLeaveUpdate.php?<?php echo(idscramble('userid').'='.valscramble($userid).
                    '&'.idscramble('secid').'='.valscramble($secid).
                    '&'.idscramble('nav').'='.valscramble(1)); ?>"+string,true);
            break;
            case 'personprofileentryexitupdate':
                xhttp.open("Get","PersonProfileEntryExitUpdate.php?<?php echo(idscramble('userid').'='.valscramble($userid).
                    '&'.idscramble('secid').'='.valscramble($secid).
                    '&'.idscramble('nav').'='.valscramble(1)); ?>"+string,true);
            break;
            case 'personprofileupdateinfo':
                xhttp.open("Get","PersonProfileInfoUpdate.php?<?php echo(idscramble('userid').'='.valscramble($userid).
                    '&'.idscramble('secid').'='.valscramble($secid).
                    '&'.idscramble('nav').'='.valscramble(1)); ?>"+string,true);
            break;
            case 'profileedit':
                //alert('profile');
                var string='<?php echo('&'.valscramble('personid').'=');?>'
                    +document.getElementById('person').value
                    +'<?php echo('&'.idscramble('edit').'='.valscramble(1)); ?>';
                xhttp.open("Get","PersonProfileResult.php?<?php echo(idscramble('userid').'='.valscramble($userid).
                    '&'.idscramble('secid').'='.valscramble($secid).
                    '&'.idscramble('nav').'='.valscramble(1)); ?>"+string,true);
            break;
            case 'profile':
                //alert('profile ');
                xhttp.open("Get","PersonProfileResult.php?<?php echo(idscramble('userid').'='.valscramble($userid).
                    '&'.idscramble('secid').'='.valscramble($secid).
                    '&'.idscramble('nav').'='.valscramble(1)); ?>"+string,true);
            break;
            case 'loadaddictionsR':
                ////alert('loadaddictions');
                xhttp.open("Get","PersonSearchAddictionsList.php?<?php echo(idscramble('userid').'='.valscramble($userid).
                    '&'.idscramble('secid').'='.valscramble($secid).
                    '&'.idscramble('nav').'='.valscramble(1)); ?>"+string,true);
            break;
            case 'loadaddictionsS':
                ////alert('loadaddictions');
                xhttp.open("Get","PersonSearchAddictionsList.php?<?php echo(idscramble('userid').'='.valscramble($userid).
                    '&'.idscramble('secid').'='.valscramble($secid).
                    '&'.idscramble('nav').'='.valscramble(1)); ?>"+string,true);
            break;
            case 'personsearchlist':
                ////alert('personsearchlist');
                xhttp.open("GET","PersonSearchList.php?<?php echo(idscramble('userid').'='.valscramble($userid).
                    '&'.idscramble('secid').'='.valscramble($secid).
                    '&'.idscramble('nav').'='.valscramble(1)); ?>"+string,true);
            break;
            case 'personsearchbox':
                ////alert("In personsearchbox");
                xhttp.open("GET","PersonSearchForm.php?<?php echo(idscramble('userid').'='.valscramble($userid).
                    '&'.idscramble('secid').'='.valscramble($secid).
                    '&'.idscramble('nav').'='.valscramble(1)); ?>",true);
            break;
            case 'intakebox':
                xhttp.open("GET","PersonIntakeForm.php?<?php echo(idscramble('userid').'='.valscramble($userid).
                        '&'.idscramble('secid').'='.valscramble($secid).
                        '&'.idscramble('nav').'='.valscramble(1)); ?>",true);
            break;
            case 'personintakesave':
            /////alert('personintakesave');
                xhttp.open("GET","PersonIntakeSave.php?<?php echo(idscramble('userid').'='.valscramble($userid).
                        '&'.idscramble('secid').'='.valscramble($secid).
                        '&'.idscramble('nav').'='.valscramble(1)); ?>"+string,true);
            break;
            case 'personprofilespouseupdate':
                xhttp.open("GEhT","PersonSpouseUpdate.php?<?php echo(idscramble('userid').'='.valscramble($userid).
                        '&'.idscramble('secid').'='.valscramble($secid).
                        '&'.idscramble('nav').'='.valscramble(1)); ?>"+string,true);
            break;
            case 'personprofilenodesupdate':
                xhttp.open("GET","PersonProfileNodeUpdate.php?<?php echo(idscramble('userid').'='.valscramble($userid).
                        '&'.idscramble('secid').'='.valscramble($secid).
                        '&'.idscramble('nav').'='.valscramble(1)); ?>"+string,true);
            break;
            case 'personprofilequalificationupdate':
                xhttp.open("GET","PersonProfileQualificationUpdate.php?<?php echo(idscramble('userid').'='.valscramble($userid).
                        '&'.idscramble('secid').'='.valscramble($secid).
                        '&'.idscramble('nav').'='.valscramble(1)); ?>"+string,true);
            break;
            case 'personprofileemploymentupdate':
                xhttp.open("GET","PersonEmploymentUpdate.php?<?php echo(idscramble('userid').'='.valscramble($userid).
                        '&'.idscramble('secid').'='.valscramble($secid).
                        '&'.idscramble('nav').'='.valscramble(1)); ?>"+string,true);
            break;
            case 'persondisciplinaryupdate':
                xhttp.open("GET","PersonDisciplinaryUpdate.php?<?php echo(idscramble('userid').'='.valscramble($userid).
                        '&'.idscramble('secid').'='.valscramble($secid).
                        '&'.idscramble('nav').'='.valscramble(1)); ?>"+string,true);
            break;
            case 'persondepartmentsupdate':
                xhttp.open("GET","PersonDepartmentUpdate.php?<?php echo(idscramble('userid').'='.valscramble($userid).
                        '&'.idscramble('secid').'='.valscramble($secid).
                        '&'.idscramble('nav').'='.valscramble(1)); ?>"+string,true);
            break;
            case 'personintakedocupdate':
                xhttp.open("GET","PersonIntakeUpdate.php?<?php echo(idscramble('userid').'='.valscramble($userid).
                        '&'.idscramble('secid').'='.valscramble($secid).
                        '&'.idscramble('nav').'='.valscramble(1)); ?>"+string,true);
            break;
        }
        //alert("xhttp.send()");
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
        ////alert(el.value);
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
            var ajaxresponser=profiletable.insertRow(11);
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
            document.getElementById("profile").deleteRow(11);
        }
    }
    function getprofilepic(){
        var file=document.getElementById("pimage");
        var personid=document.getElementById('person').value;
        var image=file.files[0];
        ////alert(image.name);
        var xhttp;
        var data=new FormData();
        data.append('file',image);
        data.append('<?php echo(idscramble('userid')); ?>','<?php echo(valscramble($userid)); ?>');
        data.append('<?php echo(idscramble('secid')); ?>','<?php echo(valscramble($secid)); ?>');
        data.append('personid',personid);
        if(window.XMLHttpRequest){
            //IE7+, Firefox, Chrome, Opera, Safari
            xhttp = new XMLHttpRequest();
        }else{
            //IE6, IE5
            xhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xhttp.onreadystatechange=function(){
            if(this.readyState==4 && this.status==200){
                //alert("In readystate");
                document.getElementById("personimage").innerHTML=this.responseText;
            }
        };
        xhttp.open("POST","PersonProfileImageUpdate.php?",true);
        //xhttp.setRequestHeader("Content-type","multipart/form-data");
        xhttp.send(data);
    }
    function getdoctorsnote(id){
        var file=document.getElementById("psicknote"+id);
        var personid=document.getElementById('person').value;
        var doc=file.files[0];
        //alert(doc.name);
        var xhttp;
        var data=new FormData();
        data.append('file',doc);
        data.append('<?php echo(idscramble('sickid')); ?>',id);
        data.append('<?php echo(idscramble('userid')); ?>','<?php echo(valscramble($userid)); ?>');
        data.append('<?php echo(idscramble('secid')); ?>','<?php echo(valscramble($secid)); ?>');
        data.append('<?php echo(idscramble('personid')); ?>',personid);
        if(window.XMLHttpRequest){
            //IE7+, Firefox, Chrome, Opera, Safari
            xhttp = new XMLHttpRequest();
        }else{
            //IE6, IE5
            xhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xhttp.onreadystatechange=function(){
            if(this.readyState==4 && this.status==200){
                //alert("In readystate");
                document.getElementById("persondoctornote"+id).innerHTML=this.responseText;
                
            }
        };
        xhttp.open("POST","PersonProfileSickNoteUpdate.php?",true);
        //xhttp.setRequestHeader("Content-type","multipart/form-data");
        xhttp.send(data);
    }
    function addentryexit(){
        var table=document.getElementById("entryexitform");
        //alert(table.rows.length);
        var row=table.insertRow(2);
        
        var check=row.insertCell(-1);
        check.setAttribute("valign","top");
        check.setAttribute("style","text-align:center;width:50px;");
        var checkbox=document.createElement("input");
        check.appendChild(checkbox);
        checkbox.setAttribute("type","checkbox");
        checkbox.setAttribute("name","deleteentryexit");
        
        var entrydate=row.insertCell(-1);
        entrydate.setAttribute("valign","top");
        entrydate.innerHTML='<input type="hidden" name="pdateid" value="0" />'+
                            '<input type="date" id="pentrydate" name="pentrydate" />';
        
        var exitdate=row.insertCell(-1);
        exitdate.setAttribute("valign","top");
        exitdate.innerHTML='<input type="date" name="pexitdate" />';
        
        var days=row.insertCell(-1);
        days.setAttribute("valign","top");
        days.setAttribute("style","text-align:center;");
        days.innerHTML=0;
        
        var note=row.insertCell(-1);
        note.setAttribute("valign","top");
        note.innerHTML='<textarea style="resize:vertical;min-height:60px;width:773px;" name="pnote" placeholder="Please make a note here."></textarea>';

    }
    function addleave(){
        var table=document.getElementById("leaveform");
        //alert(table.rows.length);
        var row=table.insertRow(2);
        
        var check=row.insertCell(-1);
        check.setAttribute("valign","top");
        check.setAttribute("style","text-align:center;width:50px;");
        var checkbox=document.createElement("input");
        check.appendChild(checkbox);
        checkbox.setAttribute("type","checkbox");
        checkbox.setAttribute("name","deleteleave");
        
        var startdate=row.insertCell(-1);
        startdate.setAttribute("valign","top");
        startdate.innerHTML='<input type="hidden" name="lleaveid" value="0" />'+
                            '<input type="date" id="lstartdate" name="lstartdate" />';
        
        var finishdate=row.insertCell(-1);
        finishdate.setAttribute("valign","top");
        finishdate.innerHTML='<input type="date" name="lfinishdate" />';
        
        var days=row.insertCell(-1);
        days.setAttribute("valign","top");
        days.setAttribute("style","text-align:center;");
        days.innerHTML=0;
        
        var comment=row.insertCell(-1);
        comment.setAttribute("valign","top");
        comment.innerHTML='<textarea style="resize:vertical;min-height:60px;width:798px;" name="lcomment" placeholder="Please make a note here."></textarea>';
        
    }
    function addsickleave(){
        var xhttp;
        var response="";
        if(window.XMLHttpRequest){
            //IE7+, Firefox, Chrome, Opera, Safari
            xhttp = new XMLHttpRequest();
        }else{
            //IE6, IE5
            xhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        //alert(document.getElementById("person").value);
        var string='&personid='+document.getElementById("person").value+' ';
        xhttp.open("Get","PersonProfileSickLeaveAddRow.php?<?php echo(idscramble('userid').'='.valscramble($userid).
            '&'.idscramble('secid').'='.valscramble($secid).
            '&'.idscramble('nav').'='.valscramble(1)); ?>"+string,true);
        
        xhttp.send();
        xhttp.onreadystatechange=function(result){
            if(this.readyState==4 && this.status==200){
                response=this.responseText;
                var table=document.getElementById("sickleavetable");
                var row=table.insertRow(2);
                
                var check=row.insertCell(-1);
                check.setAttribute("valign","top");
                check.setAttribute("style","text-align:center;width:50px;");
                var checkbox=document.createElement("input");
                check.appendChild(checkbox);
                checkbox.setAttribute("type","checkbox");
                checkbox.setAttribute("name","psickdelete");
                
                var start=row.insertCell(-1);
                start.setAttribute("valign","top");
                start.innerHTML='<input type="hidden" id="psickid" name="psickid" value="'+response+'" />'+
                                '<input type="date" id="psickstart" name="psickstart" />';
                
                var finish=row.insertCell(-1);
                finish.setAttribute("valign","top");
                finish.innerHTML='<input type="date" id="psickfinish" name="psickfinish"/>';
                
                var days=row.insertCell(-1);
                days.setAttribute("valign","top");
                days.setAttribute("style","text-align:center;");
                days.innerHTML='0';
                
                var comment=row.insertCell(-1);
                comment.innerHTML='<textarea id="psickcomment" name="psickcomment" style="resize:vertical;min-height:60px;width:498px;"></textarea>';
                comment.setAttribute("valign","top");
                
                var doc=row.insertCell(-1);
                doc.setAttribute("valign","top");
                var div=document.createElement("div");
                doc.appendChild(div);
                div.setAttribute("id","persondoctornote"+response);
                div.setAttribute("name","persondoctornote"+response);
                var text=document.createTextNode("No attachment");
                div.appendChild(text);
                var br=document.createElement("br");
                div.appendChild(br);
                var input=document.createElement("input");
                div.appendChild(input);
                input.setAttribute("style","clear:left;");
                input.setAttribute("id","psicknote"+response);
                input.setAttribute("type","file");
                input.setAttribute("name","file");
                var button=document.createElement("button");
                div.appendChild(button);
                button.setAttribute("type","button");
                button.setAttribute("onclick","getdoctorsnote("+response+");");
                button.innerHTML='Upload';
            }
        }
    }
    function addspouse(){
        var table=document.getElementById("spousetable");
        var row=table.insertRow(2);
        //alert(table.rows.length+' '+table.id);
        
        var deletecell=row.insertCell(-1);
        var deletecheck=document.createElement("input");
        deletecell.setAttribute("valign","top");
        deletecell.setAttribute("style","text-align:center;width:50px;");
        deletecell.appendChild(deletecheck);
        deletecheck.setAttribute("type","checkbox");
        deletecheck.setAttribute("name","spousedelete");
        
        var name=row.insertCell(-1);
        name.setAttribute("valign","top");
        name.innerHTML=	'<input type="hidden" name="spouseid" value="0" />'+
                        '<input type="text" name="spousename" />';
        
        var bday=row.insertCell(-1);
        bday.setAttribute("valign","top");
        bday.innerHTML=	'Undetermined';
        
        var idno=row.insertCell(-1);
        idno.setAttribute("valign","top");
        idno.innerHTML=	'<input type="text" name="spouseidno" />';
        
        var onsystem=row.insertCell(-1);
        onsystem.setAttribute("valign","top");
        onsystem.setAttribute("width","100%");
        var select=document.createElement("select");
        onsystem.appendChild(select);
        select.setAttribute("name","spouseonsystem");
        var yes=document.createElement("option");
        yes.setAttribute("value",1);
        yes.innerHTML='Yes';
        var no=document.createElement("option");
        no.setAttribute("value",2);
        no.innerHTML='No';
        select.appendChild(yes);
        select.appendChild(no);

    }
    function addnode(){
        var table=document.getElementById("nodestable");
        var row=table.insertRow(2);
        
        var cell1=row.insertCell(-1);
        var nodedelete=document.createElement("input");
        cell1.appendChild(nodedelete);
        cell1.setAttribute("valign","top");
        cell1.setAttribute("style","text-align:center;width:50px;");
        nodedelete.setAttribute("type","checkbox");
        nodedelete.setAttribute("name","nodedelete");
        
        var cell2=row.insertCell(-1);
        cell2.setAttribute("valign","top");
        cell2.setAttribute("style","text-align:left;");
        cell2.innerHTML='<input type="hidden" name="nodeid" value="0" />'+
                        '<input type="text" name="nodename" />';
        
        var cell3=row.insertCell(-1);
        cell3.setAttribute("valign","top");
        cell3.setAttribute("style","text-align:left;");
        cell3.innerHTML='<input type="date" name="nodebday" />';

    }
    function addqualification(){
        var table=document.getElementById("educationtable");
        var row=table.insertRow(2);
        
        var cell1=row.insertCell(-1);
        cell1.setAttribute("style","text-align:center;width:50px;");
        cell1.setAttribute("valign","top");
        var check=document.createElement("input");
        check.setAttribute("type","checkbox");
        check.setAttribute("name","qualdelete");
        cell1.appendChild(check);
        var id=document.createElement("input");
        id.setAttribute("type","hidden");
        id.setAttribute("name","qualid");
        id.setAttribute("value",0);
        cell1.appendChild(id);
        
        var cell2=row.insertCell(-1);
        cell2.setAttribute("style","text-align:center;width:1150px;");
        var textarea=document.createElement("textarea");
        cell2.appendChild(textarea);
        textarea.setAttribute("name","qualification");
        textarea.setAttribute("style","resize:vertical;min-height:60px;width:1148px;");
        
    }
    function addemployer(){
        var table=document.getElementById("employtable");
        var row=table.insertRow(2);
        
        var cell1=row.insertCell(-1);
        cell1.setAttribute("style","text-align:center;");
        cell1.setAttribute("valign","top");
        var check=document.createElement("input");
        check.setAttribute("type","checkbox");
        check.setAttribute("name","employdelete");
        cell1.appendChild(check);
        var id=document.createElement("input");
        id.setAttribute("type","hidden");
        id.setAttribute("name","employid");
        id.setAttribute("value",0);
        cell1.appendChild(id);
        
        var cell2=row.insertCell(-1);
        cell2.setAttribute("style","text-align:center;");
        var employer=document.createElement("input");
        employer.setAttribute("type","text");
        employer.setAttribute("name","employer");
        cell2.appendChild(employer);
        
        var cell3=row.insertCell(-1);
        cell3.setAttribute("style","text-align:center;");
        var employname=document.createElement("input");
        employname.setAttribute("type","text");
        employname.setAttribute("name","employname");
        cell3.appendChild(employname);
        
        var cell4=row.insertCell(-1);
        cell4.setAttribute("stye","text-align:center;");
        var employcell=document.createElement("input");
        employcell.setAttribute("type","text");
        employcell.setAttribute("name","employcellno");
        cell4.appendChild(employcell);
        
    }
    function personupdate(){
        var name=document.getElementById("pname").value;
        var surname=document.getElementById("psurname").value;
        var id=document.getElementById("person").value;
        var idno=document.getElementById("pidno").value;
        var drivers=document.getElementById("drivers").value;
        var sassa=document.getElementById("sassa").value;
        var completed=document.getElementById("pcompleted").value;
        var addict=document.getElementById("paddict").value;
        var druglist=new Array();
        if(document.getElementById("pdruglist")){
            var selects=document.getElementById("pdruglist");
            for(var cnt=0;cnt<selects.options.length;cnt++){
                if(selects.options[cnt].selected){
                    druglist.push(selects.options[cnt].value);
                }
            }
        }
        var string= '&personid='+id
                   +'&name='+name
                   +'&surname='+surname
                   +'&idno='+idno
                   +'&drivers='+drivers
                   +'&sassa='+sassa
                   +'&addict='+addict
                   +'&completed='+completed
                   +'&druglist[]='+druglist+' ';
    //	//alert(string);
        ajax('personprofileupdateinfo',string);
    }
    function entryexitupdate(){
        var cnt;
        var string='';
        
        var sdeletes=new Array();
        var sids=new Array();
        var sentries=new Array();
        var sexits=new Array();
        var scomments=new Array();
        
        var id=document.getElementById("person").value;
        var deletes=document.getElementsByName("deleteentryexit");
        var ids=document.getElementsByName("pdateid");
        var entries=document.getElementsByName("pentrydate");
        var exits=document.getElementsByName("pexitdate");
        var comments=document.getElementsByName("pnote");
        
        for(cnt=0;cnt<ids.length;cnt++){
            sids.push(ids[cnt].value);
            sentries.push(entries[cnt].value);
            sexits.push(exits[cnt].value);
            scomments.push(comments[cnt].value);
            sdeletes.push(deletes[cnt].checked);
        }
        //alert(sids.toString()+' '+sentries.toString()+' '+sexits.toString()+' '+scomments.toString());
        var string=  '&personid='+id
                    +'&ids='+sids.toString()
                    +'&entries='+sentries.toString()
                    +'&exits='+sexits.toString()
                    +'&comments='+scomments.toString()
                    +'&deletes='+sdeletes.toString();
        //alert(string);
        ajax('personprofileentryexitupdate',string);
    }
    function leaveupdate(){
        var cnt;
        var string='';
        
        var ldeletes=new Array();
        var lids=new Array();
        var lstarts=new Array();
        var lfinishes=new Array();
        var lcomments=new Array();
        
        var id=document.getElementById("person").value;
        var deletes=document.getElementsByName("deleteleave");
        var ids=document.getElementsByName("lleaveid");
        var starts=document.getElementsByName("lstartdate");
        var finishes=document.getElementsByName("lfinishdate");
        var comments=document.getElementsByName("lcomment");
        
        for(cnt=0;cnt<ids.length;cnt++){
            lids.push(ids[cnt].value);
            lstarts.push(starts[cnt].value);
            lfinishes.push(finishes[cnt].value);
            lcomments.push(comments[cnt].value);
            ldeletes.push(deletes[cnt].checked);
        }
        //alert(sids.toString()+' '+sentries.toString()+' '+sexits.toString()+' '+scomments.toString());
        var string=  '&personid='+id
                    +'&ids='+lids.toString()
                    +'&entries='+lstarts.toString()
                    +'&exits='+lfinishes.toString()
                    +'&comments='+lcomments.toString()
                    +'&deletes='+ldeletes.toString();
        //alert(string);
        ajax('personprofileleaveupdate',string);
    }
    function sickleaveupdate(){
        var cnt;
        var string='';
        
        var files=new Array();
        var deletes=new Array();
        var ids=new Array();
        var starts=new Array();
        var finishes=new Array();
        var comments=new Array();
        
        var id=document.getElementById("person").value;
        var pfiles=document.getElementsByName("file");
        
        var psickdeletes=document.getElementsByName("psickdelete");
        var psickids=document.getElementsByName("psickid");
        var psickstarts=document.getElementsByName("psickstart");
        var psickfinish=document.getElementsByName("psickfinish");
        var psickcomment=document.getElementsByName("psickcomment");
        
        //alert(psickids.length);
        for(cnt=0;cnt<psickids.length;cnt++){
            //alert(psickdeletes[cnt].checked+'\r\n'+psickids[cnt].value+'\r\n'+psickstarts[cnt].value+'\r\n'+psickfinish[cnt].value+'\r\n'+psickcomment[cnt].value);
            deletes.push(psickdeletes[cnt].checked);
            ids.push(psickids[cnt].value);
            starts.push(psickstarts[cnt].value);
            finishes.push(psickfinish[cnt].value);
            comments.push(psickcomment[cnt].value);
            
            if(pfiles[cnt+1].files[0] != undefined){
                if(pfiles[cnt+1].files[0].name > ''){
        //			getdoctorsnote(psickids[cnt].value);
                }
            }
        }
        /*
        //alert('\n&personid='+id
            +'\n&deletes='+deletes.toString()
            +'\n&ids'+ids.toString()
            +'\n&starts'+starts.toString()
            +'\n&finishes'+finishes.toString()
            +'\n&comments'+comments.toString());
        */
        string=  '&personid='+id
                +'&deletes='+deletes.toString()
                +'&ids='+ids.toString()
                +'&starts='+starts.toString()
                +'&finishes='+finishes.toString()
                +'&comments='+comments.toString();
        ajax('personprofilesickleaveupdate',string);
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
    function personintakesave(){
        var firstname=document.getElementById("ifirstname").value;
        var surname=document.getElementById("isurname").value;
        var idno=document.getElementById("iidno").value;
        var drivers=document.getElementsByName("idrivers");
        var sassas=document.getElementsByName("isassa");
        var addictions=document.getElementsByName("iaddictions");
        var qualification=document.getElementById("iqualification").value;
        var employname=document.getElementById("iemployname").value;
        var employcontact=document.getElementById("iemploycontact").value;
        var employcontactperson=document.getElementById('iemploycontactperson').value;
        var statuslist=document.getElementsByName("istatus");
        var spousename=document.getElementById("ispousename").value;
        var spousesurname=document.getElementById("ispousesurname").value;
        var spouseidno=document.getElementById("ispouseidno").value;
        var spousekidsbdays=document.getElementsByName("ispousekidsbdays");
        var spousekidsnames=document.getElementsByName("ispousekidsnames");
        var emergencyname=document.getElementById("iemergencyname").value;
        var emergencynumber=document.getElementById("iemergencynumber").value;
        var emergencyaddress=document.getElementById("iemergencyaddress").value;
        var healthlist=document.getElementsByName("ihealth");
        var healthnote=document.getElementById("ihealthnote").value;
        var meds=document.getElementsByName("imeds");
        var medlist=document.getElementById("imedlist").value;
        
        var string;
        var addictionlist=new Array();
        var kidsbdaylist=new Array();
        var kidsnamelist=new Array();
        var status=0;
        var health=0;
        var med=0;
        var driver=0;
        var sassa=0;
        var x;
        for(x=0;x<addictions.length;x++){
            if(addictions[x].checked == true){
                addictionlist.push(addictions[x].value);
            }
        }
        for(x=0;x<spousekidsbdays.length;x++){
            kidsbdaylist[x]=spousekidsbdays[x].value;
        }
        for(x=0;x<spousekidsnames.length;x++){
            kidsnamelist.push(spousekidsnames[x].value);
        }
        for(x=0;x<statuslist.length;x++){
            if(statuslist[x].checked == true){
                status=x;
            }
        }
        for(x=0;x<healthlist.length;x++){
            if(healthlist[x].checked == true){
                health=x;
            }
        }
        for(x=0;x<meds.length;x++){
            if(meds[x].checked == true){
                med=x;
            }
        }
        for(x=0;x<sassas.length;x++){
            if(sassas[x].checked == true){
                sassa=x;
            }
        }
        for(x=0;x<drivers.length;x++){
            if(drivers[x].checked == true){
                driver=x;
                //alert("driver"+driver);
            }
        }
        
        string='&firstname='+firstname+
                '&surname='+surname+
                '&idno='+idno+
                '&driver='+driver+
                '&sassa='+sassa+
                '&addictionlist[]='+addictionlist+
                '&qualification='+qualification+
                '&employname='+employname+
                '&employcontact='+employcontact+
                '&employcontactperson='+employcontactperson+
                '&status='+status+
                '&spousename='+spousename+
                '&spousesurname='+spousesurname+
                '&spouseidno='+spouseidno+
                '&kidsbdaylist[]='+kidsbdaylist+
                '&kidsnamelist[]='+kidsnamelist+
                '&emergencyname='+emergencyname+
                '&emergencynumber='+emergencynumber+
                '&emergencyaddress='+emergencyaddress+
                '&health='+health+
                '&healthnote='+healthnote+
                '&med='+med+
                '&medlist='+medlist;
        //alert(string);
        ajax('personintakesave',string);
        
    }
    function spouseupdate(){
        var cnt;
        var string;
        
        var deletes=new Array();
        var ids=new Array();
        var names=new Array();
        var bdays=new Array();
        var idnos=new Array();
        var onsystems=new Array();
        
        var id=document.getElementById("person").value;
        var spousedeletes=document.getElementsByName("spousedelete");
        var spouseids=document.getElementsByName("spouseid");
        var spousenames=document.getElementsByName("spousename");
        var spouseidnos=document.getElementsByName("spouseidno");
        var spouseonsystems=document.getElementsByName("spouseonsystem");
        
        for(cnt=0;cnt<spousedeletes.length;cnt++){
            deletes.push(spousedeletes[cnt].checked);
            ids.push(spouseids[cnt].value);
            names.push(spousenames[cnt].value);
            idnos.push(spouseidnos[cnt].value);
            onsystems.push(spouseonsystems[cnt].value);
        }
        string=  '&personid='+id
                +'&deletes='+deletes.toString()
                +'&ids='+ids.toString()
                +'&names='+names.toString()
                +'&idnos='+idnos.toString()
                +'&onsystems='+onsystems.toString();
        //alert(string);
        ajax('personprofilespouseupdate',string);

    }
    function nodeupdate(){
        var x;
        var string;
        
        var deletes=new Array();
        var ids=new Array();
        var names=new Array();
        var bdays=new Array();
        
        var id=document.getElementById("person").value;
        var nodedeletes=document.getElementsByName("nodedelete");
        var nodeids=document.getElementsByName("nodeid");
        var nodenames=document.getElementsByName("nodename");
        var nodebdays=document.getElementsByName("nodebday");
        
        for(x=0;x<nodeids.length;x++){
            deletes.push(nodedeletes[x].checked);
            ids.push(nodeids[x].value);
            names.push(nodenames[x].value);
            bdays.push(nodebdays[x].value);
        }
        string=	 '&personid='+id
                +'&deletes='+deletes.toString()
                +'&ids='+ids.toString()
                +'&names='+names.toString()
                +'&bdays='+bdays.toString();
        //alert(string);
        ajax('personprofilenodesupdate',string);
        
    }
    function qualificationupdate(){
        var x;
        var string;
        
        var deletes=new Array();
        var ids=new Array();
        var names=new Array();
        
        var id=document.getElementById("person").value;
        var qualdeletes=document.getElementsByName("qualdelete");
        var qualids=document.getElementsByName("qualid");
        var qualifications=document.getElementsByName("qualification");
        
        for(x=0;x<qualids.length;x++){
            deletes.push(qualdeletes[x].checked);
            ids.push(qualids[x].value);
            names.push(qualifications[x].value);
        }
        string=	 '&personid='+id
                +'&deletes='+deletes.toString()
                +'&ids='+ids.toString()
                +'&names='+names.toString();
        //alert(string);
        ajax('personprofilequalificationupdate',string);
    }
    function employmentupdate(){
        var x;
        var string;
        
        var deletes=new Array();
        var ids=new Array();
        var employers=new Array();
        var names=new Array();
        var cellnos=new Array();
        
        var id=document.getElementById("person").value;
        var employdeletes=document.getElementsByName("employdelete");
        var employids=document.getElementsByName("employid");
        var employemployers=document.getElementsByName("employer");
        var employnames=document.getElementsByName("employname");
        var employcellnos=document.getElementsByName("employcellno");
        
        for(x=0;x<employids.length;x++){
            deletes.push(employdeletes[x].checked);
            ids.push(employids[x].value);
            employers.push(employemployers[x].value);
            names.push(employnames[x].value);
            cellnos.push(employcellnos[x].value);
        }
        string=	 '&personid='+id
                +'&deletes='+deletes.toString()
                +'&ids='+ids.toString()
                +'&employers='+employers.toString()
                +'&names='+names.toString()
                +'&cellnos='+cellnos.toString();
        //alert(string);
        ajax('personprofileemploymentupdate',string);
    }
    function adddisciplinary(){
        var xhttp;
        var response="";
        if(window.XMLHttpRequest){
            //IE7+, Firefox, Chrome, Opera, Safari
            xhttp = new XMLHttpRequest();
        }else{
            //IE6, IE5
            xhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        //alert(document.getElementById("person").value);
        var string='&personid='+document.getElementById("person").value+' ';
        xhttp.open("Get","PersonProfileDisciplinaryAddRow.php?<?php echo(idscramble('userid').'='.valscramble($userid).
            '&'.idscramble('secid').'='.valscramble($secid).
            '&'.idscramble('nav').'='.valscramble(1)); ?>"+string,true);
        
        xhttp.send();
        xhttp.onreadystatechange=function(result){
            if(this.readyState==4 && this.status==200){
                response=this.responseText;
                var table=document.getElementById("disciplinarytable");
                var row=table.insertRow(2);
                
                var check=row.insertCell(-1);
                check.setAttribute("valign","top");
                check.setAttribute("style","text-align:center;width:50px;");
                var checkbox=document.createElement("input");
                check.appendChild(checkbox);
                checkbox.setAttribute("type","checkbox");
                checkbox.setAttribute("name","disciplinarydelete");
                
                var offence=row.insertCell(-1);
                offence.setAttribute("valign","top");
                offence.innerHTML='<input type="hidden" id="discid" name="discid" value="'+response+'" />'+
                                '<select name="offence"><?php
                                    $query='select OffenceId as Id,Offence as Of from offences where OffenceId>?';
                                    $types='i';
                                    $params=array(0);
                                    $res=query($types,$params,$query);
                                    while($row=$res->fetch_assoc()){
                                        echo('<option value='.$row['Id'].' >'.$row['Of'].'</option>');
                                    }mysqli_free_result($res);
                                ?></select>';
                
                var offencedate=row.insertCell(-1);
                offencedate.setAttribute("valign","top");
                offencedate.innerHTML='<input type="date" id="disciplineoffencedate" name="disciplineoffencedate"/>';
                
                var capturedate=row.insertCell(-1);
                capturedate.setAttribute("valign","top");
                capturedate.setAttribute("style","text-align:center;");
                capturedate.innerHTML='<input type="date" id="disciplinecapturedate" name="disciplinecapturedate"/>';
                
                var discipline=row.insertCell(-1);
                discipline.innerHTML='<textarea id="disciplinetext" name="disciplinetext" style="resize:vertical;min-height:60px;width:298px;"></textarea>';
                discipline.setAttribute("valign","top");
                
                var restart=row.insertCell(-1);
                restart.innerHTML='<select name="restart"><option value=0>No Restart</option><option value=1>Restart</option></select>';
                restart.setAttribute("valign","top");
                
                var doc=row.insertCell(-1);
                doc.setAttribute("valign","top");
                var div=document.createElement("div");
                doc.appendChild(div);
                div.setAttribute("id","disciplinarydocdiv"+response);
                div.setAttribute("name","disciplinarydocdiv"+response);
                var text=document.createTextNode("No attachment");
                div.appendChild(text);
                var br=document.createElement("br");
                div.appendChild(br);
                var input=document.createElement("input");
                div.appendChild(input);
                input.setAttribute("style","clear:left;");
                input.setAttribute("id","disciplinarydoc"+response);
                input.setAttribute("type","file");
                input.setAttribute("name","file");
                var button=document.createElement("button");
                div.appendChild(button);
                button.setAttribute("type","button");
                button.setAttribute("onclick","persondisciplinedoc("+response+");");
                button.innerHTML='Upload';
            }
        }
    }
    function persondisciplinedoc(id){
        var file=document.getElementById("disciplinarydoc"+id);
        var personid=document.getElementById('person').value;
        //alert(file);
        var doc=file.files[0];
        //alert(doc.name);
        var xhttp;
        var data=new FormData();
        data.append('file',doc);
        //alert(id);
        data.append('<?php echo(idscramble('discid')); ?>',id);
        data.append('<?php echo(idscramble('userid')); ?>','<?php echo(valscramble($userid)); ?>');
        data.append('<?php echo(idscramble('secid')); ?>','<?php echo(valscramble($secid)); ?>');
        data.append('<?php echo(idscramble('personid')); ?>',personid);
        if(window.XMLHttpRequest){
            //IE7+, Firefox, Chrome, Opera, Safari
            xhttp = new XMLHttpRequest();
        }else{
            //IE6, IE5
            xhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xhttp.onreadystatechange=function(){
            if(this.readyState==4 && this.status==200){
                //alert("In readystate");
                document.getElementById("disciplinarydocdiv"+id).innerHTML=this.responseText;
            }
        };
        xhttp.open("POST","PersonDisciplinaryDocUpdate.php?",true);
        //xhttp.setRequestHeader("Content-type","multipart/form-data");
        xhttp.send(data);
    }
    function persondisciplineupdate(){
        var cnt;
        var string='';
        
        var files=new Array();
        var deletes=new Array();
        var ids=new Array();
        var offences=new Array();
        var offencedates=new Array();
        var capturedates=new Array();
        var disciplines=new Array();
        var restarts=new Array();
        
        var id=document.getElementById("person").value;
        var dfiles=document.getElementsByName("file");
        
        var discdeletes=document.getElementsByName("disciplinarydelete");
        var discids=document.getElementsByName("discid");
        var discoffence=document.getElementsByName("offence");
        var discoffencedate=document.getElementsByName("disciplineoffencedate");
        var disccapturedate=document.getElementsByName("disciplinecapturedate");
        var discdiscipline=document.getElementsByName("disciplinetext");
        var discrestart=document.getElementsByName("restart");
        
        //alert(discids.length);
        for(cnt=0;cnt<discids.length;cnt++){
            deletes.push(discdeletes[cnt].checked);
            ids.push(discids[cnt].value);
            offences.push(discoffence[cnt].value);
            offencedates.push(discoffencedate[cnt].value);
            capturedates.push(disccapturedate[cnt].value);
            disciplines.push(discdiscipline[cnt].value);
            restarts.push(discrestart[cnt].value);
            
            if(dfiles[cnt+1].files[0] != undefined){
                if(dfiles[cnt+1].files[0].name > ''){
            //		persondisciplinedoc(discids[cnt].value);
                }
            }
        }
        
        string=  '&personid='+id
            +'&deletes='+deletes.toString()
            +'&ids='+ids.toString()
            +'&offences='+offences.toString()
            +'&offencedates='+offencedates.toString()
            +'&capturedates='+capturedates.toString()
            +'&disciplines='+disciplines.toString()
            +'&restarts='+restarts.toString();
        //alert(string);
        ajax('persondisciplinaryupdate',string);
    }
    function adddepartment(){
        var table=document.getElementById("departmenttable");
        var row=table.insertRow(2);
        
        var cell1=row.insertCell(-1);
        var departmentdelete=document.createElement("input");
        cell1.setAttribute("style","text-align:center;width:50px;");
        cell1.innerHTML='<input type="checkbox" name="departmentdelete" />';
        
        var cell2=row.insertCell(-1);
        cell2.setAttribute("valign","top");
        cell2.setAttribute("style","text-align:center;");
        cell2.innerHTML='<input type="hidden" name="persondeptid" value="0" />'+
                        '<select name="department"><?php
                            $query='select DeptId, Name from department where DeptId>?';
                            $types='i';
                            $params=array(0);
                            $res=query($types,$params,$query);
                            while($row=$res->fetch_assoc()){
                                echo('<option value="'.$row['DeptId'].' ">'.$row['Name'].'</option>');
                            }mysqli_free_result($res);
                                                   ?></select>';
        
        var cell3=row.insertCell(-1);
        cell3.setAttribute("valign","top");
        cell3.setAttribute("style","text-align:center;");
        cell3.innerHTML='<input type="date" name="departmentstartdate" />';
        
        var cell4=row.insertCell(-1);
        cell4.setAttribute("valign","top");
        cell4.setAttribute("style","text-align:left;");
        cell4.innerHTML='<input type="date" name="departmentfinishdate" />';
    }
    function departmentupdate(){
        var x;
        var string;
        
        var deletes=new Array();
        var ids=new Array();
        var names=new Array();
        var starts=new Array();
        var finishes=new Array();
        
        var id=document.getElementById("person").value;
        var departmentdeletes=document.getElementsByName("departmentdelete");
        var persondeptids=document.getElementsByName("persondeptid");
        var departmentnames=document.getElementsByName("department");
        var departmentstarts=document.getElementsByName("departmentstartdate");
        var departmentfinishes=document.getElementsByName("departmentfinishdate");
        
        for(x=0;x<persondeptids.length;x++){
            deletes.push(departmentdeletes[x].checked);
            ids.push(persondeptids[x].value);
            names.push(departmentnames[x].value);
            starts.push(departmentstarts[x].value);
            finishes.push(departmentfinishes[x].value);
        }
        string=	 '&personid='+id
                +'&deletes='+deletes.toString()
                +'&ids='+ids.toString()
                +'&names='+names.toString()
                +'&starts='+starts.toString()
                +'&finishes='+finishes.toString();
        //alert(string);
        ajax('persondepartmentsupdate',string);
    }
    function addintakedocs(){
        var response=0;
        var table=document.getElementById("personintakedocstable");
        var row=table.insertRow(2);

        var check=row.insertCell(-1);
        check.setAttribute("valign","top");
        check.setAttribute("style","text-align:center;width:50px;");
        var checkbox=document.createElement("input");
        check.appendChild(checkbox);
        checkbox.setAttribute("type","checkbox");
        checkbox.setAttribute("name","deleteintakedoc");

        var uploadedon=row.insertCell(-1);
        uploadedon.setAttribute("valign","top");
        uploadedon.innerHTML='<input type="hidden" id="intakeid" name="intakeid" value="'+response+'" />'+
                             '<input type="date" id="intakeuploadedon" name="intakeuploadedon" />';

        var doc=row.insertCell(-1);
        doc.setAttribute("valign","top");
        var div=document.createElement("div");
        doc.appendChild(div);
        div.setAttribute("id","personintakedocument"+response);
        div.setAttribute("name","personintakedocument"+response);
        var text=document.createTextNode("No attachment");
        div.appendChild(text);
        var input=document.createElement("input");
        div.appendChild(input);
        input.setAttribute("style","clear:left;");
        input.setAttribute("id","personintakedoc"+response);
        input.setAttribute("type","file");
        input.setAttribute("name","file");
        var button=document.createElement("button");
        div.appendChild(button);
        button.setAttribute("type","button");
        button.setAttribute("onclick","getpersonintakedoc("+response+");");
        button.innerHTML='Upload';	
    }
    function getpersonintakedoc(id){
        var file=document.getElementById("personintakedoc"+id);
        var personid=document.getElementById('person').value;
        //alert(file);
        var doc=file.files[0];
        //alert(doc.name);
        var xhttp;
        var data=new FormData();
        data.append('file',doc);
        //alert(id);
        data.append('<?php echo(idscramble('intakeid')); ?>',id);
        data.append('<?php echo(idscramble('userid')); ?>','<?php echo(valscramble($userid)); ?>');
        data.append('<?php echo(idscramble('secid')); ?>','<?php echo(valscramble($secid)); ?>');
        data.append('<?php echo(idscramble('personid')); ?>',personid);
        if(window.XMLHttpRequest){
            //IE7+, Firefox, Chrome, Opera, Safari
            xhttp = new XMLHttpRequest();
        }else{
            //IE6, IE5
            xhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xhttp.onreadystatechange=function(){
            if(this.readyState==4 && this.status==200){
                //alert("In readystate");
                document.getElementById("personintakedocument"+id).innerHTML=this.responseText;
            }
        };
        xhttp.open("POST","PersonIntakeDocUpdate.php?",true);
        //xhttp.setRequestHeader("Content-type","multipart/form-data");
        xhttp.send(data);
    }
    function personintakedocupdate(){
        var x;
        var string;

        var deletes=new Array();
        var ids=new Array();
        var uploadedons=new Array();

        var id=document.getElementById("person").value;
        var intakedocsdeletes=document.getElementsByName("deleteintakedoc");
        var personintakeids=document.getElementsByName("intakeid");
        var intakeuploadedons=document.getElementsByName("intakeuploadedon");
		//alert(intakedocsdeletes.length);//alert(personintakeids.length);//alert(intakeuploadedons.length);
        for(x=0;x<personintakeids.length;x++){
            deletes.push(intakedocsdeletes[x].checked);
            ids.push(personintakeids[x].value);
            uploadedons.push(intakeuploadedons[x].value);
        }
        string=	 '&personid='+id
                +'&deletes='+deletes.toString()
                +'&ids='+ids.toString()
                +'&uploadedons='+uploadedons.toString();
        //alert(string);
        ajax('personintakedocupdate',string);

    }
	function addmedical(){
		table=document.getElementById("medicalform");
		row=table.insertRow(2);
		
		var deletemedical=row.insertCell(-1);
		deletemedical.setAttribute("style","text-align:center;width:50px;");
		var checkbox=document.createElement("input");
		deletemedical.appendChild(checkbox);
        checkbox.setAttribute("type","checkbox");
        checkbox.setAttribute("name","deletemedical");
		
		var phealth=row.insertCell(-1);
		phealth.setAttribute("style","text-align:left;width:150px;");
		phealth.innerHTML='<select name="phealth">'+
								'<option value="0" />Excellent</option>'+
								'<option value="1" />Good</option>'+
								'<option value="2" />Poor</option>'+
							'</select>';
		var hidden=document.createElement("input");
		phealth.appendChild(hidden);
		hidden.setAttribute("type","hidden");
		hidden.setAttribute("id","pmedid");
		hidden.setAttribute("name","pmedid");
		hidden.setAttribute("value","0");
		
		var pconditions=row.insertCell(-1);
		pconditions.setAttribute("style","width:200px;");
		var textarea1=document.createElement("textarea");
		pconditions.appendChild(textarea1);
		textarea1.setAttribute("style","resize:vertical;min-height:60px;width:198px;");
		textarea1.setAttribute("id","pconditions");
		textarea1.setAttribute("name","pconditions");
		
		var pmeds=row.insertCell(-1);
		pmeds.setAttribute("style","width:75px;text-align:left;");
		pmeds.innerHTML='<select name="pmeds">'+
							'<option value="0" />Yes</option>'+
							'<option value="1" />No</option>'+
						'</select>';
						
		var pmedications=row.insertCell(-1);
		pmedications.setAttribute("style","width:775px;");
		var textarea2=document.createElement("textarea");
		pmedications.appendChild(textarea2);
		textarea2.setAttribute("style","resize:vertical;min-height:60px;width:773px;");
		textarea2.setAttribute("id","pmedications");
		textarea2.setAttribute("name","pmedications");
		
	}
	function medicalupdate(){
		var x;
        var string;
        
        var deletes=new Array();
        var ids=new Array();
        var health=new Array();
        var conditions=new Array();
        var meds=new Array();
        var medications=new Array();
        
        var id=document.getElementById("person").value;
        var deletemedicals=document.getElementsByName("deletemedical");
        var pmedids=document.getElementsByName("pmedid");
        var phealths=document.getElementsByName("phealth");
        var pconditions=document.getElementsByName("pconditions");
        var pmeds=document.getElementsByName("pmeds");
        var pmedications=document.getElementsByName("pmedications");
        //alert(deletemedicals.length);
        for(x=0;x<pmedids.length;x++){
            deletes.push(deletemedicals[x].checked);
            ids.push(pmedids[x].value);
            health.push(phealths[x].value);
            conditions.push(pconditions[x].value);
			meds.push(pmeds[x].value);
			medications.push(pmedications[x].value);
		}
		
        string = '&personid='+id
                +'&deletes='+deletes.toString()
                +'&ids='+ids.toString()
                +'&health='+health.toString()
                +'&conditions='+conditions.toString()
                +'&meds='+meds.toString()
                +'&medications='+medications.toString();
       // //alert(string);
        ajax('personprofilemedicalupdate',string);
	}
</script>
</body>
</html>











