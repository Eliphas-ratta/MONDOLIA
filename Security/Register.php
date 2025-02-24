<?php
session_start();
require_once "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);
    $role = "user";

    if (!empty($username) && !empty($password)) {
        $checkUser = $pdo->prepare("SELECT id FROM utilisateurs WHERE username = ?");
        $checkUser->execute([$username]);

        if ($checkUser->rowCount() > 0) {
            $error = "Ce nom d'utilisateur est déjà pris.";
        } else {
            $hashedPassword = hash('sha256', $password);
            $stmt = $pdo->prepare("INSERT INTO utilisateurs (username, password, role) VALUES (?, ?, ?)");
            if ($stmt->execute([$username, $hashedPassword, $role])) {
                header("Location: Login.php");
                exit();
            } else {
                $error = "Erreur lors de l'inscription.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-dark text-white d-flex align-items-center justify-content-center vh-100">

<div class="container text-center">
    <div class="card bg-secondary p-4 w-50 mx-auto">
        <h2 class="mb-3">Inscription</h2>
        <?php if (isset($error)) echo "<p class='text-danger'>$error</p>"; ?>
        <form action="Register.php" method="post">
            <input type="text" name="username" class="form-control mb-2" placeholder="Nom d'utilisateur" required>
            <input type="password" name="password" class="form-control mb-2" placeholder="Mot de passe" required>
            <button type="submit" class="btn btn-primary w-100">S'inscrire</button>
        </form>
        <a href="Login.php" class="text-light mt-3 d-block">Déjà un compte ? Se connecter</a>
    </div>
</div>

</body>
</html>
