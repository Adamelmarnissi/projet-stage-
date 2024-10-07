<?php
// Connexion à la base de données
$servername = "localhost"; // Remplacez par votre serveur
$username = "root"; // Remplacez par votre nom d'utilisateur de base de données
$password = ""; // Remplacez par votre mot de passe de base de données
$dbname = "gstock"; // Nom de votre base de données

$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifiez la connexion
if ($conn->connect_error) {
    die("Échec de la connexion : " . $conn->connect_error);
}

// Traitement du formulaire d'inscription
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $dob = $_POST['dob'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash du mot de passe

    // Insertion des données dans la base de données
    $sql = "INSERT INTO users (first_name, last_name, dob, phone, email, username, password) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssss", $first_name, $last_name, $dob, $phone, $email, $username, $password);

    if ($stmt->execute()) {
        // Redirection vers la page de connexion après une inscription réussie
        header("Location: login.php");
        exit();
    } else {
        echo "Erreur : " . $stmt->error;
    }
    $stmt->close();
}

$conn->close();
?>
