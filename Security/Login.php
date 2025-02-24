<?php
session_start();
require_once "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    if (!empty($username) && !empty($password)) {
        $stmt = $pdo->prepare("SELECT id, password, role FROM utilisateurs WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && hash('sha256', $password) === $user["password"]) {
            $_SESSION["user_id"] = $user["id"];
            $_SESSION["username"] = $username;
            $_SESSION["role"] = $user["role"];
            header("Location: ../index.php");

            exit();
        } else {
            $error = "Nom d'utilisateur ou mot de passe incorrect.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-dark text-white d-flex align-items-center justify-content-center vh-100">

<div class="container text-center">
    <div class="card bg-secondary p-4 w-50 mx-auto">
        <h2 class="mb-3">Connexion</h2>
        <?php if (isset($error)) echo "<p class='text-danger'>$error</p>"; ?>
        <form action="Login.php" method="post">
            <input type="text" name="username" class="form-control mb-2" placeholder="Nom d'utilisateur" required>
            <input type="password" name="password" class="form-control mb-2" placeholder="Mot de passe" required>
            <button type="submit" class="btn btn-primary w-100">Se connecter</button>
        </form>
        <a href="Register.php" class="text-light mt-3 d-block">Créer un compte</a>
    </div>
</div>

</body>
</html>
