<?php

function unicode_decode($str) {
    return preg_replace_callback('/\\\\u([0-9a-fA-F]{4})/', function ($match) {
        return mb_convert_encoding(pack('H*', $match[1]), 'UTF-8', 'UTF-16BE');
    }, $str);
}

$json = file_get_contents(base_path('filtered-51k.json'));

// Decode the JSON data into a PHP array.
$data = json_decode($json, true);

// Open a CSV file.
$f = fopen('filtered.csv', 'w');

// Get the array keys and write them as CSV headers.
$firstLineKeys = array_keys($data[0]);
fputcsv($f, $firstLineKeys);

// Loop over the array and write each line to the CSV file.
foreach ($data as $line)
{
    // Loop through each item in the line to decode Unicode escape sequences and HTML entities.
    foreach ($line as $key => $value) {
        $line[$key] = html_entity_decode(unicode_decode($value));
    }

    fputcsv($f, $line);
}

// Close the CSV file.
fclose($f);
