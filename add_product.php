<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter Produit</title>
    
    <style>
        body {
            display: flex;
            margin: 0;
            background-color: #f4f4f4; /* Couleur de fond de la page */
        }

        .main-content {
            width: 80%; /* Ajustez la largeur selon vos besoins */
            max-width: 500px; /* Limite la largeur maximale pour le formulaire */
            background-color: white; /* Couleur de fond */
            padding: 20px; /* Espacement interne */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Ombre pour donner de la profondeur */
            border-radius: 8px; /* Coins arrondis */
            margin: auto; /* Centre le contenu */
            margin-top: 50px; /* Marge en haut pour le centrer verticalement */
        }

        /* Style de la barre latérale */
        .sidebar {
            width: 250px;
            height: 100vh;
            background-color: #2c3e50; /* Bleu foncé élégant */
            color: #ecf0f1; /* Blanc légèrement grisé pour un contraste doux */
            padding: 20px;
            text-align: center;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.2); /* Ombre subtile */
        }

        .sidebar-logo {
            width: 120px;
            height: auto;
            margin-bottom: 20px;
            border-radius: 50%; /* Donne une forme arrondie à l'image */
            border: 3px solid #ffffff; /* Ajoute une bordure blanche autour de l'image */
        }

        .sidebar h2 {
            font-size: 1.5rem;
            margin-bottom: 30px;
            color: #f8f9fa; /* Légèrement plus clair pour le contraste */
        }

        .sidebar a {
            display: block;
            color: white;
            text-decoration: none;
            padding: 10px 15px;
            margin: 10px 0;
            border-radius: 5px;
            background-color: #495057; /* Gris moyen pour les liens */
            transition: background-color 0.3s ease, transform 0.2s ease; /* Ajout de transitions */
        }

        .sidebar a:hover {
            background-color: #0056b3;
            transform: scale(1.05); /* Augmente légèrement la taille des liens au survol */
        }

        /* Style du formulaire */
        form {
            display: flex;
            flex-direction: column;
            gap: 15px; /* Espace entre les éléments du formulaire */
        }

        label {
            font-weight: bold; /* Met les étiquettes en gras */
            color: #34495e; /* Couleur des étiquettes */
        }

        input[type="text"],
        input[type="number"],
        input[type="submit"] {
            padding: 10px; /* Espacement interne */
            border: 1px solid #bdc3c7; /* Bordure gris clair */
            border-radius: 4px; /* Coins arrondis pour les champs */
            font-size: 14px; /* Taille de police */
            transition: border-color 0.3s; /* Transition pour la couleur de bordure */
        }

        input[type="text"]:focus,
        input[type="number"]:focus {
            border-color: #007bff; /* Bordure bleue au focus */
            outline: none; /* Retire le contour par défaut */
        }

        input[type="submit"] {
            background-color: #007bff; /* Couleur du bouton */
            color: white; /* Couleur du texte */
            cursor: pointer; /* Changement de curseur sur hover */
            font-weight: bold; /* Met le texte en gras */
            border: none; /* Retire la bordure */
            padding: 10px 0; /* Ajustement de l'espacement interne pour le bouton */
            border-radius: 4px; /* Coins arrondis */
            transition: background-color 0.3s; /* Transition pour le changement de couleur */
        }

        input[type="submit"]:hover {
            background-color: #0056b3; /* Couleur du bouton au survol */
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <img src="img/management-icon-teamwork-business-team-260nw-1051689107.webp" alt="Logo" class="sidebar-logo">
        <h2>Tableau de bord</h2>
        <a href="admin.php">Tableau de bord</a>
        <a href="product_history.php">Historique des produits</a>
        <a href="suppliers.php">Fournisseurs</a>
        <a href="logout.php">Déconnexion</a>
    </div>
    <div class="main-content">
        <h1>Ajouter un Produit</h1>

        <form method="POST" action="add_product_process.php">
            <label for="name">Nom du produit:</label>
            <input type="text" id="name" name="name" required>

            <label for="price">Prix:</label>
            <input type="number" id="price" name="price" required>

            <label for="quantity">Quantité:</label>
            <input type="number" id="quantity" name="quantity" required>

            <input type="submit" value="Ajouter Produit">
        </form>
    </div>
</body>
</html>
