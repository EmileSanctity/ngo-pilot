<?php
	require_once('vmscFunctions.php');
$query="select * from mykeys where KeyId>?";
$types="i";
$params=array(0);
$result=query($types,$params,$query);

while($row=$result->fetch_assoc()){
	$AlicePublicKey=$row['PublicKey'];
	$AliceSecretKey=$row['SecretKey'];
	$AliceKeyPair=$row['KeyPair'];
	$nonce=$row['Nonce'];
}mysqli_free_result($result);

if(isset($_GET['bobPublicKeyHex']) && isset($_GET['nonceBobToAlice_cipherTexBobPubToAlicePrivBox'])){
	////hex BobPublicKeyHex to bynary
    $bobPublicKeyBin = hex2bin($_GET['bobPublicKeyHex']);
    ////hex nonceBobToAlice_cipherTexBobPubToAlicePrivBox to bynary
    $nonceBobToAlice_cipherTexBobPubToAlicePrivBox = hex2bin($_GET['nonceBobToAlice_cipherTexBobPubToAlicePrivBox']);
    ////get nouce
    $nonce_asy = mb_substr($nonceBobToAlice_cipherTexBobPubToAlicePrivBox, 0, 24, '8bit');
    ////get cypher
    $encrypted_asy = mb_substr($nonceBobToAlice_cipherTexBobPubToAlicePrivBox, 24, null, '8bit');
    
	////concatenate AliceSecretKey to BobPublicKey
    $keyOpen = hex2bin($AliceSecretKey) . $bobPublicKeyBin;
	
	$message = sodium_crypto_box_open($encrypted_asy,$nonce_asy,$keyOpen);
	echo("<textare>".$message."</textarea>");
}
?>