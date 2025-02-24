<?php
require_once "FooterHeader/Header.php";
require_once "Security/config.php";

// Vérifier si un ID est passé en paramètre
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<p class='text-danger text-center'>Aucun héros sélectionné.</p>";
    exit;
}

$hero_id = intval($_GET['id']);

// Récupérer les informations du héros
$stmt = $pdo->prepare("SELECT * FROM heros WHERE id = ?");
$stmt->execute([$hero_id]);
$hero = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$hero) {
    echo "<p class='text-danger text-center'>Héros introuvable.</p>";
    exit;
}

// Récupérer les listes des factions, guildes et races
$query = $pdo->query("SELECT id, nom FROM factions");
$factions = $query->fetchAll(PDO::FETCH_ASSOC);

$query = $pdo->query("SELECT id, nom FROM guildes");
$guildes = $query->fetchAll(PDO::FETCH_ASSOC);

$query = $pdo->query("SELECT id, nom FROM races");
$races = $query->fetchAll(PDO::FETCH_ASSOC);

// Mise à jour du héros
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["modifier"])) {
    $nom = $_POST["nom"];
    $age = $_POST["age"];
    $taille = $_POST["taille"];
    $fonction = $_POST["fonction"];
    $description = $_POST["description"];
    $guilde_id = !empty($_POST["guilde_id"]) ? $_POST["guilde_id"] : NULL;
    $faction_id = !empty($_POST["faction_id"]) ? $_POST["faction_id"] : NULL;
    $race_id = !empty($_POST["race_id"]) ? $_POST["race_id"] : NULL;

    // Gestion de l’image (garder l'ancienne si pas de nouvelle image)
    $image_name = $hero["image"];
    if (!empty($_FILES["image"]["name"])) {
        $target_dir = "Img/Heros/";
        $image_name = basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], $target_dir . $image_name);
    }

    try {
        $sql = "UPDATE heros SET nom = ?, age = ?, taille = ?, fonction = ?, description = ?, guilde_id = ?, faction_id = ?, race_id = ?, image = ? 
                WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nom, $age, $taille, $fonction, $description, $guilde_id, $faction_id, $race_id, $image_name, $hero_id]);

        // Redirection après modification
        header("Location: Backoffice_Hero.php");
        exit();

    } catch (PDOException $e) {
        die("❌ ERREUR : " . $e->getMessage());
    }
}
?>

<main class="container py-5">
    <h2 class="text-center text-primary mb-3">Modifier un Héros</h2>

    <div class="card p-4">
        <form method="POST" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-6">
                    <label>Nom</label>
                    <input type="text" name="nom" class="form-control" value="<?php echo htmlspecialchars($hero['nom']); ?>" required>

                    <label>Âge</label>
                    <input type="text" name="age" class="form-control" value="<?php echo htmlspecialchars($hero['age']); ?>" required>

                    <label>Taille</label>
                    <input type="text" name="taille" class="form-control" value="<?php echo htmlspecialchars($hero['taille']); ?>" required>

                    <label>Fonction</label>
                    <input type="text" name="fonction" class="form-control" value="<?php echo htmlspecialchars($hero['fonction']); ?>" required>
                </div>

                <div class="col-md-6">
                    <label>Description</label>
                    <textarea name="description" class="form-control"><?php echo htmlspecialchars($hero['description']); ?></textarea>

                    <label>Guilde Associée</label>
                    <select name="guilde_id" class="form-control">
                        <option value="">Aucune</option>
                        <?php foreach ($guildes as $guilde): ?>
                            <option value="<?php echo $guilde['id']; ?>" <?php echo ($hero['guilde_id'] == $guilde['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($guilde['nom']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <label>Faction Associée</label>
                    <select name="faction_id" class="form-control">
                        <option value="">Aucune</option>
                        <?php foreach ($factions as $faction): ?>
                            <option value="<?php echo $faction['id']; ?>" <?php echo ($hero['faction_id'] == $faction['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($faction['nom']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <label>Race Associée</label>
                    <select name="race_id" class="form-control">
                        <option value="">Aucune</option>
                        <?php foreach ($races as $race): ?>
                            <option value="<?php echo $race['id']; ?>" <?php echo ($hero['race_id'] == $race['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($race['nom']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <label>Image Actuelle</label><br>
                    <img src="Img/Heros/<?php echo htmlspecialchars($hero['image']); ?>" width="100" class="mb-2">
                    
                    <label>Nouvelle Image</label>
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
