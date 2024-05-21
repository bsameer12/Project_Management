<?php
session_start();
if(isset($_SESSION["email"]) && isset($_SESSION["accesstime"]))
{
    session_destroy();
    header("Location:../admin_signin.php");
    exit();
}
else
{
    header("Location:../admin_signin.php");
    exit();
}
?>