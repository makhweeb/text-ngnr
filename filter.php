<?php

$json = file_get_contents(base_path('translated-51k.json'));

// Decode the JSON data into a PHP array.
$data = json_decode($json, true);

$new = collect($data)->filter(function($item) {
  $blacklist = ['python', 'html', '&&', '==', '===', '!=', '!==', 'select', '";', '```', 'sql'];

  foreach($blacklist as $black) {
    if(str($item['instruction'])->lower()->contains($black) || str($item['input'])->lower()->contains($black) || str($item['output'])->lower()->contains($black)) {
      return false;
    }
  }
  
  return true;
})->values();

file_put_contents(base_path('filtered-51k.json'), json_encode($new, JSON_PRETTY_PRINT));