<?php
require_once "FooterHeader/Header.php";
?>

<main class="container py-5">
    <h2 class="text-center text-primary mb-5">Backoffice - Gestion du Contenu</h2>
    <div class="row row-cols-1 row-cols-md-2 g-4 text-center">
        
        <!-- Gestion des Factions -->
        <div class="col">
            <div class="card bg-secondary text-white">
                <img src="Img/Factions/Faction_front.jpeg" class="card-img-top" alt="Factions">
                <div class="card-body">
                    <h5 class="card-title">Gérer les Factions</h5>
                    <p class="card-text">Ajouter, modifier ou supprimer des factions.</p>
                    <a href="Backoffice_Faction.php" class="btn btn-warning">Accéder</a>
                </div>
            </div>
        </div>

        <!-- Gestion des Races -->
        <div class="col">
            <div class="card bg-secondary text-white">
                <img src="Img/Race/Race_front.jpeg" class="card-img-top" alt="Races">
                <div class="card-body">
                    <h5 class="card-title">Gérer les Races</h5>
                    <p class="card-text">Ajouter, modifier ou supprimer des races.</p>
                    <a href="Backoffice_Race.php" class="btn btn-warning">Accéder</a>
                </div>
            </div>
        </div>

        <!-- Gestion des Guildes -->
        <div class="col">
            <div class="card bg-secondary text-white">
                <img src="Img/Guildes/Guilde_front.jpeg" class="card-img-top" alt="Guildes">
                <div class="card-body">
                    <h5 class="card-title">Gérer les Guildes</h5>
                    <p class="card-text">Ajouter, modifier ou supprimer des guildes.</p>
                    <a href="Backoffice_Guilde.php" class="btn btn-warning">Accéder</a>
                </div>
            </div>
        </div>

        <!-- Gestion des Héros -->
        <div class="col">
            <div class="card bg-secondary text-white">
                <img src="Img/Heros/Hero_front.jpeg" class="card-img-top" alt="Héros">
                <div class="card-body">
                    <h5 class="card-title">Gérer les Héros</h5>
                    <p class="card-text">Ajouter, modifier ou supprimer des héros.</p>
                    <a href="Backoffice_Hero.php" class="btn btn-warning">Accéder</a>
                </div>
            </div>
        </div>

    </div>
</main>

<?php require_once "FooterHeader/Footer.php"; ?>
</body>
</html>
