<?php
session_start();

// Correct the logical operator to logical AND (&&)
// Correct the assignment operator to comparison operator (!=) for checking the role
if (!isset($_SESSION["email"]) || !isset($_SESSION["accesstime"]) || !isset($_SESSION["role"]) || $_SESSION["role"] != "customer") {
    header("Location: customer_signin.php");
    exit(); // Always exit after sending a header to prevent further code execution
}
?>

