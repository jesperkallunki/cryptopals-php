<?php

$a = "1c0111001f010100061a024b53535009181c";
$b = "686974207468652062756c6c277320657965";

$a = hex2bin($a);
$b = hex2bin($b);

if (strlen($a) === strlen($b)) {

    $a ^= $b;

    $a = bin2hex($a);

    echo $a;

}
