<?php

class Result {

    public $output;
    public $score;

}

$inputs = file(__DIR__ . "/challenge-4.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

$inputs = array_map("hex2bin", $inputs);

$results = [];

foreach ($inputs as $input) {
    
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
