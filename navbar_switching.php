<?php
session_start();
function includeNavbarBasedOnSession() {
    if(isset($_SESSION["userid"]) && isset($_SESSION["accesstime"])) {
        include("session_navbar.php");
    } else {
        include("without_session_navbar.php");
    }
}

?>