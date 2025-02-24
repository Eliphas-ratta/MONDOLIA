<?php
require_once "FooterHeader/Header.php";
require_once "Security/config.php";

// Récupérer les listes des factions, guildes et races
$query = $pdo->query("SELECT id, nom FROM factions");
$factions = $query->fetchAll(PDO::FETCH_ASSOC);

$query = $pdo->query("SELECT id, nom FROM guildes");
$guildes = $query->fetchAll(PDO::FETCH_ASSOC);

$query = $pdo->query("SELECT id, nom FROM races");
$races = $query->fetchAll(PDO::FETCH_ASSOC);

// Ajouter un héros
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["ajouter"])) {
    $nom = $_POST["nom"];
    $age = $_POST["age"];
    $taille = $_POST["taille"];
    $fonction = $_POST["fonction"];
    $description = $_POST["description"];
    $guilde_id = !empty($_POST["guilde_id"]) ? $_POST["guilde_id"] : NULL;
    $faction_id = !empty($_POST["faction_id"]) ? $_POST["faction_id"] : NULL;
    $race_id = !empty($_POST["race_id"]) ? $_POST["race_id"] : NULL;

    // Gestion de l’image
    $image_name = "default_hero.jpg";
    if (!empty($_FILES["image"]["name"])) {
        $target_dir = "Img/Heros/";
        $image_name = basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], $target_dir . $image_name);
    }

    try {
        $sql = "INSERT INTO heros (nom, age, taille, fonction, description, guilde_id, faction_id, race_id, image) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nom, $age, $taille, $fonction, $description, $guilde_id, $faction_id, $race_id, $image_name]);

        $hero_id = $pdo->lastInsertId(); // Récupérer l'ID du héros inséré

        // Insérer la relation faction-héros si une faction est sélectionnée
        if (!empty($faction_id)) {
            $sql_relation = "INSERT INTO faction_heros (faction_id, hero_id) VALUES (?, ?)";
            $stmt_relation = $pdo->prepare($sql_relation);
            $stmt_relation->execute([$faction_id, $hero_id]);
        }
        
    } catch (PDOException $e) {
        die("❌ ERREUR lors de l'ajout : " . $e->getMessage());
    }
}

// Supprimer un héros avec suppression des relations
if (isset($_GET["supprimer"])) {
    $id = $_GET["supprimer"];

    try {
        // 1️⃣ Supprimer les relations faction_heros associées à ce héros
        $stmt = $pdo->prepare("DELETE FROM faction_heros WHERE hero_id = ?");
        $stmt->execute([$id]);

        // 2️⃣ Supprimer le héros
        $stmt = $pdo->prepare("DELETE FROM heros WHERE id = ?");
        $stmt->execute([$id]);

        // Redirection après suppression
        header("Location: Backoffice_Hero.php");
        exit();

    } catch (PDOException $e) {
        die("❌ ERREUR lors de la suppression : " . $e->getMessage());
    }
}

// Récupérer tous les héros avec leurs relations
$query = $pdo->query("
    SELECT h.*, g.nom AS guilde_nom, f.nom AS faction_nom, r.nom AS race_nom 
    FROM heros h
    LEFT JOIN guildes g ON h.guilde_id = g.id
    LEFT JOIN factions f ON h.faction_id = f.id
    LEFT JOIN races r ON h.race_id = r.id
");
$heros = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<main class="container py-5">
    <h2 class="text-center text-primary mb-3">Gestion des Héros</h2>

    <!-- Formulaire d'ajout -->
    <div class="card p-4 mb-4">
        <h4>Ajouter un Héros</h4>
        <form method="POST" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-6">
                    <label>Nom</label>
                    <input type="text" name="nom" class="form-control" required>

                    <label>Âge</label>
                    <input type="text" name="age" class="form-control" required>

                    <label>Taille</label>
                    <input type="text" name="taille" class="form-control" required>

                    <label>Fonction</label>
                    <input type="text" name="fonction" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label>Description</label>
                    <textarea name="description" class="form-control"></textarea>

                    <label>Guilde Associée</label>
                    <select name="guilde_id" class="form-control">
                        <option value="">Aucune</option>
                        <?php foreach ($guildes as $guilde): ?>
                            <option value="<?php echo $guilde['id']; ?>"><?php echo htmlspecialchars($guilde['nom']); ?></option>
                        <?php endforeach; ?>
                    </select>

                    <label>Faction Associée</label>
                    <select name="faction_id" class="form-control">
                        <option value="">Aucune</option>
                        <?php foreach ($factions as $faction): ?>
                            <option value="<?php echo $faction['id']; ?>"><?php echo htmlspecialchars($faction['nom']); ?></option>
                        <?php endforeach; ?>
                    </select>

                    <label>Race Associée</label>
                    <select name="race_id" class="form-control">
                        <option value="">Aucune</option>
                        <?php foreach ($races as $race): ?>
                            <option value="<?php echo $race['id']; ?>"><?php echo htmlspecialchars($race['nom']); ?></option>
                        <?php endforeach; ?>
                    </select>

                    <label>Image</label>
                    <input type="file" name="image" class="form-control">
                </div>
            </div>

            <button type="submit" name="ajouter" class="btn btn-success mt-3">Ajouter</button>
        </form>
    </div>

    <!-- Liste des héros -->
    <h3 class="text-center">Liste des Héros</h3>
    <table class="table table-dark table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Âge</th>
                <th>Taille</th>
                <th>Fonction</th>
                <th>Guilde</th>
                <th>Faction</th>
                <th>Race</th>
                <th>Image</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($heros as $hero): ?>
                <tr>
                    <td><?php echo $hero["id"]; ?></td>
                    <td><?php echo htmlspecialchars($hero["nom"]); ?></td>
                    <td><?php echo htmlspecialchars($hero["age"]); ?></td>
                    <td><?php echo htmlspecialchars($hero["taille"]); ?></td>
                    <td><?php echo htmlspecialchars($hero["fonction"]); ?></td>
                    <td><?php echo !empty($hero["guilde_nom"]) ? htmlspecialchars($hero["guilde_nom"]) : "Aucune"; ?></td>
                    <td><?php echo !empty($hero["faction_nom"]) ? htmlspecialchars($hero["faction_nom"]) : "Aucune"; ?></td>
                    <td><?php echo !empty($hero["race_nom"]) ? htmlspecialchars($hero["race_nom"]) : "Aucune"; ?></td>
                    <td>
                        <img src="Img/Heros/<?php echo htmlspecialchars($hero['image']); ?>" width="50" alt="Image de <?php echo htmlspecialchars($hero['nom']); ?>">
                    </td>
                    <td>
                        <a href="Modifier_Hero.php?id=<?php echo $hero["id"]; ?>" class="btn btn-warning">Modifier</a>
                        <a href="?supprimer=<?php echo $hero["id"]; ?>" class="btn btn-danger" onclick="return confirm('Supprimer ce héros ?');">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

<?php require_once "FooterHeader/Footer.php"; ?>
</body>
</html>
