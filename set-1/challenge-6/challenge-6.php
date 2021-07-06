<?php

$input = file_get_contents(__DIR__ . "/challenge-6.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

$input = base64_decode($input);

$keySize = range(1, 50);

$a = "this is a test";
$b = "wokka wokka!!!";

if (strlen($a) === strlen($b)) {

    $dist = 0;

    for ($i = 0; $i < strlen($a); $i++) {

        $diff = ord($a[$i]) ^ ord($b[$i]);

        foreach (str_split(decbin($diff)) as $bit) {

            if ($bit == 1) {

                $dist++;

            }

        }

    }

    echo $dist;

}
