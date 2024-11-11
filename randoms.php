<?php

$rnd1 = rand(1, 100);
$rnd2 = rand(1, 100);
$rnd3 = rand(1, 100);
$rnd4 = rand(1, 100);
echo $rnd1;
echo $rnd2;
echo $rnd3;
echo $rnd4;
echo "<hr>";
$rnd = "$rnd1"."$rnd2"."$rnd3" . "$rnd4";

echo $rnd;