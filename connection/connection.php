<?php 
$conn = oci_connect('HudderFoods', 'Root123#', '//localhost/xe'); 
if (!$conn) {
    $m = oci_error();
    echo $m['message'], "\n";
    exit; 
} 
?>