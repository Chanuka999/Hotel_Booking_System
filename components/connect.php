<?php

$server_name = "localhost";
$username = "root";
$password = "";
$dbName = "hotel_db";

$conn = mysqli_connect($server_name,$username,$password,$dbName);

if(!$conn){
  die("connection failed" . mysqli_connect_error());
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