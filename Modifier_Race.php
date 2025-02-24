<?php
require_once "FooterHeader/Header.php";
require_once "Security/config.php"; // Connexion à la base de données

// Vérifier si un ID est fourni dans l'URL
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

// Récupérer la liste des factions
$query = $pdo->query("SELECT id, nom FROM factions");
$factions = $query->fetchAll(PDO::FETCH_ASSOC);

// Récupérer la liste des héros
$query = $pdo->query("SELECT id, nom, race_id FROM heros");
$heros = $query->fetchAll(PDO::FETCH_ASSOC);

// Modifier une race
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["modifier"])) {
    $nom = $_POST["nom"];
    $taille_moyenne = $_POST["taille_moyenne"];
    $description = $_POST["description"];
    $faction_associee = !empty($_POST["faction_associee"]) ? $_POST["faction_associee"] : NULL;
    $hero_associe = !empty($_POST["hero_associe"]) ? $_POST["hero_associe"] : NULL;
    $image_name = $race["image"]; // Conserver l'image actuelle par défaut

    // Gestion de l'image
    if (!empty($_FILES["image"]["name"])) {
        $target_dir = "Img/Race/";
        $image_name = basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $image_name;
        move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
    }

    // Mise à jour dans la base de données
    $sql = "UPDATE races SET nom = ?, taille_moyenne = ?, description = ?, region_presente = ?, image = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$nom, $taille_moyenne, $description, $faction_associee, $image_name, $race_id]);

    // Mise à jour de l'association héros - race
    $pdo->prepare("UPDATE heros SET race_id = NULL WHERE race_id = ?")->execute([$race_id]); // Dissocier les anciens héros
    if ($hero_associe) {
        $pdo->prepare("UPDATE heros SET race_id = ? WHERE id = ?")->execute([$race_id, $hero_associe]); // Associer le nouveau héros
    }

    // Redirection après modification
    header("Location: Backoffice_Race.php");
    exit;
}
?>

<main class="container py-5">
    <h2 class="text-center text-warning mb-3">Modifier la Race : <?php echo htmlspecialchars($race["nom"]); ?></h2>

    <!-- Formulaire de modification -->
    <div class="card p-4 mb-4">
        <h4>Éditer les informations de la Race</h4>
        <form method="POST" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-6">
                    <label>Nom</label>
                    <input type="text" name="nom" class="form-control" value="<?php echo htmlspecialchars($race['nom']); ?>" required>

                    <label>Taille Moyenne</label>
                    <input type="text" name="taille_moyenne" class="form-control" value="<?php echo htmlspecialchars($race['taille_moyenne']); ?>" required>
                </div>

                <div class="col-md-6">
                    <label>Description</label>
                    <textarea name="description" class="form-control"><?php echo htmlspecialchars($race['description']); ?></textarea>

                    <label>Faction Associée</label>
                    <select name="faction_associee" class="form-control">
                        <option value="">Aucune</option>
                        <?php foreach ($factions as $faction): ?>
                            <option value="<?php echo $faction['id']; ?>" <?php echo ($race['region_presente'] == $faction['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($faction['nom']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <label>Héros Associé</label>
                    <select name="hero_associe" class="form-control">
                        <option value="">Aucun</option>
                        <?php foreach ($heros as $hero): ?>
                            <option value="<?php echo $hero['id']; ?>" <?php echo ($hero['race_id'] == $race_id) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($hero['nom']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <label>Image actuelle</label>
                    <div>
                        <img src="Img/Race/<?php echo !empty($race['image']) ? htmlspecialchars($race['image']) : 'default_race.jpg'; ?>" width="100" class="mb-2">
                    </div>
                    <label>Changer l'Image</label>
                    <input type="file" name="image" class="form-control">
                </div>
            </div>

            <button type="submit" name="modifier" class="btn btn-primary mt-3">Modifier</button>
        </form>
    </div>
</main>

<?php require_once "FooterHeader/Footer.php"; ?>
</body>
</html>
