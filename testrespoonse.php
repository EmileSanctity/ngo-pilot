<?php
    require_once('vmscFunctions.php');
    
    
    $ctext=$_GET['asd'];
    $n2h=$_GET['n2h'];
    $pk=$_GET['pk'];
    $userid=1;
    
    
    $reply='<h4>Boo</h4><p>ctext<br>'.$ctext.'</p><p>dsa<br>'.$n2h.'</p>';
    
    
    
    $query="select KeyPk,KeySk,Nonce
              from cryptokeys
             where UserId=?
               and KeyTime=(select max(KeyTime) from cryptokeys where UserId=?)";
    $types="ii";
    
    $params=array($userid,$userid);
    
    $result=query($types,$params,$query);
    
    while($row=$result->fetch_assoc()){
        $keypk=$row['KeyPk'];
        $keysk=$row['KeySk'];
        $nonce=$row['Nonce'];
    }mysqli_free_result($result);
    
    $jsnonce=sodium_hex2bin($n2h);
    $keydc=$keysk.$pk;
    
    
    $text=sodium_crypto_box_open($ctext,$jsnonce,$keydc);
    
    $reply.='<br>'.strlen($nonce).'<br>';
    $reply.='<br>'.strlen($jsnonce).'<br>';
    $reply.='<br>'.strlen($n2h).'<br>';
    
    $reply.='<br>'.sodium_bin2hex($keypk).'<br>'.sodium_bin2hex($keysk).'<br>'.sodium_bin2hex($nonce);
    
    echo($reply);
?>
