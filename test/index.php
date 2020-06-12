<?php

use Aes\Php\AesPhp;

require '../vendor/autoload.php';
$data= [
    'id'=>23,
    'name'=>'dsfsd',
    'sdsa'=>4324543
];
$data = json_encode($data);
//$a = (new AesPhp('c2rFIU3ym8AXJ1aU'))->encrypt($data);
//echo $a;
//echo '|------------------------------------------------|';
var_dump((new AesPhp('c2rFIU3ym8AXJ1aU'))->decrypt('eyJpdiI6IlN0RUJhRDRHTmxMMFJkeHIiLCJ2YWx1ZSI6IlByRksvVmRCU2hEVFRnUW1udW1sZFU1Wk5hemdrVk0xcEZEdmF1NkVZY3c9In0='));
