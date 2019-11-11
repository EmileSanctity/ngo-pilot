<?php
	
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';


	//echo('<br><br><br>');
//systemsize
	$path = '.';
	$linescnt=-2;
	$charcnt=0;
		$reply='	<table style="border:2px solid grey;">
						<tr style="font-size:23px;font-weight:bold;border:1px solid grey;">
							<td >Filename</td>
							<td >Lines</td>
							<td >Characters</td>
						</tr>';
	$files = scandir($path);
	foreach ($files as &$file) {
	    if(strlen($file)>2 && $file != 'include' && $file != 'systemsize.php' && $file != '.buildpath' && $file != '.project'){
				$lines=count(file($file));
				$string=file_get_contents($file);
				$linescnt+=$lines;
				$chars=strlen($string);
				$charcnt+=$chars;
				$reply.='<tr style="font-size:16px;font-weight:normal;border:1px solid grey;">
							<td style="border:1px solid grey;">'.$file.'</td>
							<td style="border:1px solid grey;">'.$lines.'</td>
							<td style="border:1px solid grey;">'.$chars.'</td>
						</tr>';
			}
	}
	$reply.='			<tr>
							<td style="border:1px solid grey;font-weight:bold">TOTALS</td>
							<td style="border:1px solid grey;">'.$linescnt.'</td>
							<td style="border:1px solid grey;">'.$charcnt.'</td>
						</tr>
					</table>';
	$mydate='2000-01-01';
	$res=(time()-strtotime($mydate))/60/60/24/365;
	//echo($res);
	echo('<span style="font-weight:bold;">Date :'.date('Y-m-d').'</span>');
	echo($reply);
	
	//// 
 
  
	$mail = new PHPMailer(true); 
try {
    //Server settings
    $mail->SMTPDebug = 2;
    $mail->isSMTP();
    $mail->Host = 'smtp.vistarus.co.za';
    $mail->SMTPAuth = true;
    $mail->Username = 'it@vistarus.co.za';
    $mail->Password = 'cV3#JxCt49a';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;
 	$mail->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )
    );
 
    $mail->setFrom('it@vistarus.co.za', 'Admin');
    $mail->addAddress('emiledewilde2@gmail.com', 'Recipient1');
 
    //Attachments
   // $mail->addAttachment('/backup/myfile.tar.gz');
 
    //Content
    $mail->isHTML(true); 
    $mail->Subject = 'Test Mail Subject!';
    $mail->Body    = 'This is SMTP Email Test';
 
    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo 'Message could not be sent.';
    echo 'Mailer Error: ' . $mail->ErrorInfo;
}

/*
	$druglist='1,2,3,4,5,6,7,10';
	$query='select ';
			$list=explode(",",$druglist);
			for($x=0;$x<sizeof($list);$x++){
				$query.='sum(case when PA.AddictionId='.$list[$x].' then 1 else 0 end) as Num'.$list[$x].' ';
				if(sizeof($list)!=$x+1){
					$query.=',';
				}
			}
	$query.='from personaddictions PA
			  left join (select PersonId,max(EntryDate) AS EDate
						   from personentryexit
						  where Date(EntryDate) between Date(?) and Date(?)
						  group by PersonId) X
				on PA.PersonId=X.PersonId 
			 where AddictionId in (?)
			   and Date(X.EDate) between Date(?) and Date(?)';
	echo("<pre>");
	echo($query);
	echo("</pre>");
	$types="sssss";
	echo("<br>".$druglist."<br>");
	$params=array("2000-01-01","2019-01-01",$druglist,"2000-01-01","2019-01-01");
	$result=query($types,$params,$query);
	$fields=mysqli_fetch_fields($result);

	echo("<br>Fields: ".$fields);
	echo("<pre>");
	var_dump($fields);
	echo("</pre>");
	
	echo(sizeof($fields));
	foreach($fields as $key){
		echo("<br>".$key->name);
	}
	$x=0;
	while($row=$result->fetch_assoc()){
		$ans="[";
		foreach($fields as $key){
			$ans.=$row[$key->name];
			if($x<sizeof($fields)){
				$ans.=",";
			}
			$x++;
		}
		$ans.="]";
	}
	echo("<br>".$res);

	echo("<br>".$ans);
*/
?>