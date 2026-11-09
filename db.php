<?php
$host = "sql102.infinityfree.com";
$user = "if0_43281779";
$pass = "kdjahaaZ5CbMq";
$dbname = "if0_43281779_pwaclasepractica4";

$conn = new mysqli($host, $user, $pass, $dbname);

$conn->set_charset("utf8");

if ($conn->connect_error) {
    die("Error: " . $conn->connect_error);
}
?>