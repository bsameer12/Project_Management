<?php
session_start();
if(!isset($_SESSION["email"]) &! isset($_SESSION["accesstime"]))
{
    header("Location:../admin_signin.php");
}
?>
