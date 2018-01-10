<?php

$abc = array_flip(array_map(function($v){return $v+1;}, array_flip(range('a','z'))));


function turn($int) {
    ($int == 0)?exit('数值为0'):'';
    global $abc;
    $fl = floor($int/26);
    $mo = $int%26;
    if ($fl>0) {
        return(turn($fl).(($mo!=0)?$abc[$mo]:''));
    } else {
        return($abc[$mo]);
    }
    
}

echo turn(53)."<br/>";
echo turn(702)."<br/>";
echo turn(9999)."<br/>";
echo turn(29)."<br/>";

?>