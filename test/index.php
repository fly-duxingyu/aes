<?php
require '../vendor/autoload.php';
$data= [
    'id'=>23,
    'name'=>'dsfsd',
    'sdsa'=>4324543
];
$data = json_encode($data);
$a = \Aes\Php\AesPhp::init('c2rFIU3ym8AXJ1aU')->encrypt($data);
echo $a;
echo '_______';
var_dump(\Aes\Php\AesPhp::init('c2rFIU3ym8AXJ1aU')->decrypt('eyJpdiI6Imt1dFFZMTRPUFNmaW93bXoiLCJ2YWx1ZSI6Ik44c0J4YVN1WnlmZVRNU3N5dTMwbW1LbyttVHczVW8zQ05UUkFUSG55ZmJhSDE0UXRIUDV5NmZod3RwMmZVOGkifQ=='));
