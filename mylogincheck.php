<?php
	require_once('vmscFunctions.php');

	//Variables
	$message="";
	$navid=0;
	$userid=0;
	$activeid=0;
	$secid=0;
	
	userloginupdate();
	
//Check params and proceed
	if(isset($_POST['client']) && isset($_POST['email']) && isset($_POST['pass']) && isset($_POST['session'])){
		//get Keys
		
		$session=str_clean(valunscramble($_POST['session']));
		
		$query="select * from mykeys where KeyId=?";
		$types="i";
		$params=array($session);
		$result=query($types,$params,$query);

		while($row=$result->fetch_assoc()){
			$ServerPublicKey=$row['PublicKey'];
			$ServerSecretKey=$row['SecretKey'];
			$ServerKeyPair=$row['KeyPair'];
		}mysqli_free_result($result);

		// ClientPublicKeyHex to bin
		$ClientPublicKeyBin = hex2bin($_POST['client']);
		//Hex to Bin
		$nonceClientToServer_cipherTexClientPubToServerPrivBox1 = hex2bin($_POST['email']);
		$nonceClientToServer_cipherTexClientPubToServerPrivBox2 = hex2bin($_POST['pass']);
		//get nonce
		$nonce_asy1 = mb_substr($nonceClientToServer_cipherTexClientPubToServerPrivBox1, 0, 24, '8bit');
		$nonce_asy2 = mb_substr($nonceClientToServer_cipherTexClientPubToServerPrivBox2, 0, 24, '8bit');
		//get cypher
		$encrypted_asy1 = mb_substr($nonceClientToServer_cipherTexClientPubToServerPrivBox1, 24, null, '8bit');
		$encrypted_asy2 = mb_substr($nonceClientToServer_cipherTexClientPubToServerPrivBox2, 24, null, '8bit');
    
		//concatenate ServerSecretKey to ClientPublicKey
		$keyOpen = hex2bin($ServerSecretKey) . hex2bin($_POST['client']);
	
		//Open
		$email = sodium_crypto_box_open($encrypted_asy1,$nonce_asy1,$keyOpen);
		$password = sodium_crypto_box_open($encrypted_asy2,$nonce_asy2,$keyOpen);
		echo("<br>".$email."</br>");
		echo("<br>".$password."</br>");

		$sql='select UserId,count(UserId) as Chk
				from users 
			   where Email=? 
			     and Password=?';
		$types="ss";
		$chk=0;
		$params=array($email,$password);
		$result=query($types,$params,$sql);
		while($row=$result->fetch_assoc()){
			$chk=$row['Chk'];
			$userid=$row['UserId'];
		}mysqli_free_result($result);
    	echo('<br>$chk :'.$chk.' $userid :'.$userid);

	//Sys logs
		$array=array('Email',$email,'Password',$password);
		$tables=array('users');
		logs($userid,'r','logincheck',$array,$tables);

		if($chk>0){
			echo('<br>Chk: '.$chk);
			$date=Date('Y-m-d H:i:s');
        	echo('<br>Date :'.$date);

        	$query='insert into userlogin(UserId,LoggedIn,LoggedOut)values(?,now(),"0000-00-00");';
        	$types="i";
        	$params=array($userid);
        	$result=query($types,$params,$query);

	//Sys logs

			$query='select max(ActiveId) as ActiveId 
					  from userlogin 
					 where UserId=? 
					   and LoggedIn=?';
			$types='is';
			$params=array($userid,$date);
			$result=query($types,$params,$query);
			while($row=$result->fetch_assoc()){
				$activeid=$row['ActiveId'];
			}mysqli_free_result($result);

			echo('<br>ActiveId :'.$activeid);
			$array=array('userid',$userid,'LoggedIn',$date);
			$tables=array('userlogin');
			logs($userid,'c','logincheck',$array,$tables,$activeid);

			$sql='select SecId 
					from secusers 
				   where UserId=?';
			$type='i';
			$param=array($userid);
			$result=query($type,$param,$sql);
			
			while($row=$result->fetch_assoc()){
				$secid=$row['SecId'];
			}
			
			switch($secid){
				case 1:
					echo('<br>SecId: '.$secid.' Location:Administrator.php');
					//header('Location:Administrator.php?'.idscramble('userid').'='.valscramble($userid).
					////	'&'.idscramble('navid').'='.valscramble($navid).
					////	'&'.idscramble('secid').'='.valscramble($secid).' ');
				break;
				case 2:
					echo('<br>SecId: '.$secid.' Location:Counsellor.php');
					//header('Location:Counsellor.php?'.idscramble('userid').'='.valscramble($userid).
					////	'&'.idscramble('navid').'='.valscramble($navid).
					////	'&'.idscramble('secid').'='.valscramble($secid).' ');
				break;
				case 3:
					echo('<br>SecId: '.$secid.' Location:Manager.php');
					//header('Location:Manager.php?'.idscramble('userid').'='.valscramble($userid).
					////	'&'.idscramble('navid').'='.valscramble($navid).
					////	'&'.idscramble('secid').'='.valscramble($secid).' ');
				break;
				case 4:
					echo('<br>SecId: '.$secid.' Location:General.php');
					//header('Location:General.php?'.idscramble('userid').'='.valscramble($userid).
					////	'&'.idscramble('navid').'='.valscramble($navid).
					////	'&'.idscramble('secid').'='.valscramble($secid).' ');
				break;
				default:
				$message='Please contact the system administrator.<br>There\'s an issue with your account.';
				//header('Location:index.php?message='.$message);
			}
		}else{
			$message='Please make sure that your:<br>Email and Password are correct.';
			//header('Location:index.php?message='.$message);
		}
	}else{
		if(isset($email) && $email>""){
			$message.="<br>Email is set.";
		}else{
			$message.="<br>Email is blank.";
		}
		if(isset($password) && $password>""){
			$message.="<br>Password is set.";
		}else{
			$message.="<br>Password is blank.";
		}
		//header('Location:index.php?message='.$message);
	}

?>
