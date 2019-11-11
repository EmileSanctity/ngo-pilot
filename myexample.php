<?php
	require_once 'vmscFunctions.php';
    //generate key pair Alice pub+secret
    $AliceKeyPair = sodium_crypto_box_keypair();
	//get key secret Alice
	$AliceSecretKey = sodium_crypto_box_secretkey($AliceKeyPair);
	//get key public Alice
	$AlicePublicKey = sodium_crypto_box_publickey($AliceKeyPair);

	$query='insert into mykeys(KeyPair,SecretKey,PublicKey,Nonce)values(?,?,?,"")';
	$types="sss";
	$params=array(bin2hex($AliceKeyPair),bin2hex($AliceSecretKey),bin2hex($AlicePublicKey));
	$result=query($types,$params,$query);
	echo('<pre>');
	echo(implode("<Br>",$params));
	echo('</pre>');
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title></title>
</head>
<body>

<div id="msg">
<button type="button" onclick="ajax();">Do it</button>
</div>






</body>
	<script src="lib/functionIndex.js"></script>
<script src="lib/jquery.js"></script>
<script src="lib/nacl_factory.js"></script>
<script type="text/javascript">
	
function ajax(){
    	var nacl_global=null;
		nacl_factory.instantiate(function (nacl) {
			nacl_global = nacl;
		});
		var xhttp;
    	var response;
    	if(window.XMLHttpRequest){
    		xhttp=new XMLHttpRequest();
    	}else{
    		xhttp=new ActiveXObject("Microsoft.XMLHTTP");
    	}
    	xhttp.onreadystatechange=function(){
    		if(this.readyState==4 && this.status==200){
    			document.getElementById("msg").innerHTML=this.responseText;
    		}
    	};
    	var bob_sk=nacl_global.crypto_box_keypair();
    	var nonceBobToAlice=nacl_global.crypto_box_random_nonce();
    	var message='My message is safe';
    	var cipherTexBobPubToAlicePrivBox = nacl_global.crypto_box(nacl_global.encode_utf8(message), nonceBobToAlice, nacl_global.from_hex('<?php echo bin2hex($AlicePublicKey)?>'), bob_sk.boxSk);
    	var nonceBobToAlice_cipherTexBobPubToAlicePrivBox = concatTypedArrays(nonceBobToAlice, cipherTexBobPubToAlicePrivBox);
		
		xhttp.open("GET","myexampleresponse.php?bobPublicKeyHex="+nacl_global.to_hex(bob_sk.boxPk)+"&nonceBobToAlice_cipherTexBobPubToAlicePrivBox="+nacl_global.to_hex(nonceBobToAlice_cipherTexBobPubToAlicePrivBox),true);
    	xhttp.send();
		
    }


</script>