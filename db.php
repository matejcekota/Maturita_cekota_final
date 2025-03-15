<?php
$host = "localhost";
$username = "root";
$password = "databazematurita22";
$dbname = "registrace";

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>