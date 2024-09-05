<?php

$server = "localhost";
$user = "root";
$pass = "";
$database = "cabreracrud";

$conn = new mysqli($server, $user, $pass, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!$conn->set_charset("utf8")) {
    die("Error loading character set utf8: " . $conn->error);
}

?>
