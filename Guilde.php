<?php
require_once "FooterHeader/Header.php";
require_once "Security/config.php";

if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<p class='text-danger text-center'>Aucune guilde sélectionnée.</p>";
    exit;
}

$guilde_id = intval($_GET['id']);

// Récupérer les informations de la guilde avec la faction associée
$stmt = $pdo->prepare("
    SELECT g.*, f.nom AS faction_nom, f.image AS faction_image, f.id AS faction_id
    FROM guildes g
    LEFT JOIN factions f ON g.faction_id = f.id
    WHERE g.id = ?
");
$stmt->execute([$guilde_id]);
$guilde = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$guilde) {
    echo "<p class='text-danger text-center'>Guilde introuvable.</p>";
    exit;
}

// Récupérer les héros associés à la guilde
$stmt = $pdo->prepare("
    SELECT h.* FROM heros h WHERE h.guilde_id = ?
");
$stmt->execute([$guilde_id]);
$heros = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<main class="container py-5">
    <div class="row">
        <div class="col-md-4">
            <?php 
            // Vérification et correction du chemin de l'image de la guilde
            $guildeImage = !empty($guilde['image']) ? "Img/Guildes/" . htmlspecialchars($guilde['image']) : "Img/Guildes/default_guild.jpg";
            ?>
            <img src="<?php echo $guildeImage; ?>" 
                 alt="Image de <?php echo htmlspecialchars($guilde['nom']); ?>" 
                 class="img-fluid rounded border">
        </div>
        <div class="col-md-8">
            <h2 class="text-primary"><?php echo htmlspecialchars($guilde['nom']); ?></h2>
            <p><strong>Type :</strong> <?php echo htmlspecialchars($guilde['type']); ?></p>
            <p><strong>Description :</strong> <?php echo htmlspecialchars($guilde['description']); ?></p>
            <p><strong>Faction Associée :</strong> 
                <?php if (!empty($guilde['faction_id'])): ?>
                    <a href="Faction.php?id=<?php echo htmlspecialchars($guilde['faction_id']); ?>">
                        <?php echo htmlspecialchars($guilde['faction_nom']); ?>
                    </a>
                <?php else: ?>
                    Aucune
                <?php endif; ?>
            </p>
        </div>
    </div>

    <hr class="my-4">

    <h3 class="text-danger text-center">Héros associés</h3>
<div class="row row-cols-1 row-cols-md-4 g-4">
    <?php foreach ($heros as $h): ?>
        <div class="col">
            <a href="Hero.php?id=<?php echo htmlspecialchars($h['id']); ?>" class="text-decoration-none">
                <div class="card bg-dark text-white border-light h-100">
                    <?php 
                    // Vérification et correction du chemin de l'image des héros
                    $heroImage = !empty($h['image']) ? "Img/Heros/" . htmlspecialchars($h['image']) : "Img/Heros/default_hero.jpg"; 
                    ?>
                    <img src="<?php echo $heroImage; ?>" 
                         class="card-img-top img-fluid rounded" 
                         style="max-height: 300px; object-fit: cover;" 
                         alt="Image de <?php echo htmlspecialchars($h['nom']); ?>">

                    <div class="card-body text-center">
                        <h5 class="card-title"><?php echo htmlspecialchars($h['nom']); ?></h5>
                        <p class="card-text"><?php echo htmlspecialchars($h['fonction']); ?></p>
                    </div>
                </div>
            </a>
        </div>
    <?php endforeach; ?>
</div>


    <h3 class="text-light mt-5">Faction Associée</h3>
    <?php if (!empty($guilde['faction_id']) && !empty($guilde['faction_image'])): ?>
        <a href="Faction.php?id=<?php echo htmlspecialchars($guilde['faction_id']); ?>" class="text-decoration-none">
            <div class="card bg-dark text-white border-light mt-3" style="max-width: 500px; margin: auto;">
                <img src="<?php echo htmlspecialchars($guilde['faction_image']); ?>" 
                     class="card-img-top img-fluid rounded border" 
                     style="max-height: 250px; object-fit: cover;" 
                     alt="Image de <?php echo htmlspecialchars($guilde['faction_nom']); ?>">
                <div class="card-body text-center">
                    <h5 class="card-title"><?php echo htmlspecialchars($guilde['faction_nom']); ?></h5>
                </div>
            </div>
        </a>
    <?php else: ?>
        <p class="text-white text-center">Aucune faction associée</p>
    <?php endif; ?>

</main>

<?php require_once "FooterHeader/Footer.php"; ?>
</body>
</html>
