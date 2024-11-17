<?php
session_start();
if(isset($_SESSION["email"]) && isset($_SESSION["accesstime"]))
{
    session_destroy();
    header("Location:../trader_signin.php");
    exit();
}
else
{
    header("Location:../trader_signin.php");
    exit();
}
?>