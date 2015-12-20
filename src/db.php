<?php

$servername = getenv("DB_SERVERNAME");
$username   = getenv("DB_USERNAME");
$password   = getenv("DB_PASSWORD");

$db = new PDO("mysql:host=$servername;dbname=homespot", $username, $password);

?>
