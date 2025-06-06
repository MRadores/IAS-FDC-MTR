<?php

$ports = ["3306", "3307", "3308", "3309"];
foreach ($ports as $port) {
    $dsn = "mysql:host=localhost;port=" . $port . ";dbname=fdc-mtr;";
    $username = "root";
    $pass = "";
    try {
        $conn = new PDO($dsn, $username, $pass);
        if ($conn) {
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            break;
        }
    echo $port;
    } catch (\PDOException $e) {
        // echo "Failed to connect on port $port: " . $e->getMessage() . "<br>";
        continue;
    }
}
