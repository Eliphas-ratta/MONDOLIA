<?php
$host = "mondolxsai.mysql.db";  // Adresse du serveur OVH
$dbname = "mondolxsai";         // Nom de la base de données
$username = "mondolxsai";       // Nom d'utilisateur MySQL
$password = "Archaon1886";      // Mot de passe MySQL

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>
