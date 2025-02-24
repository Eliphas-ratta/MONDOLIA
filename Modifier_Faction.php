<?php
require_once "FooterHeader/Header.php";
require_once "Security/config.php"; // Connexion à la base de données

// Vérifier si un ID de faction est présent
if (!isset($_GET["id"]) || empty($_GET["id"])) {
    echo "<p class='text-danger text-center'>Aucune faction sélectionnée.</p>";
    exit;
}

$faction_id = intval($_GET["id"]);

// Récupérer les informations de la faction
$stmt = $pdo->prepare("SELECT * FROM factions WHERE id = ?");
$stmt->execute([$faction_id]);
$faction = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$faction) {
    echo "<p class='text-danger text-center'>Faction introuvable.</p>";
    exit;
}

// Récupérer la liste des héros pour le dirigeant
$query = $pdo->query("SELECT id, nom FROM heros");
$heros = $query->fetchAll(PDO::FETCH_ASSOC);

// Modifier la faction
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["modifier"])) {
    $nom = $_POST["nom"];
    $regime = $_POST["regime"];
    $type = $_POST["type"];
    $couleur = $_POST["couleur"];
    $embleme = $_POST["embleme"];
    $capitale = $_POST["capitale"];
    $description = $_POST["description"];
    $dirigeant = !empty($_POST["dirigeant"]) ? $_POST["dirigeant"] : NULL;
    
    // Gestion de l’upload d’une nouvelle image
    $image_name = $faction["image"]; // Garder l’ancienne image par défaut
    if (!empty($_FILES["image"]["name"])) {
        $target_dir = "Img/Factions/";
        $image_name = time() . "_" . basename($_FILES["image"]["name"]); // Ajouter un timestamp pour éviter les doublons
        $target_file = $target_dir . $image_name;

        // Vérification et déplacement du fichier uploadé
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            // Supprimer l'ancienne image si elle existe et n'est pas l'image par défaut
            if ($faction["image"] !== "default_faction.jpg" && file_exists($faction["image"])) {
                unlink($faction["image"]);
            }
            $image_name = $target_file;
        }
    }

    // Mise à jour dans la base de données
    $sql = "UPDATE factions SET nom = ?, regime = ?, type = ?, couleur = ?, embleme = ?, capitale = ?, actuel_dirigeant = ?, description = ?, image = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$nom, $regime, $type, $couleur, $embleme, $capitale, $dirigeant, $description, $image_name, $faction_id]);

    // Rediriger vers la page des factions après modification
    header("Location: Backoffice_Faction.php");
    exit;
}
?>

<main class="container py-5">
    <h2 class="text-center text-primary mb-3">Modifier la Faction</h2>

    <!-- Formulaire de modification -->
    <div class="card p-4 mb-4">
        <h4>Modifier une Faction</h4>
        <form method="POST" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-6">
                    <label>Nom</label>
                    <input type="text" name="nom" class="form-control" value="<?php echo htmlspecialchars($faction["nom"]); ?>" required>

                    <label>Régime</label>
                    <input type="text" name="regime" class="form-control" value="<?php echo htmlspecialchars($faction["regime"]); ?>" required>

                    <label>Type</label>
                    <input type="text" name="type" class="form-control" value="<?php echo htmlspecialchars($faction["type"]); ?>" required>

                    <label>Couleur</label>
                    <input type="text" name="couleur" class="form-control" value="<?php echo htmlspecialchars($faction["couleur"]); ?>" required>
                </div>

                <div class="col-md-6">
                    <label>Emblème</label>
                    <input type="text" name="embleme" class="form-control" value="<?php echo htmlspecialchars($faction["embleme"]); ?>">

                    <label>Capitale</label>
                    <input type="text" name="capitale" class="form-control" value="<?php echo htmlspecialchars($faction["capitale"]); ?>">

                    <label>Description</label>
                    <textarea name="description" class="form-control"><?php echo htmlspecialchars($faction["description"]); ?></textarea>

                    <label>Dirigeant (Héros)</label>
                    <select name="dirigeant" class="form-control">
                        <option value="">Aucun</option>
                        <?php foreach ($heros as $hero): ?>
                            <option value="<?php echo $hero['id']; ?>" <?php echo ($hero['id'] == $faction['actuel_dirigeant']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($hero['nom']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-6">
                    <label>Image Actuelle</label>
                    <div>
                        <img src="<?php echo htmlspecialchars($faction["image"]); ?>" alt="Image de <?php echo htmlspecialchars($faction["nom"]); ?>" width="100">
                    </div>
                </div>

                <div class="col-md-6">
                    <label>Nouvelle Image</label>
                    <input type="file" name="image" class="form-control">
                </div>
            </div>

            <button type="submit" name="modifier" class="btn btn-warning mt-3">Modifier</button>
            <a href="Backoffice_Faction.php" class="btn btn-secondary mt-3">Annuler</a>
        </form>
    </div>
</main>

<?php require_once "FooterHeader/Footer.php"; ?>
</body>
</html>
