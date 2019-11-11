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
	var nacl_global=null;
	nacl_factory.instantiate(function (nacl) {
        nacl_global = nacl;
        var bob_sk = nacl.crypto_box_keypair();
    });
function ajax(){
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
		
		 nacl_factory.instantiate(function (nacl) {
                nacl_global = nacl;
                var bob_sk = nacl.crypto_box_keypair();
            });
    }