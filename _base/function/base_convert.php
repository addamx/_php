<?php
/**
 * 仿base_convert()
 */
function tohex($num, $length = 8)
{

    $arr = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'A', 'B', 'C', 'D', 'E', 'F');
    $tmp = array();

    $base = 0xF; // 15, 1111

    //每次收集4位数
    do {
        $i     = $num & $base; //收集
        $tmp[] = $arr[$i];
        $num >>= 4; //=> 除以2^4
    } while ($num);

    $res = implode('', array_reverse($tmp)); // 得到16进制
    $res = str_pad($res, $length, '0', STR_PAD_LEFT); // 补齐到$length位
    return $res;
}

echo tohex(255, 8); // 000000FF
