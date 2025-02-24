<?php
require_once "FooterHeader/Header.php";
require_once "Security/config.php";


// Récupérer toutes les factions
$query = $pdo->query("SELECT id, nom, image FROM factions");
$factions = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<main class="container py-5">
    <h3 class="text-center ">FACTIONS</h3>
    <div class="row row-cols-1 row-cols-md-5 g-4">
        <?php foreach ($factions as $faction): ?>
            <div class="col">
            <a href="Faction.php?id=<?php echo isset($faction['id']) ? htmlspecialchars($faction['id']) : ''; ?>" class="text-decoration-none">
                    <div class="card faction-card bg-dark text-white border-light h-100">
                        <img src="<?php echo htmlspecialchars($faction['image']); ?>" class="card-img-top faction-img" alt="Image de <?php echo htmlspecialchars($faction['nom']); ?>">
                        <div class="card-body text-center">
                            <h5 class="card-title"><?php echo htmlspecialchars($faction['nom']); ?></h5>
                        </div>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</main>



<?php require_once "FooterHeader/Footer.php"; ?>
</body>
</html>
