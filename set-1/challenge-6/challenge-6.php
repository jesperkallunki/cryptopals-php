<?php

class Dist {

    public $keySize;
    public $value;

}

class Result {

    public $key;
    public $message;
    public $score;

}

# This function calculates the hamming distance in bits between two inputs.

function hamDist($a ,$b) {

    $dist = 0;

    $a = str_split($a);
    $b = str_split($b);

    for ($i = 0; $i < count($a); $i++) {

        if (isset($a[$i]) && isset($b[$i])) { # While this won't give errors if the inputs aren't of the same length this function doesn't work properly for such use cases.

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

$input = file_get_contents(__DIR__ . "/input.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

$input = base64_decode($input);

$results = [];

$keySizes = range(2, 40);

foreach ($keySizes as $keySize) {

    $blocks = str_split($input, $keySize);

    $dists = [];

    while ($blocks) {

        $dists[] = (hamDist(array_shift($blocks), array_shift($blocks))) / $keySize;

    }

    $dist = new Dist();
    $dist->keySize = $keySize;
    $dist->value =array_sum($dists) / count($dists);

    $results[] = $dist;

}

$sort = array_column($results, "value");

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

$key = null;

foreach ($transposedBlocks as $transposedBlock) {
 
    $results = [];

    # The total number of ASCII characters is 256 (0-255).
    # XOR the input againt each ASCII character.

    for ($i = 0; $i < 256; $i++) {
    
        $output = $transposedBlock ^ str_repeat(chr($i), strlen($transposedBlock)); # Convert the ASCII number to corresponding character and repeat until it matches the length of the ciphertext.
    
        # Create a scoring system for detecting outputs which make most sense.
    
        $score = 0;
    
        # http://cs.wellesley.edu/~fturbak/codman/letterfreq.html
        # This scoring system makes use of letter frequencies, diagrams and triagrams.
    
        $freqs = [
            0.08167, 0.01492, 0.02782, 0.04253, 0.12702, 0.02228, 0.02015,
            0.06094, 0.06966, 0.00153, 0.00772, 0.04025, 0.02406, 0.06749,
            0.07507, 0.01929, 0.00095, 0.05987, 0.06327, 0.09056, 0.02758,
            0.00978, 0.02360, 0.00150, 0.01974, 0.00074
        ];
    
        $diagr = [
            "th", "he", "in", "en", "nt", "re", "er", "an", "ti", "es", "on", 
            "at", "se", "nd", "or", "ar", "al", "te", "co", "de", "to", "ra", 
            "et", "ed", "it", "sa", "em", "ro"
        ];
    
        $triagr = [
            "the", "and", "tha", "ent", "ing", "ion", "tio", "for", "nde", "has", 
            "nce", "edt", "tis", "oft", "sth", "men" 
        ];
    
        foreach (range("a", "z") as $j => $letter) {
    
            foreach (str_split($output) as $char) {
    
                if ($char == $letter) {
        
                    $score += $freqs[$j];
    
                }
        
            }
    
        }
    
        foreach (str_split($output, 2) as $block) {
    
            if (in_array($block, $diagr)) {
        
                $score ++;
    
            }
    
        }
    
        foreach (str_split($output, 3) as $block) {
    
            if (in_array($block, $triagr)) {
        
                $score ++;
    
            }
    
        }
    
        $result = New Result;
        $result->key = chr($i);
        $result->message = $output;
        $result->score = $score;
    
        $results[] = $result;
    
    }

    # Sort the results by score and append the highest scoring result to the key.

    $sort = array_column($results, "score");

    array_multisort($sort, SORT_DESC, $results);

    $key .= $results[0]->key;

}

echo "Key: " . $key . "\n\n";

$key = substr(str_repeat($key, strlen($input)), 0, strlen($input));

$output = $input ^ $key;

echo $output;
