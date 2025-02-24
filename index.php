<?php
require_once "FooterHeader/Header.php";
?>

<!-- Main Content -->
<main class="container py-5">
    <div class="row row-cols-1 row-cols-md-2 g-4 text-center">
        <!-- Factions -->
        <div class="col">
            <div class="card home-card bg-secondary text-white">
                <img src="Img/Factions/Faction_front.jpeg" class="card-img-top home-img" alt="Factions">
                <div class="card-body">
                    <h5 class="card-title">FACTIONS</h5>
                    <p class="card-text">Découvrez les factions qui dominent le monde de Mondolia.</p>
                    <a href="Faction_menu.php" class="btn btn-danger">Explorer</a>
                </div>
            </div>
        </div>

        <!-- Races -->
        <div class="col">
            <div class="card home-card bg-secondary text-white">
                <img src="Img/Race/Race_front.jpeg" class="card-img-top home-img" alt="Races">
                <div class="card-body">
                    <h5 class="card-title">RACES</h5>
                    <p class="card-text">Apprenez-en plus sur les différentes races peuplant cet univers.</p>
                    <a href="Race_menu.php" class="btn btn-danger">Explorer</a>
                </div>
            </div>
        </div>

        <!-- Guildes -->
        <div class="col">
            <div class="card home-card bg-secondary text-white">
                <img src="Img/Guildes/Guilde_front.jpeg" class="card-img-top home-img" alt="Guildes">
                <div class="card-body">
                    <h5 class="card-title">GUILDES</h5>
                    <p class="card-text">Plongez dans le monde des guildes et de leurs légendes.</p>
                    <a href="Guilde_menu.php" class="btn btn-danger">Explorer</a>
                </div>
            </div>
        </div>

        <!-- Héros -->
        <div class="col">
            <div class="card home-card bg-secondary text-white">
                <img src="Img/Hero/Hero_front.jpeg" class="card-img-top home-img" alt="Héros">
                <div class="card-body">
                    <h5 class="card-title">HEROS</h5>
                    <p class="card-text">Découvrez les héros qui façonnent l’histoire de ce monde.</p>
                    <a href="Hero_menu.php" class="btn btn-danger">Explorer</a>
                </div>
            </div>
        </div>
    </div>
</main>


<?php require_once "FooterHeader/Footer.php"; ?>

</body>
</html>
