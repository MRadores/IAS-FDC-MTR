<?php

declare(strict_types = 1);

if($_SERVER["REQUEST_METHOD"] === "POST"){
    require_once "db.php";
    session_start();
    session_unset();
    session_destroy();
    header("location: index.php");
    // $_SESSION["signed_in"] = false;
} else {
    echo "<scripts>Alert('Invalid request method.')</scripts>";
}