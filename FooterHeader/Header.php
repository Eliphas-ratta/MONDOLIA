<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$basePath = (strpos($_SERVER['SCRIPT_NAME'], '/Security/') !== false) ? '../' : '';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mondolia</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" type="image/x-icon" href="Img/favicon-32x32.png">
</head>
<body class="bg-dark text-white">

<header class="bg-secondary py-3">
    <div class="container d-flex justify-content-between align-items-center">
        <!-- Logo -->
        <div >
            <a href="index.php" class="d-flex align-items-center text-white text-decoration-none ">
                <img src="Img/logo.png" alt="LOGO" class="img-fluid" style="width: 100px; padding: 10px;">
                <h3>MONDOLIA</h3>
            </a>
        </div>
        
        <!-- Navigation -->
        <nav>
            <ul class="nav">
                <li class="nav-item"><a href="Faction_menu.php" class="nav-link text-white">FACTIONS</a></li>
                <li class="nav-item"><a href="Race_menu.php" class="nav-link text-white">RACES</a></li>
                <li class="nav-item"><a href="Guilde_menu.php" class="nav-link text-white">GUILDES</a></li>
                <li class="nav-item"><a href="Hero_menu.php" class="nav-link text-white">HEROS</a></li>
                
                
                <?php if (isset($_SESSION["role"]) && $_SESSION["role"] === "admin"): ?>
                    <li class="nav-item"><a class="nav-link text-warning" href="Backoffice.php">BACKOFFICE</a></li>

                <?php endif; ?>
            </ul>
        </nav>
        
        <!-- Profil utilisateur -->
        <div class="d-flex align-items-center">
            <?php if (isset($_SESSION["username"])): ?>
                <img src="Img/pdp.png" alt="Profil" class="rounded-circle" style="width: 40px; height: 40px; background: #ccc;">
                <span class="text-white ms-2"> <?php echo htmlspecialchars($_SESSION["username"]); ?> </span>
                <a href="Security/Logout.php" class="btn btn-outline-light btn-sm ms-3">Déconnexion</a>
            <?php else: ?>
                <a href="Security/Login.php" class="btn btn-outline-light btn-sm">Se connecter</a>
            <?php endif; ?>
        </div>
    </div>
</header>
