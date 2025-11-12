<?php
    session_start();
    if (!isset($_SESSION['usuario']))
    {
        header("HTTP/1.1 404 Not Found");
        exit();
    }
    session_unset();
    session_destroy();
    header("Location: inicio.php");
?>