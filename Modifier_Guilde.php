<?php
require_once "FooterHeader/Header.php";
require_once "Security/config.php"; // Connexion à la base de données

// Vérifier si un ID est fourni dans l'URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<p class='text-danger text-center'>Aucune guilde sélectionnée.</p>";
    exit;
}

$guilde_id = intval($_GET['id']);

// Récupérer les informations de la guilde
$stmt = $pdo->prepare("SELECT * FROM guildes WHERE id = ?");
$stmt->execute([$guilde_id]);
$guilde = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$guilde) {
    echo "<p class='text-danger text-center'>Guilde introuvable.</p>";
    exit;
}

// Récupérer la liste des héros pour le dirigeant
$query = $pdo->query("SELECT id, nom FROM heros");
$heros = $query->fetchAll(PDO::FETCH_ASSOC);

// Récupérer la liste des factions pour l'association
$query = $pdo->query("SELECT id, nom FROM factions");
$factions = $query->fetchAll(PDO::FETCH_ASSOC);

// Modifier une guilde
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["modifier"])) {
    $nom = $_POST["nom"];
    $type = $_POST["type"];
    $description = $_POST["description"];
    $dirigeante = !empty($_POST["dirigeante"]) ? $_POST["dirigeante"] : NULL;
    $faction_id = !empty($_POST["faction_associee"]) ? $_POST["faction_associee"] : NULL;
    $image_name = $guilde["image"]; // Conserver l'image actuelle par défaut

    // Gestion de l'image
    if (!empty($_FILES["image"]["name"])) {
        $target_dir = "Img/Guildes/";
        $image_name = basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $image_name;
        move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
    }

    // Mise à jour dans la base de données
    $sql = "UPDATE guildes SET nom = ?, type = ?, description = ?, dirigeante = ?, faction_id = ?, image = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$nom, $type, $description, $dirigeante, $faction_id, $image_name, $guilde_id]);

    // Redirection après modification
    header("Location: Backoffice_Guilde.php");
    exit;
}
?>

<main class="container py-5">
    <h2 class="text-center text-warning mb-3">Modifier la Guilde : <?php echo htmlspecialchars($guilde["nom"]); ?></h2>

    <!-- Formulaire de modification -->
    <div class="card p-4 mb-4">
        <h4>Éditer les informations de la Guilde</h4>
        <form method="POST" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-6">
                    <label>Nom</label>
                    <input type="text" name="nom" class="form-control" value="<?php echo htmlspecialchars($guilde['nom']); ?>" required>

                    <label>Type</label>
                    <input type="text" name="type" class="form-control" value="<?php echo htmlspecialchars($guilde['type']); ?>" required>

                    <label>Description</label>
                    <textarea name="description" class="form-control"><?php echo htmlspecialchars($guilde['description']); ?></textarea>
                </div>

                <div class="col-md-6">
                    <label>Dirigeant (Héros)</label>
                    <select name="dirigeante" class="form-control">
                        <option value="">Aucun</option>
                        <?php foreach ($heros as $hero): ?>
                            <option value="<?php echo $hero['id']; ?>" <?php echo ($guilde['dirigeante'] == $hero['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($hero['nom']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <label>Faction Associée</label>
                    <select name="faction_associee" class="form-control">
                        <option value="">Aucune</option>
                        <?php foreach ($factions as $faction): ?>
                            <option value="<?php echo $faction['id']; ?>" <?php echo ($guilde['faction_id'] == $faction['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($faction['nom']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <label>Image actuelle</label>
                    <div>
                        <img src="Img/Guildes/<?php echo htmlspecialchars($guilde['image']); ?>" width="100" class="mb-2">
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
