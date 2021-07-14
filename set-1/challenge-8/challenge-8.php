<?php

$input = file(__DIR__ . "/input.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

$input = array_map("hex2bin", $input);

# "Remember that the problem with ECB is that it is stateless and deterministic; the same 16 byte plaintext block will always produce the same 16 byte ciphertext."
# Split each ciphertext into blocks with each having a max size/length of 16. From those blocks we will search for repeating values.

foreach ($input as $ciphertext) {

    for ($i = 1; $i < strlen($ciphertext); $i++) {

        $blocks = str_split($ciphertext, 16);

    }

    # Find repeating blocks by removing duplicate values from the blocks array and comparing the count of that to the count of the unmodified blocks array.
    # If the count doesn't match we found an ECB encrypted ciphertext.

    if (count($blocks) != count(array_unique($blocks))) {
        
        echo bin2hex($ciphertext) . "\n";

    }

    # Optionally we could do some sort of a scoring system e.g. based on the difference of $blocks and array_unique($blocks).

}
