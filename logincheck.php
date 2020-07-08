<?php

	require_once('vmscFunctions.php');

	$email=str_clean($_POST['email']);
	$password=str_clean($_POST['password']);
	$message="";
	$navid=0;
	$userid=0;
	$activeid=0;
	$secid=0;
	
	//echo('Login Details: '.$email.' Pass: '.$password.'<br>');
	
	//userloginupdate();


	if(isset($email) && $email>"" && $password>"" && isset($password)){

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

		if($chk > 0){
	
			echo('<br>Chk: '.$chk);
			$date=Date('Y-m-d H:i:s');
        	echo('<br>Date :'.$date);

        	$query='insert into userlogin(ActiveId,UserId,LoggedIn,LoggedOut)values(0,?,now(),"0000-00-00");';
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
					header('Location:Administrator.php?'.idscramble('userid').'='.valscramble($userid).
						'&'.idscramble('navid').'='.valscramble($navid).
						'&'.idscramble('secid').'='.valscramble($secid).' ');
				break;
				case 2:
					echo('<br>SecId: '.$secid.' Location:Counsellor.php');
					header('Location:Counsellor.php?'.idscramble('userid').'='.valscramble($userid).
						'&'.idscramble('navid').'='.valscramble($navid).
						'&'.idscramble('secid').'='.valscramble($secid).' ');
				break;
				case 3:
					echo('<br>SecId: '.$secid.' Location:Manager.php');
					header('Location:Management.php?'.idscramble('userid').'='.valscramble($userid).
						'&'.idscramble('navid').'='.valscramble($navid).
						'&'.idscramble('secid').'='.valscramble($secid).' ');
				break;
				case 4:
					echo('<br>SecId: '.$secid.' Location:General.php');
					header('Location:General.php?'.idscramble('userid').'='.valscramble($userid).
						'&'.idscramble('navid').'='.valscramble($navid).
						'&'.idscramble('secid').'='.valscramble($secid).' ');
				break;
				default:
				$message='Please contact the system administrator.<br>There\'s an issue with your account.';
				header('Location:index.php?message='.$message);
			}
		}else{
			$message='Please make sure that your:<br>Email and Password are correct.';
			header('Location:index.php?message='.$message);
		}
	}
	else{
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
		header('Location:index.php?message='.$message);
	}
?>
