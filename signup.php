<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link rel="stylesheet" href="signup.css"> <!-- Lien vers votre fichier CSS -->
</head>
<body class="signup-page"> <!-- Ajoutez la classe "signup-page" pour appliquer les styles -->
    <form method="POST" action="signup_process.php">
        <fieldset>
            <h1 class="form-title">Inscription</h1> <!-- Classe ajoutée ici -->
            
            <label for="first_name">Prénom:</label>
            <input type="text" id="first_name" name="first_name" required><br>

            <label for="last_name">Nom:</label>
            <input type="text" id="last_name" name="last_name" required><br>

            <label for="dob">Date de naissance:</label>
            <input type="date" id="dob" name="dob" required><br>

            <label for="phone">Téléphone:</label>
            <input type="text" id="phone" name="phone" required><br>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required><br>

            <label for="username">Nom d'utilisateur:</label>
            <input type="text" id="username" name="username" required><br>

            <label for="password">Mot de passe:</label>
            <input type="password" id="password" name="password" required><br>

            <input type="submit" value="S'inscrire">
        </fieldset>
    </form>
</body>
</html>
