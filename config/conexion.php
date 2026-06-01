<?php
$host = 'localhost:3306';
$db   = 'novalingua_school'; 
$user = 'root';       
$pass = 'Programacion*23';           
$charset = 'utf8mb4';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    // Le decimos a PHP que si hay un error, nos lo muestre claramente en pantalla
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $error) {
    echo "¡No se pudo conectar! El error fue: " . $error->getMessage();
    exit();
}
?>