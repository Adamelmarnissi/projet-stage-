<?php
session_start();

// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['user_name'])) {
    header("Location: login.php");
    exit();
}

// Connexion à la base de données
$mysqli = new mysqli("localhost", "root", "", "gstock");

// Vérifiez la connexion
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Récupérer les informations du produit et de la quantité depuis la requête POST
if (isset($_POST['product_id']) && isset($_POST['quantity'])) {
    $product_id = $mysqli->real_escape_string($_POST['product_id']);
    $quantity = (int)$_POST['quantity'];

    // Vérifiez la quantité disponible du produit
    $query = "SELECT quantity FROM products WHERE id = '$product_id'";
    $result = $mysqli->query($query);

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();

        if ($product['quantity'] >= $quantity) {
            // Insérez l'achat dans la table purchases
            $user_id = $_SESSION['user_id'];
            $insert_query = "INSERT INTO purchases (user_id, product_id, quantity) VALUES ('$user_id', '$product_id', '$quantity')";

            if ($mysqli->query($insert_query) === TRUE) {
                // Mettre à jour la quantité du produit dans la base de données
                $new_quantity = $product['quantity'] - $quantity;
                $update_query = "UPDATE products SET quantity = '$new_quantity' WHERE id = '$product_id'";
                $mysqli->query($update_query);

                // Supprimer l'article acheté du panier
                unset($_SESSION['cart'][$product_id]);

                // Rediriger directement vers la page Historique des achats après l'achat
                header("Location: purchase_history.php");
                exit();
            } else {
                echo "Erreur lors de l'enregistrement de l'achat : " . $mysqli->error;
            }
        } else {
            // Si la quantité demandée dépasse celle disponible
            header("Location: cart.php?error=insufficient_quantity");
            exit();
        }
    } else {
        echo "Produit non trouvé.";
    }
} else {
    echo "ID du produit ou quantité non spécifiés.";
}

$mysqli->close();
?>
