<?php

declare(strict_types = 1);

if($_SERVER["REQUEST_METHOD"] === "POST"){
    require_once "db.php";
    session_start();
    session_unset();
    session_destroy();
    // header("location: index.php");
    // $_SESSION["signed_in"] = false;
    echo "<script>alert('successfully logged out..'); window.location.href = '/IAS-FDC-MTR/'</script>";
    exit();
} else {
    echo "<scripts>alert('Invalid request method.'); window.location.href = '/IAS-FDC-MTR/'</scripts>";
    die();
}
