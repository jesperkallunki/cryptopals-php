<?php

class Result {

    public $char;
    public $output;
    public $score;

}

$input = "1b37373331363f78151b7f2b783431333d78397828372d363c78373e783a393b3736";

$input = hex2bin($input);

for ($i = 0; $i < 256; $i++) {

    $output = $input ^ str_repeat(chr($i), strlen($input));

    $score = 0;

    $letters = range("a", "z");

    $freqs = [
        0.08167, 0.01492, 0.02782, 0.04253, 0.12702, 0.02228, 0.02015,
        0.06094, 0.06966, 0.00153, 0.00772, 0.04025, 0.02406, 0.06749,
        0.07507, 0.01929, 0.00095, 0.05987, 0.06327, 0.09056, 0.02758,
        0.00978, 0.02360, 0.00150, 0.01974, 0.00074
    ];

    $characters = str_split($output, 1);

    foreach ($letters as $j => $letter) {

        foreach ($characters as $character) {

            if ($character == $letter) {
    
                $score += $freqs[$j];

            }
    
        }

    }

    $result = New Result;
    $result->char = chr($i);
    $result->output = $output;
    $result->score = $score;

    $results[] = $result;

}

$sort = array_column($results, "score");

array_multisort($sort, SORT_DESC, $results);

echo "Using " . $results[0]->char . " as key: " . $results[0]->output . "\n";
echo "Using " . $results[1]->char . " as key: " . $results[1]->output . "\n";
echo "Using " . $results[2]->char . " as key: " . $results[2]->output . "\n";
