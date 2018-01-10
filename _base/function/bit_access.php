<?php
function getstatus($status, $position)
{
    $t = $status & (1 << $position - 1) ? 1 : 0;
    return $t;
}

function setstatus($position, $value, $baseon = null)
{
    $t = 1 << $position - 1; //(算术运算优先位运算) 或者 = pow(2, $position - 1)
    if ($value) {
        $t = $baseon | $t;
    } elseif ($baseon !== null) {
        $t = $baseon & ~$t;
    } else {
        $t = ~$t;
    }
    return $t & 0xFFFF; //1.防溢出;2.对setstatus(2, 0)的方式初始化处理, 否则只返回11.  (0xFFFF=> 1111 1111 1111 1111)
}

$status = setstatus(2, 1); //设置初始值, 返回0000 0000 0000 0010;
$status = setstatus(2, 0); //设置初始值, 返回1111 1111 1111 1101;
//$status = setstatus(3, 0, $status); //修改值, 返回1111 1111 1111 1001

echo base_convert($status, 10, 2) . "\n";
echo getstatus($status, 4); //获取制定的权限值
