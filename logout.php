<?php
session_start();
if (isset($_SESSION["loggedin"])){
    session_destroy();
}
header("location: /login.php");
?>