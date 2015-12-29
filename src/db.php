<?php
$servername = getenv("DB_SERVERNAME");
$username   = getenv("DB_USERNAME");
$password   = getenv("DB_PASSWORD");
$dbname     = getenv("DB_NAME");

$db = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
?>
