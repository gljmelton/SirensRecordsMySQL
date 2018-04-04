<?php

$servername="******";
$username = '*******';
$password = '******';
$dbname="******";

$conn = mysql_connect($servername, $username, $password, $dbname) or die ('Error conncecting to mysql');

if($conn->connect_error){
  die("Connection failed: " . $conn->connect->error);
}

 ?>
