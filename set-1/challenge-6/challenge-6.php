<?php

$input = file_get_contents(__DIR__ . "/challenge-6.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

$input = base64_decode($input);

function calcDist($a, $b) {

    $a = str_split($a);
    $b = str_split($b);

    $dist = 0;
    
    for ($i = 0; $i < count($a); $i++) {

        if (isset($a[$i]) && isset($b[$i])) {

            $diff = ord($a[$i]) ^ ord($b[$i]);
    
            foreach (str_split(decbin($diff)) as $bit) {
        
                if ($bit) {
        
                    $dist++;
        
                }
        
            }

        }
    
    }
    
    return $dist;

}

class Result {

    public $keySize;
    public $dist;

}

$results = [];

$keySizes = range(2, 40);

foreach ($keySizes as $keySize) {

    $blocks = str_split($input, $keySize);

    $dists = [];

    while ($blocks) {

        $dists[] = (calcDist(array_shift($blocks), array_shift($blocks))) / $keySize;

    }

    $result = new Result();
    $result->keySize = $keySize;
    $result->dist =array_sum($dists) / count($dists);

    $results[] = $result;

}

$sort = array_column($results, "dist");

array_multisort($sort, SORT_ASC, $results);

$keySize = $results[0]->keySize;

$blocks = str_split($input, $keySize);

$transposedBlocks = [];

foreach ($blocks as $block) {

    $chars = str_split($block);

    foreach ($chars as $i => $char) {

        if (isset($transposedBlocks[$i])) {

            $transposedBlocks[$i] .= $char;

        } else {

            $transposedBlocks[] = $char;

        }
    
    }

}

class Output {

    public $char;
    public $score;

}

foreach ($transposedBlocks as $input) {
    
    $results = [];

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

        $result = New Output;
        $result->char = chr($i);
        $result->score = $score;
    
        $results[] = $result;

    }

    $sort = array_column($results, "score");

    array_multisort($sort, SORT_DESC, $results);

    echo $results[0]->char;

}
