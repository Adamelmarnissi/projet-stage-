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

// Vérifiez si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération des données du formulaire
    $product_id = $_POST['product_id'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];

    // Affichage pour débogage
    echo "ID: $product_id, Nom: $name, Prix: $price, Quantité: $quantity<br>";

    // Vérifiez les valeurs actuelles dans la base de données
    $result = $conn->query("SELECT * FROM products WHERE id = $product_id");
    $current_product = $result->fetch_assoc();
    echo "Valeurs actuelles - Nom: {$current_product['name']}, Prix: {$current_product['price']}, Quantité: {$current_product['quantity']}<br>";

    // Préparation de la requête de mise à jour
    $sql = "UPDATE products SET name=?, price=?, quantity=? WHERE id=?";
    $stmt = $conn->prepare($sql);

    // Vérifiez si la préparation a échoué
    if ($stmt === false) {
        die("Erreur de préparation : " . $conn->error);
    }

    $stmt->bind_param("sdis", $name, $price, $quantity, $product_id);

    // Exécution de la requête
    if ($stmt->execute()) {
        // Débogage - Afficher les lignes affectées
        echo "Nombre de lignes affectées : " . $stmt->affected_rows . "<br>";
        if ($stmt->affected_rows > 0) {
            header("Location: product_history.php"); // Redirection
            exit();
        } else {
            echo "Aucune mise à jour effectuée. Peut-être que les valeurs étaient identiques.";
        }
    } else {
        echo "Erreur de mise à jour : " . $stmt->error; // Affichage de l'erreur
    }

    $stmt->close();
}

// Fermer la connexion
$conn->close();
?>
