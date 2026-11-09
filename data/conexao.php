<?php 

$host = "localhost";
$user = "root";
$password = "root";
$database = "projeto_final";

$conn = new mysqli($host, $user, $password, $database);

if($conn->connect_error){
    die("falha na conexão: " . $conn -> connect_error);

}
