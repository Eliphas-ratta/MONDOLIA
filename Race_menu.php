<?php
require_once "FooterHeader/Header.php";
require_once "Security/config.php";

// Récupérer toutes les races
$query = $pdo->query("SELECT id, nom, image FROM races");
$races = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<main class="container py-5">
    <h3 class="text-center">RACES</h3>
    <div class="row row-cols-1 row-cols-md-5 g-4">
        <?php if (empty($races)): ?>
            <p class="text-center">Aucune race disponible.</p>
        <?php else: ?>
            <?php foreach ($races as $race): ?>
                <div class="col">
                    <a href="Race.php?id=<?php echo htmlspecialchars($race['id']); ?>" class="text-decoration-none">
                        <div class="card race-card bg-dark text-white border-light h-100">
                            <img src="Img/Race/<?php echo htmlspecialchars($race['image']); ?>" class="card-img-top race-img" alt="Image de <?php echo htmlspecialchars($race['nom']); ?>">
                            <div class="card-body text-center">
                                <h5 class="card-title"><?php echo htmlspecialchars($race['nom']); ?></h5>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</main>

<?php require_once "FooterHeader/Footer.php"; ?>
</body>
</html>
