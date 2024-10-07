<?php
session_start();

// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirigez vers la page de connexion si non connecté
    exit();
}

// Connexion à la base de données
$mysqli = new mysqli("localhost", "root", "", "gstock");

// Vérifiez la connexion
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Récupérer l'ID de l'utilisateur
$user_id = $_SESSION['user_id'];

// Récupérer l'historique des achats de l'utilisateur
$query = "SELECT p.name, pu.quantity FROM purchases pu
          JOIN products p ON pu.product_id = p.id
          WHERE pu.user_id = '$user_id' ORDER BY pu.purchase_date DESC"; // Suppression de purchase_date

$result = $mysqli->query($query);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="purchase_styles.css">
    <title>Historique des Achats</title>
</head>
<body>
    <header>
        <h1>Historique des Achats</h1><br>
        <nav>
            <a href="user.php">Tableau de bord</a>
            <a href="cart.php">Panier</a>
            <a href="purchase_history.php">Historique des achats</a>
            <a href="logout.php">Déconnexion</a>
        </nav>
    </header>

    <main>
        <h2>Vos Achats</h2>
        <table>
            <thead>
                <tr>
                    <th>Nom du Produit</th>
                    <th>Quantité</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo htmlspecialchars($row['quantity']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="2">Aucun achat trouvé.</td> <!-- Changement ici -->
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </main>
</body>
</html>
