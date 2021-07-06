<?php

class Result {

    public $output;
    public $score;

}

$input = "1b37373331363f78151b7f2b783431333d78397828372d363c78373e783a393b3736";

$input = hex2bin($input);

for ($i = 0; $i < 256; $i++) {

    $output = $input ^ str_repeat(chr($i), strlen($input));

    $score = 0;

    $letters = range("a", "z");

    $characters = str_split($output, 1);

    foreach ($letters as $letter) {

        foreach ($characters as $character) {

            if ($character == $letter) {

                $score++;

            }
    
        }

    }

    $result = New Result;
    $result->output = $output;
    $result->score = $score;

    $results[] = $result;

}

$sort = array_column($results, "score");

array_multisort($sort, SORT_DESC, $results);

$j = 0;

foreach ($results as $result) {

    if ($j == 10) {

        break;

    }

    echo $result->output . "\n";

    $j++;

}
