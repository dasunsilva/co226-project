<?php
    session_start();
    unset($_SESSION['UserName']);
    session_destroy();
    header("Location: index.php");
    exit;
?>