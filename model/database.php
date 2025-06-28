<?php

$dbServerName = "localhost";
$dbUserName = "root";
$dbPassword = "";
$dbName = "blog";

$conn = mysqli_connect($dbServerName, $dbUserName, $dbPassword, $dbName);
if ($conn) {
    echo "Database connected successfully!";
} else {
    echo "Database connected error";
}
?>