<?php
require_once "FooterHeader/Header.php";
require_once "Security/config.php"; // Connexion à la base de données

// Récupérer la liste des héros pour le dirigeant
$query = $pdo->query("SELECT id, nom FROM heros");
$heros = $query->fetchAll(PDO::FETCH_ASSOC);

// Ajouter une faction
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["ajouter"])) {
    $nom = $_POST["nom"];
    $regime = $_POST["regime"];
    $type = $_POST["type"];
    $couleur = $_POST["couleur"];
    $embleme = $_POST["embleme"];
    $capitale = $_POST["capitale"];
    $description = $_POST["description"];
    $dirigeant = !empty($_POST["dirigeant"]) ? $_POST["dirigeant"] : NULL;

    // Gestion de l'upload de l'image
    $image_name = "default_faction.jpg"; // Valeur par défaut
    if (!empty($_FILES["image"]["name"])) {
        $target_dir = "Img/Factions/";
        $image_name = time() . "_" . basename($_FILES["image"]["name"]); // Ajouter un timestamp pour éviter les doublons
        $target_file = $target_dir . $image_name;

        // Vérification et déplacement du fichier uploadé
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $image_name = $target_file; // On enregistre le chemin de l'image
        }
    }

    // Insertion dans la base
    $sql = "INSERT INTO factions (nom, regime, type, couleur, embleme, capitale, actuel_dirigeant, description, image) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$nom, $regime, $type, $couleur, $embleme, $capitale, $dirigeant, $description, $image_name]);
}

// Supprimer une faction
if (isset($_GET["supprimer"])) {
    $id = $_GET["supprimer"];

    // Mettre à NULL les références à cette faction dans la table races
    $pdo->prepare("UPDATE races SET region_presente = NULL WHERE region_presente = ?")->execute([$id]);

    // Récupération de l'image avant suppression pour la supprimer physiquement
    $query = $pdo->prepare("SELECT image FROM factions WHERE id = ?");
    $query->execute([$id]);
    $faction = $query->fetch(PDO::FETCH_ASSOC);

    if ($faction && $faction["image"] !== "default_faction.jpg" && file_exists($faction["image"])) {
        unlink($faction["image"]); // Supprime le fichier image du serveur
    }

    // Supprimer la faction après avoir supprimé les références
    $pdo->prepare("DELETE FROM factions WHERE id = ?")->execute([$id]);
}


// Récupérer toutes les factions avec leurs dirigeants
$query = $pdo->query("
    SELECT f.*, h.nom AS dirigeant_nom, h.image AS dirigeant_image
    FROM factions f
    LEFT JOIN heros h ON f.actuel_dirigeant = h.id
");
$factions = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<main class="container py-5">
    <h2 class="text-center text-primary mb-3">Gestion des Factions</h2>

    <!-- Formulaire d'ajout -->
    <div class="card p-4 mb-4">
        <h4>Ajouter une Faction</h4>
        <form method="POST" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-6">
                    <label>Nom</label>
                    <input type="text" name="nom" class="form-control" required>

                    <label>Régime</label>
                    <input type="text" name="regime" class="form-control" required>

                    <label>Type</label>
                    <input type="text" name="type" class="form-control" required>

                    <label>Couleur</label>
                    <input type="text" name="couleur" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label>Emblème</label>
                    <input type="text" name="embleme" class="form-control">

                    <label>Capitale</label>
                    <input type="text" name="capitale" class="form-control">

                    <label>Description</label>
                    <textarea name="description" class="form-control"></textarea>

                    <label>Dirigeant (Héros)</label>
                    <select name="dirigeant" class="form-control">
                        <option value="">Aucun</option>
                        <?php foreach ($heros as $hero): ?>
                            <option value="<?php echo $hero['id']; ?>"><?php echo htmlspecialchars($hero['nom']); ?></option>
                        <?php endforeach; ?>
                    </select>

                    <label>Image</label>
                    <input type="file" name="image" class="form-control">
                </div>
            </div>

            <button type="submit" name="ajouter" class="btn btn-success mt-3">Ajouter</button>
        </form>
    </div>

    <!-- Liste des factions -->
    <h3 class="text-center">Liste des Factions</h3>
    <table class="table table-dark table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Image</th>
                <th>Nom</th>
                <th>Régime</th>
                <th>Type</th>
                <th>Couleur</th>
                <th>Capitale</th>
                <th>Dirigeant</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($factions as $faction): ?>
                <tr>
                    <td><?php echo $faction["id"]; ?></td>
                    <td>
                        <img src="<?php echo htmlspecialchars($faction["image"]); ?>" alt="Image de <?php echo htmlspecialchars($faction["nom"]); ?>" width="50">
                    </td>
                    <td><?php echo htmlspecialchars($faction["nom"]); ?></td>
                    <td><?php echo htmlspecialchars($faction["regime"]); ?></td>
                    <td><?php echo htmlspecialchars($faction["type"]); ?></td>
                    <td><?php echo htmlspecialchars($faction["couleur"]); ?></td>
                    <td><?php echo htmlspecialchars($faction["capitale"]); ?></td>
                    <td>
                        <?php echo !empty($faction["dirigeant_nom"]) ? htmlspecialchars($faction["dirigeant_nom"]) : "Aucun"; ?>
                    </td>
                    <td>
                        <a href="Modifier_Faction.php?id=<?php echo $faction["id"]; ?>" class="btn btn-warning">Modifier</a>
                        <a href="?supprimer=<?php echo $faction["id"]; ?>" class="btn btn-danger" onclick="return confirm('Supprimer cette faction ?');">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

<?php require_once "FooterHeader/Footer.php"; ?>
</body>
</html>
