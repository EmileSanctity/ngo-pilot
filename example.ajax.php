<?php
session_start();
if(!isset($_SESSION['aliceKeypair'])){
    $_SESSION['aliceKeypair'] = \Sodium\crypto_box_keypair();
}
$aliceSecretKey = sodium_crypto_box_secretkey($_SESSION['aliceKeypair']);
$alicePublicKey = sodium_crypto_box_publickey($_SESSION['aliceKeypair']);

if(isset($_POST['bobPublicKeyHex']) && 
        isset($_POST['nonceBobToAlice_cipherTexBobPubToAlicePrivBox'])){
    header('Content-type: application/json');
    $text_debug = '### decrypt ###'."\n";
    ////hex BobPublicKeyHex to bynary
    $bobPublicKeyBin = hex2bin($_POST['bobPublicKeyHex']);
    ////hex nonceBobToAlice_cipherTexBobPubToAlicePrivBox to bynary
    $nonceBobToAlice_cipherTexBobPubToAlicePrivBox = hex2bin($_POST['nonceBobToAlice_cipherTexBobPubToAlicePrivBox']);
    ////get nouce
    $nonce_asy = mb_substr($nonceBobToAlice_cipherTexBobPubToAlicePrivBox, 0, 24, '8bit');
    ////get cypher
    $encrypted_asy = mb_substr($nonceBobToAlice_cipherTexBobPubToAlicePrivBox, 24, null, '8bit');
    
    ////concatenate AliceSecretKey to BobPublicKey
    $keyOpen = $aliceSecretKey . $bobPublicKeyBin;
    $start = microtime(true);
    ////descriptografar o assimetrico para obter o counce do simetrico(secretBox)
    $message = \Sodium\crypto_box_open( $encrypted_asy, $nonce_asy , $keyOpen );
    $time_elapsed_secs = microtime(true) - $start;
    $text_debug .= number_format($time_elapsed_secs,6)."s\t crypto_box_open \t\t\t(get message - X25519 + XSalsa20 + Poly1305 MAC)\n";
    $text_debug .= 'Text clean: '. $message."\n\n";
    
    ////
    ////Here's how to encrypt the answer, whether it's with BOX or with SECRETBOX
    ////

    //Text exemple Box and SecretBox To JavaScript
    $text_debug .= '### encrypt ###'."\n";
    
	
	//box
    $message = 'Hi there! :) in PHP crypto_box';
    $aliceToBob = $aliceSecretKey . $bobPublicKeyBin;
    $nonce_orig = openssl_random_pseudo_bytes(24); /* Never repeat this! */
    $start = microtime(true);
    $ciphertext_crypto_box = $nonce_orig . \Sodium\crypto_box($message, $nonce_orig, $aliceToBob);
    $time_elapsed_secs = microtime(true) - $start;
    $text_debug .= number_format($time_elapsed_secs,6).'s ciphertext_crypto_box'."\n";
    
    //secretbox
    $message = 'Hi there! :) in PHP crypto_secretbox';
    $nonce = openssl_random_pseudo_bytes(24); /* Never repeat this! */
    $start = microtime(true);
    $kA = \Sodium\crypto_scalarmult($aliceSecretKey, $bobPublicKeyBin);
    $time_elapsed_secs = microtime(true) - $start;
    $text_debug .= number_format($time_elapsed_secs,6).'s crypto_scalarmult(Key pair generation AliceSecret and BobPublic)'."\n";
    $start = microtime(true);
    $ciphertext_crypto_secretbox = $nonce . \Sodium\crypto_secretbox($message, $nonce, $kA);
    $time_elapsed_secs = microtime(true) - $start;
    $text_debug .= number_format($time_elapsed_secs,6).'s ciphertext_crypto_secretbox'."\n";
    $text_debug .= 'ciphertext_crypto_box: '. bin2hex($ciphertext_crypto_box) .'('. strlen(bin2hex($ciphertext_crypto_box)).')'."\n";
    $text_debug .= 'ciphertext_crypto_secretbox: '. bin2hex($ciphertext_crypto_secretbox) ."\n";
 
    $text_debug .= "\n".'$_POST(AJAX): '. json_encode($_POST)."\n\n";
    
    $return_arrayExample = [
        'ciphertext_crypto_box' => bin2hex($ciphertext_crypto_box),
        'ciphertext_crypto_secretbox' => bin2hex($ciphertext_crypto_secretbox),
        'debug' => '...',
    ];
    
    $text_debug .= "\n".'RETURN TO AJAX: '. json_encode($return_arrayExample)."\n\n";
    
    $return_array = [
        'ciphertext_crypto_box' => bin2hex($ciphertext_crypto_box),
        'ciphertext_crypto_secretbox' => bin2hex($ciphertext_crypto_secretbox),
        'debug' => $text_debug,
    ];
    
    echo json_encode($return_array);
    
    exit();
}