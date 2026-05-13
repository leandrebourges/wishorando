<?php
header('Content-Type: application/json; charset=utf-8');
$arr=array();
foreach (glob("mymaps/*.gpx") as $file) $arr[]=basename($file);
echo json_encode($arr);
?>