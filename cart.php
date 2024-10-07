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

// Récupérer les informations du panier
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$cart_items = [];

if (!empty($cart)) {
    foreach ($cart as $product_id => $quantity) {
        // Récupérer les informations du produit
        $query = "SELECT * FROM products WHERE id = $product_id";
        $result = $mysqli->query($query);

        if ($result->num_rows > 0) {
            $product = $result->fetch_assoc();
            $product['quantity'] = $quantity; // Ajouter la quantité au produit
            $cart_items[] = $product;
        }
    }
}

$mysqli->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panier</title>
    <link rel="stylesheet" href="user_style.css"> <!-- Lien vers votre fichier CSS -->
</head>
<body>
    <header>
        <h1>Votre Panier</h1>
        <br>
        <nav>
            <a href="user.php">Tableau de bord</a>
            <a href="cart.php">Panier</a>
            <a href="purchase_history.php">Historique des achats</a>
            <a href="logout.php">Déconnexion</a>
        </nav>
    </header>
    <main>
        <h2>Produits dans le Panier</h2>

        <!-- Affichage des messages d'erreur ou de succès -->
        <?php if (isset($_GET['success'])): ?>
            <p style="color: green;">Produit acheté avec succès !</p>
        <?php elseif (isset($_GET['error']) && $_GET['error'] == 'insufficient_quantity'): ?>
            <p style="color: red;">Quantité demandée supérieure à la quantité disponible.</p>
        <?php endif; ?>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Prix</th>
                    <th>Quantité</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($cart_items)): ?>
                    <?php foreach ($cart_items as $item): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['id']); ?></td>
                            <td><?php echo htmlspecialchars($item['name']); ?></td>
                            <td><?php echo htmlspecialchars($item['price']); ?> €</td>
                            <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                            <td>
                                <form action="process_purchase.php" method="POST">
                                    <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($item['id']); ?>">
                                    <input type="hidden" name="quantity" value="<?php echo htmlspecialchars($item['quantity']); ?>">
                                    <button type="submit">Acheter</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">Votre panier est vide.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Bouton pour revenir au tableau de bord -->
        <div style="text-align: center; margin-top: 20px;">
            <a href="user.php">
                <button type="button">Revenir au tableau de bord</button>
            </a>
        </div>

    </main>

</body>
</html>
