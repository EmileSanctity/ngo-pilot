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
    logs($userid,'l','PersonIntakeUpdate',$array,$tables);

    //App Params
   // echo("<pre>");var_dump($_GET);echo("</pre>");

    $reply='';
    $personid=str_clean(valunscramble($_GET[idunscramble('personid')]));
    $deletes=explode(",",str_clean($_GET['deletes']));
    $ids=explode(",",str_clean($_GET['ids']));
    $uploadedons=explode(",",str_clean($_GET['uploadedons']));


    //echo(	'<br>personid '.$personid.
    //		'<br>deletes '.implode(",",$deletes).
    //		'<br>ids '.implode(",",$ids).
    //		'<br>$uploadedons '.implode(",",$uploadedons).
    //		'<br>');


    foreach($ids as $x => $id){
        //echo('<br>'.$id.'<br>');
        if($uploadedons[$x]===''){
            $uploadedons[$x]=NULL;
        }
        if($deletes[$x] == 'false'){
            //echo('false');
            $query='update personintakedocs
				       set UploadedOn=case when ?=null then now() else ? end
				     where PersonId=?
				       and IntakeId=?';
            $types="ssii";
            $params=array($uploadedons[$x],$uploadedons[$x],$personid,$id);
            //echo(implode(",",$params));
            $result=query($types,$params,$query);
        }
        if($deletes[$x] == 'true'){
            //echo('true');
            $query='delete
				      from personintakedocs
				     where IntakeId=?
				       and PersonId=?';
            $types="ii";
            $params=array($ids[$x],$personid);
            $result=query($types,$params,$query);

            $query='select count(*) as Cnt
				      from personintakedocs
				     where IntakeId=?';
            $types="i";
            $params=array($ids[$x]);
            $result=query($types,$params,$query);
            while($row=$result->fetch_assoc()){
                $cnt=$row['Cnt'];
            }mysqli_free_result($result);

            if($cnt>0){
                $query='delete from personintakedocs where IntakeId=?';
                $types="i";
                $params=array($ids[$x]);
                $result=query($types,$params,$query);
            }
        }
    }
    $query=PersonProfileSql::get_person_intake_docs();
    $types='i';
    $params=array($personid);
    $result=query($types,$params,$query);

    $reply.='	            <table class="alternate"  width="1200px" style="margin: 0 auto;" id="personintakedocstable">
							    <thead>
							    <tr style="font-weight:bold;">
								    <th colspan="4" style="text-align:center;">
									    <div>
										    <span class="folder-button" onclick="folders(\'personintakedocsfolder\',\'personintakedocsbutton\');">Person Intake Documents</span>
										    <button id="personintakedocsbutton" class="folder-content" style="float:right;width:50px;" type="button" onclick="addintakedocs();">Add</button>
									    </div>
								    </th>
							    </tr>
							    <thead>
							    <tbody id="personintakedocsfolder" class="folder-content" style="padding: 5px auto;">
							    <tr style="font-weight:bold;">
								    <td style="border:1px solid #a6a6a6;text-align:center;vertical-align:middle;width:50px;">Delete</td>
								    <td style="border:1px solid #a6a6a6;text-align:center;vertical-align:middle;width:150px;">Uploaded On</td>
								    <td style="border:1px solid #a6a6a6;text-align:left;vertical-align:middle;width:100%">Document</td>
							    </tr>';
    while($row=$result->fetch_assoc()){
        $intakeid=$row['IntakeId'];
        $ext=$row['Ext'];
        $notename=$row['NoteName'];
        $uploadedon=$row['UploadedOn'];
        $reply.='				<tr style="text-align:center;">
								    <td style="text-align:center;width:50px;">
										<input type="checkbox" id="deleteintakedoc" name="deleteintakedoc" />
								    </td>
                                    <div id="personintake'.$intakeid.'">
									    <td>
										    <input type="hidden" id="intakeid" name="intakeid" value="'.$intakeid.'" />
										    <input type="date" id="intakeuploadedon" name="intakeuploadedon" value="'.$uploadedon.'" />
									    </td>
									    <td style="text-align:left;">
										    <a href="include/personintakedocs/'.$intakeid.'.'.$ext.'" >'.$notename.'</a>
                                            &nbsp;
											    <input style="clear:both;" id="personintakedoc'.$intakeid.'" type="file" class="fileinput" name="file">
											    <button type="button" onclick="getpersonintakedoc('.$intakeid.');">Upload</button>
									    </td>
                                    </div>
							    </tr>';
    }   
    $reply.='		            <tr>
					                <td colspan="3" style="text-align:center;">
						                <button type="button" onclick="personintakedocupdate();">Update</button>
					                </td>
				                </tr>
				                </tbody>
			                </table>';

    echo($reply);
?>