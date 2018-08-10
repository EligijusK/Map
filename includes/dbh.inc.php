<?php
$dbhost="localhost";
$dbuser="root";
$dbpassword="";
$dbname="map";

$sql = new mysqli($dbhost, $dbuser, $dbpassword, $dbname);

if($sql->connect_error)
{
    die("Connection failed: " . $conn->connect_error);
}
?>
