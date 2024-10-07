<?php
// Connexion à la base de données
$servername = "localhost"; // ou votre serveur
$username = "root"; // Nom d'utilisateur par défaut de XAMPP
$password = ""; // Mot de passe vide par défaut de XAMPP
$dbname = "gstock";

// Créer une connexion
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Échec de la connexion: " . $conn->connect_error);
}

// Récupérer l'ID du produit
$id = $_GET['id'];

// Récupérer les données du formulaire
$name = $_POST['name'];
$price = $_POST['price'];
$quantity = $_POST['quantity'];
$manufacture_date = $_POST['manufacture_date'];

// Préparer et lier
$stmt = $conn->prepare("UPDATE products SET name = ?, price = ?, quantity = ?, manufacture_date = ? WHERE id = ?");
$stmt->bind_param("disdi", $name, $price, $quantity, $manufacture_date, $id);

// Exécuter la requête
if ($stmt->execute()) {
    header("Location: product_history.php");
    exit();
} else {
    echo "Erreur: " . $stmt->error;
}

// Fermer la connexion
$stmt->close();
$conn->close();
?>
