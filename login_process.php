<?php
session_start();

// Connexion à la base de données
$mysqli = new mysqli("localhost", "root", "", "gstock");

// Vérifiez la connexion
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Traitement de la connexion
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $mysqli->real_escape_string($_POST['email']);
    $password = $mysqli->real_escape_string($_POST['password']);

    // Vérifiez si l'email correspond à un administrateur
    if ($email === 'adam@gmail.com' && $password === 'adam12') {
        $_SESSION['admin_name'] = 'Adam'; // Identifiant de l'administrateur
        header("Location: admin.php"); // Redirige vers la page admin
        exit();
    } else {
        // Vérifiez si l'utilisateur est dans la base de données
        $query = "SELECT * FROM users WHERE email = '$email'";
        $result = $mysqli->query($query);

        if ($result->num_rows === 1) {
            // L'utilisateur est valide
            $user = $result->fetch_assoc();
            // Vérifiez le mot de passe
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id']; // Stocker l'ID de l'utilisateur
                $_SESSION['user_name'] = $user['first_name']; // Enregistrer le nom de l'utilisateur
                header("Location: user.php"); // Redirige vers la page utilisateur
                exit();
            } else {
                echo "Identifiants incorrects.";
            }
        } else {
            echo "Identifiants incorrects.";
        }
    }
}

$mysqli->close();
?>
