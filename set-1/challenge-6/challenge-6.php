<?php

$input = file_get_contents(__DIR__ . "/input.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

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

$key = null;

foreach ($transposedBlocks as $input) {
    
    $results = [];

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
    
            foreach ($characters as $k => $character) {
    
                if ($character == $letter) {
    
                    $score += $freqs[$j];
    
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

    $key .= $results[0]->char;

}

echo "Key: $key\n\n";

$input = file_get_contents(__DIR__ . "/input.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

$input = base64_decode($input);

$key = substr(str_repeat($key, strlen($input)), 0, strlen($input));

$output = $input ^ $key;

echo $output;
