<?php
require_once "FooterHeader/Header.php";
require_once "Security/config.php"; // Connexion à la base de données

// Récupérer la liste des factions
$query = $pdo->query("SELECT id, nom FROM factions");
$factions = $query->fetchAll(PDO::FETCH_ASSOC);

// Ajouter une race
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["ajouter"])) {
    $nom = $_POST["nom"];
    $taille_moyenne = $_POST["taille_moyenne"];
    $description = $_POST["description"];
    $faction_associee = !empty($_POST["faction_associee"]) ? $_POST["faction_associee"] : NULL;

    // Gestion de l’image
    $image_name = "default_race.jpg"; // Valeur par défaut
    if (!empty($_FILES["image"]["name"])) {
        $target_dir = "Img/Race/";
        $image_name = basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $image_name;
        move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
    }

    // Insertion dans la base de données
    $sql = "INSERT INTO races (nom, taille_moyenne, description, region_presente, image) 
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$nom, $taille_moyenne, $description, $faction_associee, $image_name]);

    // Insérer la relation race-faction si une faction est sélectionnée
    $race_id = $pdo->lastInsertId();
    if (!empty($faction_associee)) {
        $sql_relation = "INSERT INTO faction_races (faction_id, race_id) VALUES (?, ?)";
        $stmt_relation = $pdo->prepare($sql_relation);
        $stmt_relation->execute([$faction_associee, $race_id]);
    }
}

// Supprimer une race avec gestion des relations avant suppression
if (isset($_GET["supprimer"])) {
    $id = $_GET["supprimer"];

    // Supprimer d'abord les références dans faction_races
    $pdo->prepare("DELETE FROM faction_races WHERE race_id = ?")->execute([$id]);

    // Ensuite, supprimer la race elle-même
    $pdo->prepare("DELETE FROM races WHERE id = ?")->execute([$id]);
}

// Récupérer toutes les races avec leurs factions associées
$query = $pdo->query("
    SELECT r.*, f.nom AS faction_nom
    FROM races r
    LEFT JOIN factions f ON r.region_presente = f.id
");
$races = $query->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les héros associés à chaque race
$race_heros = [];
$query = $pdo->query("
    SELECT h.id AS hero_id, h.nom AS hero_nom, h.race 
    FROM heros h 
    WHERE h.race IS NOT NULL
");

$race_heros = [];
while ($hero = $query->fetch(PDO::FETCH_ASSOC)) {
    if (!empty($hero["race"])) {
        $race_heros[$hero["race"]][] = $hero["hero_nom"];
    }
}
?>

<main class="container py-5">
    <h2 class="text-center text-primary mb-3">Gestion des Races</h2>

    <!-- Formulaire d'ajout -->
    <div class="card p-4 mb-4">
        <h4>Ajouter une Race</h4>
        <form method="POST" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-6">
                    <label>Nom</label>
                    <input type="text" name="nom" class="form-control" required>

                    <label>Taille Moyenne</label>
                    <input type="text" name="taille_moyenne" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label>Description</label>
                    <textarea name="description" class="form-control"></textarea>

                    <label>Faction Associée</label>
                    <select name="faction_associee" class="form-control">
                        <option value="">Aucune</option>
                        <?php foreach ($factions as $faction): ?>
                            <option value="<?php echo $faction['id']; ?>"><?php echo htmlspecialchars($faction['nom']); ?></option>
                        <?php endforeach; ?>
                    </select>

                    <label>Image</label>
                    <input type="file" name="image" class="form-control">
                </div>
            </div>

            <button type="submit" name="ajouter" class="btn btn-success mt-3">Ajouter</button>
        </form>
    </div>

    <!-- Liste des races -->
    <h3 class="text-center">Liste des Races</h3>
    <table class="table table-dark table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Taille Moyenne</th>
                <th>Description</th>
                <th>Faction Associée</th>
                <th>Héros Associés</th>
                <th>Image</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($races as $race): ?>
                <tr>
                    <td><?php echo $race["id"]; ?></td>
                    <td><?php echo htmlspecialchars($race["nom"]); ?></td>
                    <td><?php echo htmlspecialchars($race["taille_moyenne"]); ?></td>
                    <td><?php echo htmlspecialchars($race["description"]); ?></td>
                    <td><?php echo !empty($race["faction_nom"]) ? htmlspecialchars($race["faction_nom"]) : "Aucune"; ?></td>
                    <td><?php echo !empty($race_heros[$race["id"]]) ? implode(", ", $race_heros[$race["id"]]) : "Aucun"; ?></td>
                    <td>
                        <img src="Img/Race/<?php echo htmlspecialchars($race['image']); ?>" width="50" alt="Image de <?php echo htmlspecialchars($race['nom']); ?>">
                    </td>
                    <td>
                        <a href="Modifier_Race.php?id=<?php echo $race["id"]; ?>" class="btn btn-warning">Modifier</a>
                        <a href="?supprimer=<?php echo $race["id"]; ?>" class="btn btn-danger" onclick="return confirm('Supprimer cette race ?');">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

<?php require_once "FooterHeader/Footer.php"; ?>
</body>
</html>
