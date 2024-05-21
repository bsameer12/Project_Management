<?php
session_start();
if(!isset($_SESSION["email"]) &! isset($_SESSION["accesstime"]))
{
    header("Location:../admin_signin.php");
}
?>

<?php
session_start();

// Correct the logical operator to logical AND (&&)
// Correct the assignment operator to comparison operator (!=) for checking the role
if (!isset($_SESSION["email"]) || !isset($_SESSION["accesstime"]) || !isset($_SESSION["role"]) || $_SESSION["role"] != "admin") {
    header("Location:../admin_signin.php");;
    exit(); // Always exit after sending a header to prevent further code execution
}
?>
