<?php
	require_once 'vmscFunctions.php';
	$serverKeyPair = sodium_crypto_box_keypair();
	$serverPublicKey = sodium_crypto_box_publickey($serverKeyPair);
	$serverPrivateKey = sodium_crypto_box_secretkey($serverKeyPair);

	$query = 'insert into mykeys(KeyPair,SecretKey,PublicKey,Nonce)values(?,?,?,"")';
	$types = "sss";
	$params = array(bin2hex($serverKeyPair),bin2hex($serverPrivateKey),bin2hex($serverPublicKey));
	$result = query($types,$params,$query);

	$query="select max(KeyId) as KeyId from mykeys where KeyPair=? and SecretKey=? and PublicKey=?";
	$types="sss";
	$params = array(bin2hex($serverKeyPair),bin2hex($serverPrivateKey),bin2hex($serverPublicKey));
	$result = query($types,$params,$query);
	while($row=$result->fetch_assoc()){
		$id = $row["KeyId"];
	}mysqli_free_result($result);

	echo($id."<br>");
?>
<!DOCTYPE>
<html>
<head>
	<link rel="shortcut icon" href="include/icons/logo.png" />
    <link rel="stylesheet" href="vmsstyle.css">
	<script src="lib/nacl_factory.js"></script>
	<title>VMS</title>
</head>
<body>
    <div style="text-align:center;width:600px;margin:0 auto;">
    	<img src="include/icons/vistarus.png" width="400px" height="200px"/>
    	<br>
    	<h3>Personnel Management System</h3>
    </div>
    <div style="text-align:center;width:600px;margin:0 auto;">
    	
		<h4 style="font-size:20px">Please enter in your login details</h4>
    	<form method="POST" action="mylogincheck.php" id="login-form">
			<table style="margin:0 auto;border:0px;">
				<tr>
					<td colspan="2">
					<?php
						if(isset($_GET['message'])){
							echo('<span style="color:red;">'.str_clean($_GET['message']).'</span>');
						}
					?>
					</td>
				</tr>
				<tr>
					<td style="text-align:right;">Email</td>
					<td>
						<input type="hidden" name="session" value="<?php echo(valscramble($id)); ?>" />
						<input type="hidden" name="client" id="client" value="" />
						<input style="background-color:#e6e6e6;" type="text" id="email" name="email" placeholder="Email Address" />
					</td>
				</tr>
				<tr>
					<td style="text-align:right;">Password</td>
					<td>
						<input style="background-color:#e6e6e6;" type="password" id="pass" name="pass" placeholder="Password" />
					</td>
				</tr>
				<tr>
					<td colspan="2" style="text-align:center;">
						<button type="button" onclick="send();">Submit</button>
					</td>
				</tr>
			</table>
    	</form>
    </div>
<script type="text/javascript">
	function concatTypedArrays(a, b) { // a, b TypedArray of same type
    var c = new (a.constructor)(a.length + b.length);
    c.set(a, 0);
    c.set(b, a.length);
    return c;
}
function send() {

	let nacl_global=null;
	nacl_factory.instantiate(function (nacl) {
        nacl_global = nacl;
    });
    let name = document.getElementById("email").value;
    let pass = document.getElementById("pass").value;
	//alert(name+' ' +pass);
	let clientKeyPair = nacl_global.crypto_box_keypair();
	let nonceClientToServer = nacl_global.crypto_box_random_nonce();

	let cipherClientPubToServerPrivBox1 = nacl_global.crypto_box(nacl_global.encode_utf8(name), nonceClientToServer, nacl_global.from_hex("<?php echo bin2hex($serverPublicKey); ?>"),clientKeyPair.boxSk);
	let nonceClientToServer_cipherClientPubToServerPrivBox1 = concatTypedArrays(nonceClientToServer,cipherClientPubToServerPrivBox1);

	let cipherClientPubToServerPrivBox2 = nacl_global.crypto_box(nacl_global.encode_utf8(pass),nonceClientToServer,nacl_global.from_hex("<?php echo(bin2hex($serverPublicKey)); ?>"),clientKeyPair.boxSk);
	let nonceClientToServer_cipherClientPubToServerPrivBox2 = concatTypedArrays(nonceClientToServer,cipherClientPubToServerPrivBox2);

	document.getElementById("email").value = nacl_global.to_hex(nonceClientToServer_cipherClientPubToServerPrivBox1);
	document.getElementById("pass").value = nacl_global.to_hex(nonceClientToServer_cipherClientPubToServerPrivBox2);
	document.getElementById("client").value = nacl_global.to_hex(clientKeyPair.boxPk);
	alert(nacl_global.to_hex(nonceClientToServer_cipherClientPubToServerPrivBox1));
	alert(nacl_global.to_hex(nonceClientToServer_cipherClientPubToServerPrivBox2));
	alert(nacl_global.to_hex(clientKeyPair.boxPk));
	document.getElementById("login-form").submit();
}
</script>
</body>
</html>