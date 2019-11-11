<?php
session_start();
if(!isset($_SESSION['aliceKeypair'])){
    //generate key pair Alice pub+secret
    $_SESSION['aliceKeypair'] = sodium_crypto_box_keypair();
}
//get key secret Alice
$aliceSecretKey = sodium_crypto_box_secretkey($_SESSION['aliceKeypair']);
//get key public Alice
$alicePublicKey = sodium_crypto_box_publickey($_SESSION['aliceKeypair']);

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <div>
            <table border="1" style="width:70pc">
                <tbody>
                    <tr>
                        <th colspan="4">PHP SESSION - Alice</th>
                    </tr>
                    <tr>
                        <th>Public Key Alice Bytes</th>
                        <th>Public Key Alice HEX</th>
                        <th>Private Key Alice Bytes</th>
                        <th>Private Key Alice HEX</th>
                    </tr>
                    <tr>
                        <td>
                            <textarea rows="5" cols="40" id="publickeyalicebytes" readonly="true"><?=implode(',', unpack('C*', $alicePublicKey))?></textarea>
                        </td>
                        <td>
                            <textarea rows="5" cols="40" id="publickeyalicehex" readonly="true"><?=bin2hex($alicePublicKey)?></textarea>
                        </td>
                        <td>
                            <textarea rows="5" cols="40" id="privatekeyalicebytes" readonly="true"><?=implode(',', unpack('C*', $aliceSecretKey))?></textarea>
                        </td>
                        <td>
                            <textarea rows="5" cols="40" id="privatekeyalicehex" readonly="true"><?=bin2hex($aliceSecretKey)?></textarea>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div>
            <table border="1" style="width:70pc">
                <tbody>
                    <tr>
                        <th colspan="4">Javascript - Bob</th>
                    </tr>
                    <tr>
                        <th>Public Key Bob Bytes</th>
                        <th>Public Key Bob HEX</th>
                        <th>Private Key Bob Bytes</th>
                        <th>Private Key Bob HEX</th>
                    </tr>
                    <tr>
                        <td>
                            <textarea rows="5" cols="40" id="publickeybobbytes" readonly="true"></textarea>
                        </td>
                        <td>
                            <textarea rows="5" cols="40" id="publickeybobhex" readonly="true"></textarea>
                        </td>

                        <td>
                            <textarea rows="5" cols="40" id="privatekeybobbytes" readonly="true"></textarea>
                        </td>
                        <td>
                            <textarea rows="5" cols="40" id="privatekeybobhex" readonly="true"></textarea>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <div>
            <table border="1" style="width:70pc">
                <tbody>
                    <tr>
                        <th>Text clean</th>
                    </tr>
                    <tr>
                        <td>
                            <textarea rows="5" cols="87" id="text_clean"></textarea>
                        </td>
                    </tr>
                    <tr>
                        <th colspan="2"><input type="button" id="begin" value="Begin" disabled="disabled"/></th>
                    </tr>
                    <tr>
                        <th>Debug</th>
                    </tr>
                    <tr>
                        <td>
                            <textarea rows="20" cols="180" id="text_debug" readonly="true"></textarea>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </body>
    <script src="lib/jquery.js"></script>
    <script src="lib/nacl_factory.js"></script>
    <script>
    function ajax(type,string){
	//alert(type+' '+string);
	var xhttp;
	var response="";
	if(window.XMLHttpRequest){
		//IE7+, Firefox, Chrome, Opera, Safari
		xhttp = new XMLHttpRequest();
	}else{
		//IE6, IE5
		xhttp = new ActiveXObject("Microsoft.XMLHTTP");
	}
	xhttp.onreadystatechange=function(){
		if(this.readyState==4 && this.status==200){
				response="ajaxresponse0";
			}
			document.getElementById(response).innerHTML=this.responseText;
		}
	};
	
	xhttp.open("Get","ajaxexample.php?<?php echo(idscramble('userid').'='.valscramble($userid).
		'&'.idscramble('secid').'='.valscramble($secid).
		'&'.idscramble('nav').'='.valscramble(1)); ?>"+string,true);
		
	
	xhttp.send();
}   
        /*
         * Concat arrays
         *  
         * @param {type} a
         * @param {type} b
         * @returns {concatTypedArrays.c|concatTypedArrays.a.constructor}
         */
        function concatTypedArrays(a, b) { // a, b TypedArray of same type
            var c = new (a.constructor)(a.length + b.length);
            c.set(a, 0);
            c.set(b, a.length);
            return c;
        }
        
        /*
         * Add debug in textarea
         * 
         * @param {type} msg
         * @returns {undefined}
         */
        function addTextArea(msg){
            $('#text_debug').val(function(i, text) {
                return text + "\n" + msg;
            });
        }
        
        /*
         * Global variable for nacl
         * 
         * @type nacl
         */
        var nacl_global = null;
        
        jQuery(function ($) {
            $( "#begin" ).click(function() {
                $( "#begin" ).attr('disabled','disabled');//to not lose Bob's key and use it in PHP's return
                
                $("#text_debug").val("#################\n\tJavascript\n#################\n### encrypt ###\n");
                
                var message = $('#text_clean').val();
                
                //// 1 - key pub and secret bobJavascript - for each ajax, a different public and private key will be created
                var d1 = new Date();
                var bob_sk = nacl_global.crypto_box_keypair();
                var d2 = new Date();
                var time = d2 - d1;
                addTextArea(time + "ms : bob_sk \t\t\t\t\t\t\t\t\t\t\t=> bob_sk.boxPk(" + nacl_global.to_hex(bob_sk.boxPk) + ")");
                
                //// 2 - nonce box
                var d1 = new Date();
                var nonceBobToAlice = nacl_global.crypto_box_random_nonce();
                var d2 = new Date();
                var time = d2 - d1;
                addTextArea(time + "ms : nonceBobToAlice \t\t\t\t\t\t\t\t=> " + nacl_global.to_hex(nonceBobToAlice));
                
                //// 3 - use nonceBobToAlice, with public certificate from AlicePHP, with private BobJavascript for crypto_box hiding message text
                var cipherTexBobPubToAlicePrivBox = nacl_global.crypto_box(nacl_global.encode_utf8(message), nonceBobToAlice, nacl_global.from_hex('<?php echo bin2hex($alicePublicKey)?>'), bob_sk.boxSk);
                d2 = new Date();
                time = d2 - d1;
                //// 4 - concatenate the nonce with cipher
                var nonceBobToAlice_cipherTexBobPubToAlicePrivBox = concatTypedArrays(nonceBobToAlice, cipherTexBobPubToAlicePrivBox);
                addTextArea(time + "ms : nonceBobToAlice_cipherTexBobPubToAlicePrivBox \t=> " + nacl_global.to_hex(nonceBobToAlice_cipherTexBobPubToAlicePrivBox));

                //// 5 - only for testing, HTML should not know the secret key of AlicePHP
                var boxOpenTest = nacl_global.crypto_box_open(cipherTexBobPubToAlicePrivBox, nonceBobToAlice, bob_sk.boxPk, nacl_global.from_hex('<?php echo bin2hex($aliceSecretKey)?>'));
                addTextArea(time + "ms : boxOpen_TEST Text clean   \t\t\t\t\t\t=> " + nacl_global.decode_utf8(boxOpenTest));

                $.ajax({
                    method: "POST",
                    url: "/example.ajax.php",
                    data: { bobPublicKeyHex:nacl_global.to_hex(bob_sk.boxPk), nonceBobToAlice_cipherTexBobPubToAlicePrivBox: nacl_global.to_hex(nonceBobToAlice_cipherTexBobPubToAlicePrivBox) }
                })
                .done(function( msg ) {
                    $('#text_debug').val(function(i, text) {
                        return text + "\n\n#################\n\t\tPHP\n#################:\n" + msg.debug;
                    });
                    //ciphertext_crypto_box
                    addTextArea("#################\n\tJavascript\n#################\n### decrypt box ###");
                    var ciphertext_crypto_box = nacl_global.from_hex(msg.ciphertext_crypto_box);
                    d1 = new Date();
                    var nonce_box = ciphertext_crypto_box.slice(0, 24);
                    var encrypted_box = ciphertext_crypto_box.slice(24);
                    addTextArea("nonce_box \t=> " + nacl_global.to_hex(nonce_box));
                    addTextArea("encrypted_box \t=> " + nacl_global.to_hex(encrypted_box));
                    var decrypted = nacl_global.crypto_box_open(encrypted_box, nonce_box, nacl_global.from_hex('<?php echo bin2hex($alicePublicKey)?>'), bob_sk.boxSk);
                    d2 = new Date();
                    time = d2 - d1;
                    addTextArea(time + "ms : decrypted text box \t=> " + nacl_global.decode_utf8(decrypted));

                    //ciphertext_crypto_secretbox
                    addTextArea("\n### decrypt secretbox ###");
                    var ciphertext_crypto_secretbox = nacl_global.from_hex(msg.ciphertext_crypto_secretbox);
                    var nonce_secretbox = ciphertext_crypto_secretbox.slice(0, 24);
                    var encrypted_secretbox = ciphertext_crypto_secretbox.slice(24);
                    addTextArea("nonce_secretbox \t=> " + nacl_global.to_hex(nonce_secretbox));
                    d1 = new Date();
                    addTextArea("encrypted_secretbox \t=> " + nacl_global.to_hex(encrypted_secretbox));
                    var kA = nacl_global.crypto_scalarmult(bob_sk.boxSk, nacl_global.from_hex('<?php echo bin2hex($alicePublicKey)?>'));
                    d2 = new Date();
                    time = d2 - d1;
                    addTextArea(time + "ms : crypto_scalarmult(Key pair generation BobSecret and AlicePublic) \t=> " + nacl_global.decode_utf8(decrypted));
                    d1 = new Date();
                    var decrypted = nacl_global.crypto_secretbox_open(encrypted_secretbox, nonce_secretbox, kA);
                    d2 = new Date();
                    time = d2 - d1;
                    addTextArea(time + "ms : decrypted text secretbox \t=> " + nacl_global.decode_utf8(decrypted));
                    
                    $( "#begin" ).removeAttr('disabled');
                });
            });

            nacl_factory.instantiate(function (nacl) {
                nacl_global = nacl;//check if it can be done this way, I do not know the consequences of putting it in a global variable

                var bob_sk = nacl.crypto_box_keypair();

                $("#publickeybobbytes").val(bob_sk.boxPk);
                $("#publickeybobhex").val(nacl.to_hex(bob_sk.boxPk));
                $("#privatekeybobbytes").val(bob_sk.boxSk);
                $("#privatekeybobhex").val(nacl.to_hex(bob_sk.boxSk));
                
                $( "#begin" ).removeAttr('disabled');

            });
        });
    </script>
</html>