<?php
require_once "FooterHeader/Header.php";
require_once "Security/config.php";

if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<p class='text-danger text-center'>Aucune race sélectionnée.</p>";
    exit;
}

$race_id = intval($_GET['id']);

// Récupérer les informations de la race
$stmt = $pdo->prepare("SELECT * FROM races WHERE id = ?");
$stmt->execute([$race_id]);
$race = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$race) {
    echo "<p class='text-danger text-center'>Race introuvable.</p>";
    exit;
}

// Récupérer les factions associées
$stmt = $pdo->prepare("
    SELECT f.* FROM factions f 
    INNER JOIN faction_races fr ON f.id = fr.faction_id 
    WHERE fr.race_id = ?
");
$stmt->execute([$race_id]);
$factions = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les héros associés à la race
$stmt = $pdo->prepare("
    SELECT h.* FROM heros h 
    WHERE h.race_id = ?
");
$stmt->execute([$race_id]);
$heros = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<main class="container py-5">
    <div class="row">
        <div class="col-md-4">
            <img src="Img/Race/<?php echo htmlspecialchars($race['image']); ?>" 
                 alt="Image de <?php echo htmlspecialchars($race['nom']); ?>" 
                 class="img-fluid">
        </div>
        <div class="col-md-8">
            <h2 class="text-primary"><?php echo htmlspecialchars($race['nom']); ?></h2>
            <p><strong>Taille Moyenne :</strong> <?php echo htmlspecialchars($race['taille_moyenne']); ?></p>
            <p><strong>Description :</strong> <?php echo htmlspecialchars($race['description']); ?></p>
        </div>
    </div>

    <hr class="my-4">

    <h3 class="text-danger text-center">Factions associées</h3>
    <div class="row row-cols-1 row-cols-md-4 g-4">
        <?php foreach ($factions as $faction): ?>
            <div class="col">
                <a href="Faction.php?id=<?php echo htmlspecialchars($faction['id']); ?>" class="text-decoration-none">
                    <div class="card bg-dark text-white border-light h-100">
                        <img src="<?php echo htmlspecialchars($faction['image']); ?>" class="card-img-top" alt="Image de <?php echo htmlspecialchars($faction['nom']); ?>">
                        <div class="card-body text-center">
                            <h5 class="card-title"><?php echo htmlspecialchars($faction['nom']); ?></h5>
                        </div>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
    </div>

    <h3 class="text-danger text-center mt-5">Héros associés</h3>
    <div class="row row-cols-1 row-cols-md-4 g-4">
        <?php foreach ($heros as $hero): ?>
            <div class="col">
                <div class="card bg-dark text-white border-light h-100">
                    <img src="Img/Heros/<?php echo htmlspecialchars($hero['image']); ?>" class="card-img-top" alt="Image de <?php echo htmlspecialchars($hero['nom']); ?>">
                    <div class="card-body text-center">
                        <h5 class="card-title"><?php echo htmlspecialchars($hero['nom']); ?></h5>
                        <p class="card-text"><?php echo htmlspecialchars($hero['fonction']); ?></p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

</main>

<?php require_once "FooterHeader/Footer.php"; ?>
</body>
</html>
