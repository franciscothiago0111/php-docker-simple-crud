<?php
$host = 'db'; // Nome do serviço do MySQL no docker-compose
$user = 'root';
$password = 'root';
$dbname = 'crud_php';

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}
?>
