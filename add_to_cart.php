<?php
session_start();

// Connexion à la base de données
$mysqli = new mysqli("localhost", "root", "", "gstock");

// Vérifiez la connexion
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Récupérer les données du formulaire
$product_id = $_POST['product_id'];
$requested_quantity = $_POST['quantity'];

// Récupérer les informations du produit
$query = "SELECT * FROM products WHERE id = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $product = $result->fetch_assoc();
    
    // Vérifiez si la quantité demandée est disponible
    if ($requested_quantity > $product['quantity']) {
        // La quantité demandée est supérieure à la quantité disponible
        $_SESSION['error_message'] = "La quantité demandée pour le produit '" . $product['name'] . "' est invalide. Quantité disponible : " . $product['quantity'];
        header("Location: user.php"); // Redirection vers la page utilisateur avec un message d'erreur
        exit();
    } else {
        // Ajouter le produit au panier (logique à implémenter selon votre projet)
        // Exemple d'ajout au panier:
        $_SESSION['cart'][$product_id] = $requested_quantity;
        
        // Rediriger vers la page du panier
        header("Location: cart.php");
        exit();
    }
} else {
    // Le produit n'existe pas
    $_SESSION['error_message'] = "Produit invalide.";
    header("Location: user.php");
    exit();
}

$mysqli->close();
?>
