<?php
session_start();

// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['admin_name'])) {
    header("Location: login.php"); // Redirigez vers la page de connexion si non connecté
    exit();
}

// Connexion à la base de données
$mysqli = new mysqli("localhost", "root", "", "gstock");

// Vérifiez la connexion
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Ajouter un fournisseur
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_supplier'])) {
    $supplier_name = $mysqli->real_escape_string($_POST['supplier_name']);
    $supplier_contact = $mysqli->real_escape_string($_POST['supplier_contact']);

    $query = "INSERT INTO suppliers (name, contact) VALUES ('$supplier_name', '$supplier_contact')";
    if ($mysqli->query($query) === TRUE) {
        header("Location: suppliers.php"); // Rediriger vers la même page après ajout
        exit();
    } else {
        die("Error adding supplier: " . $mysqli->error);
    }
}

// Supprimer un fournisseur
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_supplier'])) {
    $supplier_id = (int)$_POST['supplier_id'];
    $query = "DELETE FROM suppliers WHERE id = $supplier_id";
    if ($mysqli->query($query) === TRUE) {
        header("Location: suppliers.php"); // Rediriger vers la même page après suppression
        exit();
    } else {
        die("Error deleting supplier: " . $mysqli->error);
    }
}

// Modifier un fournisseur
if (isset($_POST['edit_supplier'])) {
    $supplier_id = (int)$_POST['supplier_id'];
    $supplier_name = $mysqli->real_escape_string($_POST['supplier_name']);
    $supplier_contact = $mysqli->real_escape_string($_POST['supplier_contact']);

    $query = "UPDATE suppliers SET name = '$supplier_name', contact = '$supplier_contact' WHERE id = $supplier_id";
    if ($mysqli->query($query) === TRUE) {
        header("Location: suppliers.php"); // Rediriger vers la même page après modification
        exit();
    } else {
        die("Error updating supplier: " . $mysqli->error);
    }
}

// Récupérer la liste des fournisseurs
$query_suppliers = "SELECT * FROM suppliers";
$result_suppliers = $mysqli->query($query_suppliers);

// Vérifiez si la requête a réussi
if (!$result_suppliers) {
    die("Query failed: " . $mysqli->error);
}

$suppliers = [];
if ($result_suppliers->num_rows > 0) {
    while ($row = $result_suppliers->fetch_assoc()) {
        $suppliers[] = $row;
    }
}

$mysqli->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fournisseurs</title>
    <link rel="stylesheet" href="styles.css"> <!-- Lien vers votre fichier CSS -->
    <style>
       /* Styles du corps */
body {
    display: flex;
    margin: 0;
    font-family: Arial, sans-serif;
}


/* Styles du contenu principal */
.main-content {
    flex: 1;
    padding: 20px;
}
/* Style de la barre latérale */
/* Style de la barre latérale */
/* Style de la sidebar */
.sidebar {
    width: 250px;
    height: 100vh;
    background-color: #2c3e50; /* Bleu foncé élégant */
    color: #ecf0f1; /* Blanc légèrement grisé pour un contraste doux */
    padding: 20px;
    text-align: center;
    box-shadow: 2px 0 5px rgba(0, 0, 0, 0.2); /* Ombre subtile */
}

.sidebar-logo {
    width: 100px;
    height: auto;
    margin-bottom: 20px;
    border-radius: 50%; /* Donne une forme arrondie à l'image */
    border: 3px solid #ffffff; /* Ajoute une bordure blanche autour de l'image */
}

/* Style du titre H2 */
.sidebar h2 {
    font-size: 1.5rem;
    margin-bottom: 30px;
    color: #f8f9fa; /* Légèrement plus clair pour le contraste */
}

/* Style des liens dans la sidebar */
.sidebar a {
    display: block;
    color: white;
    text-decoration: none;
    padding: 10px 15px;
    margin: 10px 0;
    border-radius: 5px;
    background-color: #495057; /* Gris moyen pour les liens */
    transition: background-color 0.3s ease, transform 0.2s ease; /* Ajout de transitions */
}

/* Effet hover sur les liens */
.sidebar a:hover {
    background-color: #0056b3;
    transform: scale(1.05); /* Augmente légèrement la taille des liens au survol */
}

        .product-table {
    width: 80%; /* Ajustez la largeur selon vos besoins */
    border-collapse: collapse;
    margin: 20px auto; /* Centre horizontalement le tableau */
    background-color:gris
    color: white; /* Couleur du texte pour contraste */
}

