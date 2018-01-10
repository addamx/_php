<?php 
Header('Content-type: image/png;Charset:utf-8'); //声明图片

$im = imagecreate(400,200);

//get color.
$bg = imagecolorallocate($im,0,0,0);
$red = imagecolorallocate($im,255,0,255);
$white = imagecolorallocate($im,255,255,255);

$arrowX = array(394,97,399,100,394,103);
$arrowY = array(197,5,200,0,203,5);               

//画曲线
for($i=0;$i<380;$i+=0.1){
    $x = $i/20;
    $y = sin($x);
    $y = 100 + 40*$y;
    imagesetpixel($im,$i+10,$y,$red);
}

//画X轴和Y轴
imageline($im,0,100,394,100,$white);
imageline($im,200,5,200,200,$white);

//画坐标title
imagestring($im,4,350,110,'XShaft',$white);

//画箭头
imagefilledpolygon($im,$arrowX,3,$white);
imagefilledpolygon($im,$arrowY,3,$white);

header('content-type: image/jpeg');
imagepng($im);
imagedestroy($im);



?>