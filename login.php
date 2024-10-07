<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="styles.css"> <!-- Lien vers votre fichier CSS -->
</head>
<body class="login-page"> <!-- Ajout de la classe pour l'image de fond -->
    
    <form method="POST" action="login_process.php">
        <fieldset>
          <h1>Connexion</h1> 
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Mot de passe" required>
            <button type="submit">Se connecter</button>
            <p style="text-align: center;"> <!-- Centrage du texte -->
                Vous n'avez pas de compte ? 
                <a href="signup.php"> <!-- Lien vers la page d'inscription -->
                    <button type="button">S'inscrire</button> <!-- Bouton qui agit comme un lien -->
                </a>
            </p>
        </fieldset>
    </form>
 
</body>
</html>
