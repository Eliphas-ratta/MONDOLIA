<?php
require_once "FooterHeader/Header.php";
require_once "Security/config.php"; // Connexion à la base de données

// Récupérer la liste des héros pour le dirigeant
$query = $pdo->query("SELECT id, nom FROM heros");
$heros = $query->fetchAll(PDO::FETCH_ASSOC);

// Récupérer la liste des factions
$query = $pdo->query("SELECT id, nom FROM factions");
$factions = $query->fetchAll(PDO::FETCH_ASSOC);

// Ajouter une guilde
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["ajouter"])) {
    $nom = $_POST["nom"];
    $type = $_POST["type"];
    $description = $_POST["description"];
    $dirigeante = !empty($_POST["dirigeante"]) ? $_POST["dirigeante"] : NULL;
    $faction_id = !empty($_POST["faction_id"]) ? $_POST["faction_id"] : NULL;

    // Upload de l'image
    $image_name = "default_guilde.jpg"; // Valeur par défaut
    if (!empty($_FILES["image"]["name"])) {
        $target_dir = "Img/Guildes/";
        $image_name = basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $image_name;
        move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
    }

    // Insertion dans la base
    $sql = "INSERT INTO guildes (nom, type, description, dirigeante, faction_id, image) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$nom, $type, $description, $dirigeante, $faction_id, $image_name]);

    // Insérer la relation faction-guilde si une faction est sélectionnée
    $guilde_id = $pdo->lastInsertId();
    if (!empty($faction_id)) {
        $sql_relation = "INSERT INTO faction_guildes (faction_id, guilde_id) VALUES (?, ?)";
        $stmt_relation = $pdo->prepare($sql_relation);
        $stmt_relation->execute([$faction_id, $guilde_id]);
    }
}

// Supprimer une guilde avec gestion des relations avant suppression
if (isset($_GET["supprimer"])) {
    $id = $_GET["supprimer"];

    // Supprimer d'abord les références dans faction_guildes
    $pdo->prepare("DELETE FROM faction_guildes WHERE guilde_id = ?")->execute([$id]);

    // Ensuite, supprimer la guilde elle-même
    $pdo->prepare("DELETE FROM guildes WHERE id = ?")->execute([$id]);
}

// Récupérer toutes les guildes avec leurs dirigeants et factions associées
$query = $pdo->query("
    SELECT g.*, h.nom AS dirigeant_nom, h.image AS dirigeant_image, f.nom AS faction_nom
    FROM guildes g
    LEFT JOIN heros h ON g.dirigeante = h.id
    LEFT JOIN factions f ON g.faction_id = f.id
");
$guildes = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<main class="container py-5">
    <h2 class="text-center text-primary mb-3">Gestion des Guildes</h2>

    <!-- Formulaire d'ajout -->
    <div class="card p-4 mb-4">
        <h4>Ajouter une Guilde</h4>
        <form method="POST" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-6">
                    <label>Nom</label>
                    <input type="text" name="nom" class="form-control" required>

                    <label>Type</label>
                    <input type="text" name="type" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label>Description</label>
                    <textarea name="description" class="form-control"></textarea>

                    <label>Dirigeant (Héros)</label>
                    <select name="dirigeante" class="form-control">
                        <option value="">Aucun</option>
                        <?php foreach ($heros as $hero): ?>
                            <option value="<?php echo $hero['id']; ?>"><?php echo htmlspecialchars($hero['nom']); ?></option>
                        <?php endforeach; ?>
                    </select>

                    <label>Faction Associée</label>
                    <select name="faction_id" class="form-control">
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

    <!-- Liste des guildes -->
    <h3 class="text-center">Liste des Guildes</h3>
    <table class="table table-dark table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Type</th>
                <th>Faction</th>
                <th>Dirigeant</th>
                <th>Image</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($guildes as $guilde): ?>
                <tr>
                    <td><?php echo $guilde["id"]; ?></td>
                    <td><?php echo htmlspecialchars($guilde["nom"]); ?></td>
                    <td><?php echo htmlspecialchars($guilde["type"]); ?></td>
                    <td><?php echo !empty($guilde["faction_nom"]) ? htmlspecialchars($guilde["faction_nom"]) : "Aucune"; ?></td>
                    <td><?php echo !empty($guilde["dirigeant_nom"]) ? htmlspecialchars($guilde["dirigeant_nom"]) : "Aucun"; ?></td>
                    <td>
                        <img src="Img/Guildes/<?php echo htmlspecialchars($guilde['image']); ?>" width="50" alt="Image de <?php echo htmlspecialchars($guilde['nom']); ?>">
                    </td>
                    <td>
                        <a href="Modifier_Guilde.php?id=<?php echo $guilde["id"]; ?>" class="btn btn-warning">Modifier</a>
                        <a href="?supprimer=<?php echo $guilde["id"]; ?>" class="btn btn-danger" onclick="return confirm('Supprimer cette guilde ?');">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

<?php require_once "FooterHeader/Footer.php"; ?>
</body>
</html>
