<?php
    require_once('vmscFunctions.php');
//  echo(implode("<Br>",get_loaded_extensions()));
    
/*    echo(SODIUM_LIBRARY_VERSION );
    
    var_dump([
        SODIUM_LIBRARY_MAJOR_VERSION,
        SODIUM_LIBRARY_MINOR_VERSION,
        SODIUM_LIBRARY_VERSION
    ]);    
    $secret_key = sodium_crypto_secretbox_keygen();
    $message = 'Sensitive information';
    
    $nonce = random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
    $encrypted_message = sodium_crypto_secretbox($message, $nonce, $secret_key);
    
    $decrypted_message = sodium_crypto_secretbox_open($encrypted_message, $nonce, $secret_key);
    
    echo('<br>'.$secret_key);
    echo('<br>'.$encrypted_message);
    echo('<br>'.$decrypted_message);

//Symmetric Encryption
echo('<br><br>Symmetric Encryption');

    $msg="This is a message";
    $key=random_bytes(SODIUM_CRYPTO_SECRETBOX_KEYBYTES);//32
    $nonce=random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);//24
    $ciphertext=sodium_crypto_secretbox($msg,$nonce,$key);
    $plaintext=sodium_crypto_secretbox_open($ciphertext,$nonce,$key);
    echo('<br>');
    echo($plaintext);
    
//Symmetric Authentication
echo('<br><br>Symmetric Authentication');

$msg="Another message";
$key=random_bytes(SODIUM_CRYPTO_SECRETBOX_KEYBYTES);
$mac=sodium_crypto_auth($msg,$key);
echo('<br>');
echo(sodium_crypto_auth_verify($mac,$msg,$key)?"Yes":"No");
*/
    //Public Key Encryption
    echo('<br>Public Key Encryption');
    $aliceKeypair=sodium_crypto_box_keypair();
    $alicePublicKey=sodium_crypto_box_publickey($aliceKeypair);
    $aliceSecretKey=sodium_crypto_box_secretkey($aliceKeypair);
    $nonce = random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
	
	
    $userid=1;
    $query='insert into cryptokeys(KeyId,KeyTime,KeyPk,KeySk,Nonce,UserId)values(null,now(),?,?,?,?)';
    $types="sssi";
    $params=array($alicePublicKey,$aliceSecretKey,$nonce,$userid);
    $result=query($types,$params,$query);
    $alicePublicKeyHex=sodium_bin2hex($alicePublicKey);
?>
<!DOCTYPE type="html">
<html lang="US">
<head>
	<link rel="shortcut icon" href="include/icons/logo.png" />
    <link rel="stylesheet" href="vmsstyle.css">
	<title>test</title>
	<script src="lib/nacl_factory.js"></script>
</head>
<body>
	<h4>Sodium Javascript</h4>
	<button type="button" onclick="ajax();">Do it</button>
	<div id="response">
	</div>
    <script type="text/javascript">
    function ajax(){
    	var xhttp;
    	var response;
    	var akeypair;
    	if(window.XMLHttpRequest){
    		xhttp=new XMLHttpRequest();
    	}else{
    		xhttp=new ActiveXObject("Microsoft.XMLHTTP");
    	}
    	xhttp.onreadystatechange=function(){
    		if(this.readyState==4 && this.status==200){
    			document.getElementById("response").innerHTML=this.responseText;
    		}
    	};
    	var bpkeypair;
    	var msg='My message';
    	var nonce='';
    	var message='';
    	var ctext;
    	nacl_factory.instantiate(function(nacl1){
    		bpkeypair=nacl1.from_hex("<?php echo($alicePublicKeyHex); ?>");
    	});
   
    	alert("bpkeypair"+bpkeypair);
    	nacl_factory.instantiate(function(nacl){
    		akeypair=nacl.crypto_box_keypair();
    		message=nacl.encode_utf8(msg);
    		nonce=nacl.crypto_box_random_nonce();
    	//	ctext=nacl.crypto_box(nacl.encode_utf8(msg),nacl.crypto_box_random_nonce(),bpkeypair,nacl.crypto_box_keypair().boxSk);
    	});
    	alert('akeypair.boxPk'+akeypair.boxPk);
    	alert('message'+message);
    	alert('nonce'+nonce);
    	var n2h='';
    	nacl_factory.instantiate(function(acl){
    		ctext=acl.crypto_box(message,nonce,bpkeypair,akeypair.boxSk);
    		n2h=acl.to_hex(nonce);
    	});
    	alert("ctext"+ctext);
    	alert("n2h"+n2h);
    	alert("akeypair"+akeypair.boxPk);
    	xhttp.open("GET","testrespoonse.php?asd="+ctext+"&n2h="+n2h+"&pk="+akeypair.boxPk,true);
    	xhttp.send();
    }
    </script>
</body>
</html>