/* Styles des cellules */
.product-table th, .product-table td {
    padding: 10px; /* Espace intérieur des cellules */
    border: 1px solid white; /* Bordure blanche pour bien délimiter les cellules */
}

/* En-têtes du tableau */
.product-table th {
    background-color: #2c3e50; /* Couleur plus foncée pour l'en-tête du tableau */
    font-weight: bold; /* Texte en gras */
    color: white;
}


/* Styles des formulaires */
form {
    margin-bottom: 20px;
    display: flex; /* Utilisation de flexbox */
    flex-direction: column; /* Colonne pour une meilleure disposition */
    gap: 10px; /* Espacement entre les éléments du formulaire */
}

input[type="text"] {
    padding: 10px;
    border: 1px solid #007bff;
    border-radius: 5px; /* Coins arrondis */
   /
}
/* Styles des boutons */
button {
    color: white;
    border: none;
    padding: 10px;
    border-radius: 5px; /* Coins arrondis */
    cursor: pointer;
}

/* Bouton Modifier */
button[name="edit_supplier"] {
    background-color: #28a745; /* Vert */
}

button[name="edit_supplier"]:hover {
    background-color: #218838; /* Vert foncé au survol */
}

/* Bouton Supprimer */
button[name="delete_supplier"] {
    background-color: #dc3545; /* Rouge */
}

button[name="delete_supplier"]:hover {
    background-color: #c82333; /* Rouge foncé au survol */
}

/* Conteneur des boutons */
.button-container {
    display: flex; /* Utilise flexbox pour le conteneur */
    gap: 10px; /* Espace entre les boutons */
}
/* Style pour le bouton "Revenir au tableau de bord" */
.back-button {
    padding: 10px 15px; /* Espacement intérieur */
    background-color: #007bff; /* Couleur de fond */
    color: white; /* Couleur du texte */
    border-radius: 5px; /* Coins arrondis */
    transition: background-color 0.3s ease; /* Transition douce pour le survol */
    text-decoration: none; /* Empêche le soulignement du texte */
}

/* Ajoutez également ce style pour le survol */
.back-button:hover {
    background-color: #0056b3; /* Couleur de fond au survol */
    text-decoration: none; /* Empêche le soulignement au survol */
}

    </style>
</head>
<body>
    <div class="sidebar">
    <img src="img/management-icon-teamwork-business-team-260nw-1051689107.webp" alt="Logo" class="sidebar-logo">

        <h2>Tableau de bord</h2>
        <a href="admin.php">Tableau de bord</a>
        <a href="product_history.php">Historique des produits</a>
        <a href="suppliers.php">Fournisseurs</a>
        <a href="logout.php">Déconnexion</a>
    </div>
    <div class="main-content">
        <h1>Gestion des Fournisseurs</h1>
        
        <h2>Ajouter un Fournisseur</h2>
<form method="POST">
    <input type="text" name="supplier_name" placeholder="Nom du fournisseur" required>
    <input type="text" name="supplier_contact" placeholder="Contact du fournisseur" required>
    <button type="submit" name="add_supplier">Ajouter</button>
</form>


        <h2>Liste des Fournisseurs</h2>
        <table class="product-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Contact</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($suppliers)): ?>
                    <?php foreach ($suppliers as $supplier): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($supplier['id']); ?></td>
                            <td><?php echo htmlspecialchars($supplier['name']); ?></td>
                            <td><?php echo htmlspecialchars($supplier['contact']); ?></td>
                            <td>
    <!-- Formulaire pour modifier et supprimer le fournisseur -->
    <form method="POST" style="display:inline;">
        <input type="hidden" name="supplier_id" value="<?php echo htmlspecialchars($supplier['id']); ?>">
        <input type="text" name="supplier_name" value="<?php echo htmlspecialchars($supplier['name']); ?>" required>
        <input type="text" name="supplier_contact" value="<?php echo htmlspecialchars($supplier['contact']); ?>" required>
        
        <div class="button-container">
    <button type="submit" name="edit_supplier" class="btn-edit">Modifier</button>
    <button type="submit" name="delete_supplier" class="btn-delete" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce fournisseur ?');">Supprimer</button>
</div>


    </form>
</td>

                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4">Aucun fournisseur disponible.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <div class="button-container">
    <a href="admin.php" class="back-button">Revenir au tableau de bord</a>
</div>

    </div>
    
</body>
</html>
