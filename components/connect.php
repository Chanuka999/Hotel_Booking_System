<?php

$server_name = "localhost";
$username = "root";
$password = "";
$dbName = "hotel_db";

$conn =new mysqli($server_name,$username,$password,$dbName);

if($conn->connect_error){
  die("connection failed" . $conn->connect_error());
}

function create_unique_id(){
    $str = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWYZ123456789';
    $rend = array();
    $lenght = strlen($str) - 1;

    for($i=0;$i<20; $i++){
        $n = mt_rand(0, $lenght);
        $rand[] = $str[$n];
    }
    return implode($rand);
}
?>