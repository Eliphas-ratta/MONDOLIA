<?php
$host = "localhost";
$dbname = "fantasy_world";
$username = "root"; // Mets ton identifiant MySQL
$password = "root"; // Mets ton mot de passe MySQL

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>
