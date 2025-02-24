<?php
require_once "FooterHeader/Header.php";
require_once "Security/config.php";

// Récupérer toutes les guildes
$query = $pdo->query("SELECT id, nom, image FROM guildes");
$guildes = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<main class="container py-5">
    <h3 class="text-center">GUILDES</h3>
    <div class="row row-cols-1 row-cols-md-5 g-4">
        <?php foreach ($guildes as $guilde): ?>
            <div class="col">
                <a href="Guilde.php?id=<?php echo isset($guilde['id']) ? htmlspecialchars($guilde['id']) : ''; ?>" class="text-decoration-none">
                    <div class="card bg-dark text-white border-light h-100">
                    <img src="Img/Guildes/<?php echo htmlspecialchars($guilde['image']); ?>" class="card-img-top" alt="Image de <?php echo htmlspecialchars($guilde['nom']); ?>">

                        <div class="card-body text-center">
                            <h5 class="card-title"><?php echo htmlspecialchars($guilde['nom']); ?></h5>
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
