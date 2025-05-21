<?php

$hostName = "localhost";
$dbUser = "root";
$dbPassword = "";
$dbName = "userinfo";
$conn = mysqli_connect("localhost", "root", "", "aqi");

if (!$conn) {
    die("Something went wrong;");
}

?>