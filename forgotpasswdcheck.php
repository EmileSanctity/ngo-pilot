<?php

require_once('vmscFunctions.php');

$email=str_clean($_POST['email']);


	if(isset($email) && $email > ""){
        $sql='select UserId,count(UserId) as Chk
				from users
			   where Email=?';
		$types="s";
		$chk=0;
		$params=array($email);
		$result=query($types,$params,$sql);
		while($row=$result->fetch_assoc()){
			$chk=$row['Chk'];
			$userid=$row['UserId'];
		}mysqli_free_result($result);

        if($chk > 0){

        }else{
            $message='You entered an incorrect email address.';
            header('Location:forgotpasswd.php?message='.$message);
        }
    }


?>