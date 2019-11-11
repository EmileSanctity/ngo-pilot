<?php

require_once('vmscFunctions.php');
$blah = sha1("hehe");
echo($blah);

$name="Emile";
$surname="De Wilde";
//Build link
$key = random_bytes(32);
$message = $name.$surname;

$nonce = random_bytes(24);
$cipher = sodium_crypto_secretbox($message, $nonce, $key);
$plain = sodium_crypto_secretbox_open($cipher, $nonce, $key);

$query = 'insert into passwdkeys(KeyId, RandBytes, Message, Cipher, Plain, Nonce, CreatedOn)values(null,?,?,?,?,?,now())';
$types="sssss";
$params=array(bin2hex($key), bin2hex($message), bin2hex($cipher), bin2hex($plain), bin2hex($nonce));
$result=query($types, $params, $query);

?>
<!DOCTYPE>
<html>
<head>
    <link rel="shortcut icon" href="include/icons/logo.png" />
    <link rel="shortcut icon" href="include/icons/logo.png" />
    <link rel="stylesheet" href="vmsstyle.css" />
    <title>VMS</title>
</head>
<body>
    <div style="text-align:center;width:600px;margin:0 auto;">
        <img src="include/icons/vistarus.png" width="400px" height="200px" />
        <br />
        <h3>Personnel Management System</h3>
    </div>
    <div style="text-align:center;width:600px;margin:0 auto;">

        <h4 style="font-size:20px">Please enter in your email address</h4>
        <form method="POST" action="forgotpasswdcheck.php">
            <table style="margin:0 auto;border:0px;">
                <tr>
                    <td colspan="2">
                        <?php
						if(isset($_GET['message'])){
							echo('<span style="color:red;">'.$_GET['message'].'</span>');
						}
                        ?>
                    </td>
                </tr>
                <tr>
                    <td style="text-align:right;">Email</td>
                    <td>
                        <input style="background-color:#e6e6e6;" type="text" name="email" placeholder="Email Address" />
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align:center;">
                        <input type="submit" value="Submit" />
                    </td>~
                </tr>
            </table>
        </form>
    </div>
</body>
</html>