<?php
$end=microtime();
$end=explode(" ",$end);
$end=$end[1]+$end[0];

echo "<br>";
echo "<pre>";
printf("Completed in  : %f seconds. ",$end-$start);
echo "<br>";
echo "Using memory  : ", memory_get_usage(1), " bytes.";
echo "</pre>";
echo "<br>";
?>
