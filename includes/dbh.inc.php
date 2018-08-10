<?php
$dbhost="localhost";
$dbuser="root";
$dbpassword="";
$dbname="map";

$sql = new mysqli($dbhost, $dbuser, $dbpassword, $dbname);

if($sql->connect_error)
{
    die("Connection failed: " . $conn->connect_error);
}else
{
   $table1 =  "CREATE TABLE users (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(30) NOT NULL,
    email VARCHAR(30) NOT NULL UNIQUE,
    password varchar(256),
    created_at TIMESTAMP,
    updated_at TIMESTAMP)";
    $table2 =  "CREATE TABLE maps (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    Device_Id VARCHAR(191) NOT NULL UNIQUE,
    Coordinates VARCHAR(191) NOT NULL UNIQUE,
    Choise varchar(10),
    created_at TIMESTAMP,
    updated_at TIMESTAMP)";
    $create1 = mysqli_query($sql, $table1);
    $create2 = mysqli_query($sql, $table2);
}
?>
