<?php

$input = file_get_contents("input.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

$key = "YELLOW SUBMARINE";

# The function openssl_decrypt() expects a base64 encoded string ny default so there is no need to separately decode it.

$output = openssl_decrypt($input, "AES-128-ECB", $key);

echo $output;
