<?php

$host = 'localhost';
$db   = 'vulnerable-app';
$user = 'root';   
$pass = '';
$charset = 'utf8mb4';

$mysqli = mysqli_connect($host, $user, $pass, $db);

if (!$mysqli) {
    die("DB connection failed: " . mysqli_connect_error());
}

mysqli_set_charset($mysqli, $charset);
