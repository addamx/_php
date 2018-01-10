<?php

require "./vendor/autoload.php";

$data = ['name' => 'zhangshan', 'age' => 12];

$jsondata  = \phptestsoft\Json::encode($data);
$ejsondata = \phptestsoft\Json::decode($jsondata);
print_r($jsondata);
print_r($ejsondata);
