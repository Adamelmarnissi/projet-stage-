<?php
session_start();

// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['user_name'])) {
    header("Location: login.php"); // Redirigez vers la page de connexion si non connecté
    exit();
}

// Connexion à la base de données
$mysqli = new mysqli("localhost", "root", "", "gstock");

// Vérifiez la connexion
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Récupérer les produits
$query_products = "SELECT * FROM products";
$result_products = $mysqli->query($query_products);

$products = [];
if ($result_products->num_rows > 0) {
    while ($row = $result_products->fetch_assoc()) {
        $products[] = $row;
    }
}

$mysqli->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace Utilisateur</title>
    <link rel="stylesheet" href="user_style.css"> <!-- Lien vers votre fichier CSS -->
</head>
<body>
    <header>
        <h1>Bienvenue, <?php echo htmlspecialchars($_SESSION['user_name']); ?></h1> <br>
        <nav>
            <a href="user.php">Tableau de bord</a>
            <a href="cart.php">Panier</a>
            <a href="purchase_history.php">Historique des achats</a>
            <a href="logout.php">Déconnexion</a>
        </nav>
    </header>
    
    <main>
        <h2>Tableau des Stocks</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Prix</th>
                    <th>Quantité</th>
                    <th>Ajouter au Panier</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($products)): ?>
                    <?php foreach ($products as $product): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($product['id']); ?></td>
                            <td><?php echo htmlspecialchars($product['name']); ?></td>
                            <td><?php echo htmlspecialchars($product['price']); ?> €</td>
                            <td><?php echo htmlspecialchars($product['quantity']); ?></td>
                            <td>
                                <form method="POST" action="add_to_cart.php">
                                    <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['id']); ?>">
                                    <input type="number" name="quantity" value="1" min="1" max="<?php echo htmlspecialchars($product['quantity']); ?>" required>
                                    <button type="submit">Ajouter</button>
                                    
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">Aucun produit disponible.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </main>
</body>
</html>
