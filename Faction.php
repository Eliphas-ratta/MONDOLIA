<?php
require_once "FooterHeader/Header.php";
require_once "Security/config.php";

if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<p class='text-danger text-center'>Aucune faction sélectionnée.</p>";
    exit;
}

$faction_id = intval($_GET['id']);

// Récupérer les informations de la faction
$stmt = $pdo->prepare("SELECT * FROM factions WHERE id = ?");
$stmt->execute([$faction_id]);
$faction = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$faction) {
    echo "<p class='text-danger text-center'>Faction introuvable.</p>";
    exit;
}

// Récupérer les informations du héros dirigeant
$hero = null;
if (!empty($faction['actuel_dirigeant'])) {
    $stmt = $pdo->prepare("SELECT * FROM heros WHERE id = ?");
    $stmt->execute([$faction['actuel_dirigeant']]);
    $hero = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Récupérer les races associées
$stmt = $pdo->prepare("SELECT r.* FROM races r 
                       INNER JOIN faction_races fr ON r.id = fr.race_id 
                       WHERE fr.faction_id = ?");
$stmt->execute([$faction_id]);
$races = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les guildes associées
$stmt = $pdo->prepare("SELECT g.* FROM guildes g 
                       INNER JOIN faction_guildes fg ON g.id = fg.guilde_id 
                       WHERE fg.faction_id = ?");
$stmt->execute([$faction_id]);
$guildes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les héros associés
$stmt = $pdo->prepare("SELECT h.* FROM heros h 
                       INNER JOIN faction_heros fh ON h.id = fh.hero_id 
                       WHERE fh.faction_id = ?");
$stmt->execute([$faction_id]);
$heros = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les races associées
$stmt = $pdo->prepare("SELECT r.* FROM races r 
                       INNER JOIN faction_races fr ON r.id = fr.race_id 
                       WHERE fr.faction_id = ?");
$stmt->execute([$faction_id]);
$races = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les guildes associées
$stmt = $pdo->prepare("SELECT g.* FROM guildes g 
                       INNER JOIN faction_guildes fg ON g.id = fg.guilde_id 
                       WHERE fg.faction_id = ?");
$stmt->execute([$faction_id]);
$guildes = $stmt->fetchAll(PDO::FETCH_ASSOC);


?>

<main class="container py-5">
    <div class="row">
        <div class="col-md-4">
            <img src="<?php echo htmlspecialchars($faction['image']); ?>" alt="Image de <?php echo htmlspecialchars($faction['nom']); ?>" class="img-fluid">
        </div>
        <div class="col-md-8">
            <h2 class="text-primary"><?php echo htmlspecialchars($faction['nom']); ?></h2>
            <p><strong>Régime :</strong> <?php echo htmlspecialchars($faction['regime']); ?></p>
            <p><strong>Type :</strong> <?php echo htmlspecialchars($faction['type']); ?></p>
            <p><strong>Couleurs :</strong> <?php echo htmlspecialchars($faction['couleur']); ?></p>
            <p><strong>Capitale :</strong> <?php echo htmlspecialchars($faction['capitale']); ?></p>
            <p><strong>Description :</strong> <?php echo htmlspecialchars($faction['description']); ?></p>
        </div>
    </div>

    <hr class="my-4">

    <div class="row text-center">
        
        <div class="col-md-4">
            <h4 class="text-light">Dirigeant Actuel</h4>
            <?php if ($hero): ?>
                <img src="Img/Heros/<?php echo htmlspecialchars($hero['image']); ?>" alt="Image de <?php echo htmlspecialchars($hero['nom']); ?>" class="img-fluid" style="max-width: 150px;">
                <p class="text-white mt-2"><?php echo htmlspecialchars($hero['nom']); ?>, <?php echo htmlspecialchars($hero['fonction']); ?></p>
            <?php else: ?>
                <p class="text-white">Aucun dirigeant enregistré</p>
            <?php endif; ?>
        </div>
       
    </div>

    <hr class="my-4">

    <h3 class="text-danger text-center">Héros associés</h3>
<div class="row row-cols-1 row-cols-md-4 g-4">
    <?php foreach ($heros as $h): ?>
        <div class="col">
            <div class="card bg-dark text-white border-light h-100">
                <img src="Img/Heros/<?php echo htmlspecialchars($h['image']); ?>" class="card-img-top">
                <div class="card-body text-center">
                    <h5 class="card-title"><?php echo htmlspecialchars($h['nom']); ?></h5>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>


    <h3 class="text-danger text-center mt-5">Races associées</h3>
    <div class="row row-cols-1 row-cols-md-4 g-4">
        <?php foreach ($races as $r): ?>
            <div class="col">
                <div class="card bg-dark text-white border-light h-100">
                <img src="Img/Race/<?php echo htmlspecialchars($r['image']); ?>" class="card-img-top" alt="Image de <?php echo htmlspecialchars($r['nom']); ?>">

                    <div class="card-body text-center">
                        <h5 class="card-title"><?php echo htmlspecialchars($r['nom']); ?></h5>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <h3 class="text-danger text-center mt-5">Guildes associées</h3>
    <div class="row row-cols-1 row-cols-md-4 g-4">
        <?php foreach ($guildes as $g): ?>
            <div class="col">
                <div class="card bg-dark text-white border-light h-100">
                <img src="Img/Guildes/<?php echo htmlspecialchars($g['image']); ?>" class="card-img-top" alt="Image de <?php echo htmlspecialchars($g['nom']); ?>">
                <div class="card-body text-center">
                        <h5 class="card-title"><?php echo htmlspecialchars($g['nom']); ?></h5>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

</main>

<?php require_once "FooterHeader/Footer.php"; ?>
</body>
</html>
