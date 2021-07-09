<?php

class Result {

    public $key;
    public $message;
    public $score;

}

$input = "1b37373331363f78151b7f2b783431333d78397828372d363c78373e783a393b3736";

$input = hex2bin($input);

# The total number of ASCII characters is 256 (0-255).
# XOR the input againt each ASCII character.

for ($i = 0; $i < 256; $i++) {
    
    $output = $input ^ str_repeat(chr($i), strlen($input)); # Convert the ASCII number to corresponding character and repeat until it matches the length of the input.

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

# Sort the results by score and display some of the highest scoring results.

$sort = array_column($results, "score");

array_multisort($sort, SORT_DESC, $results);

echo "Key " .  $results[0]->key . ": " . $results[0]->message . "\n";
echo "Key " .  $results[1]->key . ": " . $results[1]->message . "\n";
echo "Key " .  $results[2]->key . ": " . $results[2]->message . "\n";
echo "Key " .  $results[3]->key . ": " . $results[3]->message . "\n";
echo "Key " .  $results[4]->key . ": " . $results[4]->message . "\n";
