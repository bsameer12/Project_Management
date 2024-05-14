<?php
session_start();
if(isset($_SESSION["email"]) && isset($_SESSION["accesstime"]))
{
    session_destroy();
    header("Location:../index.php");
    exit();
}
else
{
    header("Location:../index.php");
    exit();
}
?>