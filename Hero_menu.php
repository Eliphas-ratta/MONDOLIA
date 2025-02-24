<?php
require_once "FooterHeader/Header.php";
require_once "Security/config.php";

// Récupérer tous les héros
$query = $pdo->query("SELECT id, nom, image FROM heros");
$heros = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<main class="container py-5">
    <h3 class="text-center">HÉROS</h3>
    <div class="row row-cols-1 row-cols-md-5 g-4">
        <?php foreach ($heros as $hero): ?>
            <div class="col">
                <a href="Hero.php?id=<?php echo isset($hero['id']) ? htmlspecialchars($hero['id']) : ''; ?>" class="text-decoration-none">
                    <div class="card bg-dark text-white border-light h-100">
                    <img src="Img/Heros/<?php echo htmlspecialchars($hero['image']); ?>" class="card-img-top" alt="Image de <?php echo htmlspecialchars($hero['nom']); ?>">

                        <div class="card-body text-center">
                            <h5 class="card-title"><?php echo htmlspecialchars($hero['nom']); ?></h5>
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
