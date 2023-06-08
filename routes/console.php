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

Artisan::command('generate', function () {
    dump('Generating...');
    $filtered = file_get_contents('filtered-51k.json');

    $data = json_decode($filtered, true);

    foreach ($data as $key => $value) {
        $instruction = html_entity_decode($value['instruction']);
        $input = html_entity_decode($value['input']);
        $output = html_entity_decode($value['output']);

        $data[$key]['instruction'] = $instruction;
        $data[$key]['input'] = $input;
        $data[$key]['output'] = $output;
    }

    file_put_contents('filtered-from-html-51k.json', json_encode($data, JSON_PRETTY_PRINT));
});
