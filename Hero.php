<?php
require_once "FooterHeader/Header.php";
require_once "Security/config.php";

if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<p class='text-danger text-center'>Aucun héros sélectionné.</p>";
    exit;
}

$hero_id = intval($_GET['id']);

// Récupérer les informations du héros avec ses relations
$stmt = $pdo->prepare("
    SELECT h.*, g.nom AS guilde_nom, g.image AS guilde_image, 
           f.nom AS faction_nom, f.image AS faction_image, 
           r.nom AS race_nom, r.image AS race_image 
    FROM heros h
    LEFT JOIN guildes g ON h.guilde_id = g.id
    LEFT JOIN factions f ON h.faction_id = f.id
    LEFT JOIN races r ON h.race_id = r.id
    WHERE h.id = ?
");
$stmt->execute([$hero_id]);
$hero = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$hero) {
    echo "<p class='text-danger text-center'>Héros introuvable.</p>";
    exit;
}

?>

<main class="container py-5">
    <div class="row">
        <div class="col-md-4">
            <img src="Img/Heros/<?php echo htmlspecialchars($hero['image']); ?>" alt="Image de <?php echo htmlspecialchars($hero['nom']); ?>" class="img-fluid">
        </div>
        <div class="col-md-8">
            <h2 class="text-primary"><?php echo htmlspecialchars($hero['nom']); ?></h2>
            <p><strong>Âge :</strong> <?php echo htmlspecialchars($hero['age']); ?></p>
            <p><strong>Taille :</strong> <?php echo htmlspecialchars($hero['taille']); ?></p>
            <p><strong>Fonction :</strong> <?php echo htmlspecialchars($hero['fonction']); ?></p>
            <p><strong>Description :</strong> <?php echo htmlspecialchars($hero['description']); ?></p>
        </div>
    </div>

    <hr class="my-4">

    <div class="row text-center">
        <div class="col-md-4">
            <h4 class="text-light">Faction Associée</h4>
            <?php if (!empty($hero['faction_nom'])): ?>
                <a href="Faction.php?id=<?php echo htmlspecialchars($hero['faction_id']); ?>" class="text-decoration-none">
                    <div class="card bg-dark text-white border-light h-100">
                        <img src="<?php echo htmlspecialchars($hero['faction_image']); ?>" class="card-img-top">
                        <div class="card-body text-center">
                            <h5 class="card-title"><?php echo htmlspecialchars($hero['faction_nom']); ?></h5>
                        </div>
                    </div>
                </a>
            <?php else: ?>
                <p class="text-white">Aucune faction associée</p>
            <?php endif; ?>
        </div>

        <div class="col-md-4">
            <h4 class="text-light">Guilde</h4>
            <?php if (!empty($hero['guilde_nom'])): ?>
                <a href="Guilde.php?id=<?php echo htmlspecialchars($hero['guilde_id']); ?>" class="text-decoration-none">
                    <div class="card bg-dark text-white border-light h-100">
                        <img src="Img/Guildes/<?php echo htmlspecialchars($hero['guilde_image']); ?>" class="card-img-top">
                        <div class="card-body text-center">
                            <h5 class="card-title"><?php echo htmlspecialchars($hero['guilde_nom']); ?></h5>
                        </div>
                    </div>
                </a>
            <?php else: ?>
                <p class="text-white">Aucune guilde associée</p>
            <?php endif; ?>
        </div>

        <div class="col-md-4">
            <h4 class="text-light">Race</h4>
            <?php if (!empty($hero['race_nom'])): ?>
                <a href="Race.php?id=<?php echo htmlspecialchars($hero['race_id']); ?>" class="text-decoration-none">
                    <div class="card bg-dark text-white border-light h-100">
                        <img src="Img/Race/<?php echo htmlspecialchars($hero['race_image']); ?>" class="card-img-top">
                        <div class="card-body text-center">
                            <h5 class="card-title"><?php echo htmlspecialchars($hero['race_nom']); ?></h5>
                        </div>
                    </div>
                </a>
            <?php else: ?>
                <p class="text-white">Aucune race associée</p>
            <?php endif; ?>
        </div>
    </div>

</main>

<?php require_once "FooterHeader/Footer.php"; ?>
</body>
</html>
