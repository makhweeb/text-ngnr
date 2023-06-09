<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

function isJson($string) {
    json_decode($string);
    return json_last_error() === JSON_ERROR_NONE;
 }

Artisan::command('generate', function () {
    dump('Generating...');
    $filtered = file_get_contents('filtered-from-html-51k.json');

    dd(isJson($filtered));

    // foreach ($data as $key => $value) {
    //     $instruction = html_entity_decode($value['instruction']);
    //     $input = html_entity_decode($value['input']);
    //     $output = html_entity_decode($value['output']);

    //     $data[$key]['instruction'] = $instruction;
    //     $data[$key]['input'] = $input;
    //     $data[$key]['output'] = $output;
    // }

    // file_put_contents('filtered-from-html-51k.json', json_encode($data, JSON_PRETTY_PRINT));
});
