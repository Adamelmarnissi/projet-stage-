<?php
session_start();

// Connexion à la base de données
$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "gstock"; 

$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifiez la connexion
if ($conn->connect_error) {
    die("Échec de la connexion : " . $conn->connect_error);
}

// Vérifiez si l'ID du produit est passé via l'URL
if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    // Préparation de la requête de suppression
    $sql = "DELETE FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Erreur de préparation : " . $conn->error);
    }

    $stmt->bind_param("i", $product_id);

    // Exécutez la requête
    if ($stmt->execute()) {
        // Rediriger vers l'historique des produits après la suppression
        header("Location: product_history.php");
        exit();
    } else {
        echo "Erreur lors de la suppression : " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Aucun ID de produit fourni.";
}

// Fermer la connexion
$conn->close();
?>
