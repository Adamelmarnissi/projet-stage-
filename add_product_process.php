<?php
// Connexion à la base de données
$servername = "localhost";
$username = "root"; // Remplacez par votre utilisateur MySQL
$password = ""; // Remplacez par votre mot de passe MySQL
$dbname = "gstock"; // Votre base de données

$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Connexion échouée : " . $conn->connect_error);
}

// Récupération des données du formulaire
$name = $_POST['name'];
$price = $_POST['price'];
$quantity = $_POST['quantity'];

// Requête pour ajouter le produit sans la date de fabrication
$sql = "INSERT INTO products (name, price, quantity) VALUES ('$name', '$price', '$quantity')";

if ($conn->query($sql) === TRUE) {
    // Redirection vers la page historique des produits après ajout réussi
    header("Location: product_history.php");
    exit(); // Assurez-vous que le script s'arrête après la redirection
} else {
    echo "Erreur : " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>

