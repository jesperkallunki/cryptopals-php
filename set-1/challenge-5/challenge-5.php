<?php

$input = "Burning 'em, if you ain't quick and nimble\nI go crazy when I hear a cymbal";

$key = "ICE";

$key = substr(str_repeat($key, strlen($input)), 0, strlen($input));

$output = $input ^ $key;

$output = bin2hex($output);

echo $output;
