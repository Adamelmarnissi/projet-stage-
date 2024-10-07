<?php
session_start(); // Démarre la session en premier

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

// Requête pour récupérer les produits
$sql = "SELECT * FROM products"; // Assurez-vous que le nom de la table est correct
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historique des Produits</title>
    <link rel="stylesheet" href="history.css"> <!-- Lien vers votre fichier CSS -->
    <style>
        .main-content {
    width: 80%; /* Ajustez la largeur selon vos besoins */
    max-width: 1000px; /* Limite la largeur maximale */
    background-color: white; /* Couleur de fond */
    padding: 20px; /* Espacement interne */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Ombre pour donner de la profondeur */
    border-radius: 8px; /* Coins arrondis */
}

  body {
    display: flex; 
    margin: 0;
    background-color: #f4f4f4; /* Optionnel : couleur de fond */
}



/* Style de la barre latérale */
/* Style de la sidebar */
.sidebar {
    width: 250px;
    height: 100vh;
    background-color: #2c3e50; /* Bleu foncé élégant */
    color: #ecf0f1; /* Blanc légèrement grisé pour un contraste doux */
    padding: 20px;
    text-align: center;
    box-shadow: 2px 0 5px rgba(0, 0, 0, 0.2); /* Ombre subtile */
}


/* Logo de la sidebar */
.sidebar-logo {
    width: 100px;
    height: auto;
    margin-bottom: 20px;
    border-radius: 50%; /* Donne une forme arrondie à l'image */
    border: 3px solid #ffffff; /* Ajoute une bordure blanche autour de l'image */
}

/* Style du titre H2 */
.sidebar h2 {
    font-size: 1.5rem;
    margin-bottom: 30px;
    color: #f8f9fa; /* Légèrement plus clair pour le contraste */
}

/* Style des liens dans la sidebar */
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

/* Effet hover sur les liens */
.sidebar a:hover {
    background-color: #0056b3;
    transform: scale(1.05); /* Augmente légèrement la taille des liens au survol */
}
  /* Styles généraux du tableau */
.product-table {
    width: 80%; /* Ajustez la largeur selon vos besoins */
    border-collapse: collapse;
    margin: 20px auto; /* Centre horizontalement le tableau */
    background-color:gris
    color: white; /* Couleur du texte pour contraste */
}

/* Styles des cellules */
.product-table th, .product-table td {
    padding: 10px; /* Espace intérieur des cellules */
    border: 1px solid white; /* Bordure blanche pour bien délimiter les cellules */
}

/* En-têtes du tableau */
.product-table th {
    background-color: #2c3e50; /* Couleur plus foncée pour l'en-tête du tableau */
    font-weight: bold; /* Texte en gras */
    color: white;
}

/* Lignes impaires avec une couleur légèrement différente */



        /* Style pour les boutons */
        .button-container {
            margin-bottom: 20px;
        }
        .button-container input {
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 5px;
    width: 200px;
}


       /* Style pour le bouton "Ajouter" */
.button-container button {
    padding: 10px 15px;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    margin-right: 10px;
    transition: background-color 0.3s ease; /* Ajout de la transition */
}

.button-container button:hover {
    background-color: #0056b3; /* Couleur de fond au survol */
}

/* Style pour le bouton "Modifier" en vert */
button.modify-btn {
    background-color: #28a745; /* Vert */
    color: white;
    border: none;
    border-radius: 5px;
    padding: 8px 15px;
    cursor: pointer;
    transition: background-color 0.3s ease; /* Ajout de la transition */
}

button.modify-btn:hover {
    background-color: #218838; /* Vert plus foncé au survol */
}

/* Style pour le bouton "Supprimer" en rouge */
button.delete-btn {
    background-color: #dc3545; /* Rouge */
    color: white;
    border: none;
    border-radius: 5px;
    padding: 8px 15px;
    cursor: pointer;
    transition: background-color 0.3s ease; /* Ajout de la transition */
}

button.delete-btn:hover {
    background-color: #c82333; /* Rouge plus foncé au survol */
}

/* Style pour le bouton "Rechercher" en bleu clair */
button.search-btn {
    background-color: #17a2b8; /* Bleu clair */
    color: white;
    border: none;
    border-radius: 5px;
    padding: 8px 15px;
    cursor: pointer;
    transition: background-color 0.3s ease; /* Ajout de la transition */
}

button.search-btn:hover {
    background-color: #138496; /* Bleu clair plus foncé au survol */
}

        .action-buttons {
            display: flex;
            gap: 5px;
        }
        /* Styles pour la modal */
        .modal {
            display: none; /* Cacher par défaut */
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto; /* Activer le défilement si nécessaire */
            background-color: rgba(0,0,0,0.4); /* Avec transparence */
            padding-top: 60px;
        }
        .modal-content {
            background-color: #fefefe;
            margin: 15% auto; /* Réduire la marge */
            padding: 10px; /* Réduire le padding */
            border: 1px solid #888;
            width: 30%; /* Largeur de la modal ajustée */
            max-width: 400px; /* Largeur maximum */
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
    <script>
        function openModal(id, name, price, quantity) {
            const modal = document.getElementById("myModal");
            const form = document.getElementById("editForm");
            
            // Remplissage des champs
            document.getElementById("product_id").value = id; // Remplir le champ caché avec l'ID
            document.getElementById("name").value = name; 
            document.getElementById("price").value = price; 
            document.getElementById("quantity").value = quantity; 

            modal.style.display = "block"; // Afficher le modal
        }

        function closeModal() {
            const modal = document.getElementById("myModal");
            modal.style.display = "none"; // Cacher le modal
        }

        // Fermer la modal si l'utilisateur clique à l'extérieur de celle-ci
        window.onclick = function(event) {
            const modal = document.getElementById("myModal");
            if (event.target == modal) {
                modal.style.display = "none"; // Cacher le modal
            }
        }

        function confirmDelete(id) {
            if (confirm("Êtes-vous sûr de vouloir supprimer ce produit ?")) {
                window.location.href = 'delete_product.php?id=' + id; // Rediriger vers la page de suppression
            }
        }
    
    function searchProduct() {
        // Récupérer la valeur de l'input de recherche
        var input = document.getElementById("searchInput");
        var filter = input.value.toLowerCase();
        
        // Récupérer toutes les lignes du tableau (excepté l'en-tête)
        var table = document.querySelector(".product-table tbody");
        var tr = table.getElementsByTagName("tr");

        // Boucler à travers chaque ligne du tableau
        for (var i = 0; i < tr.length; i++) {
            // Récupérer toutes les cellules de chaque ligne
            var td = tr[i].getElementsByTagName("td");

            // Initialiser une variable de correspondance
            var match = false;

            // Boucle à travers les cellules de la ligne
            for (var j = 0; j < td.length - 1; j++) { // -1 pour ne pas inclure la dernière colonne "Action"
                if (td[j]) {
                    // Si le texte de la cellule correspond au filtre
                    if (td[j].innerText.toLowerCase().indexOf(filter) > -1) {
                        match = true;
                        break;
                    }
                }
            }

            // Afficher ou masquer la ligne en fonction du résultat de la recherche
            if (match) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }
    }
</script>

</head>
<body>
<div class="sidebar">
    <img src="img/management-icon-teamwork-business-team-260nw-1051689107.webp" alt="Logo" class="sidebar-logo"> <!-- Remplacez par le chemin de votre image -->
    <h2>Tableau de bord</h2>
    <a href="admin.php">Tableau de bord</a>
    <a href="product_history.php">Historique des produits</a>
    <a href="suppliers.php">Fournisseurs</a>
    <a href="logout.php">Déconnexion</a>
   
</div>
<div class="main-content">
    <center><h1>Historique des Produits</h1></center>
    <div class="button-container">
        <button onclick="location.href='add_product.php'">Ajouter produit</button>
        
        <input type="text" id="searchInput" onkeyup="searchProduct()" placeholder="Rechercher dans le tableau..." style="padding: 10px; margin-left: 10px;">
    </div>

    <table class="product-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Prix</th>
                <th>Quantité</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Affichage des produits
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                    echo "<td>" . (!empty($row['name']) ? htmlspecialchars($row['name']) : 'Non spécifié') . "</td>"; 
                    echo "<td>" . htmlspecialchars($row['price']) . " €</td>";
                    echo "<td>" . htmlspecialchars($row['quantity']) . "</td>";
                    echo "<td class='action-buttons'>";
                    
                    // Bouton Modifier
                    echo '<button class="modify-btn" onclick="openModal(' . htmlspecialchars($row['id']) . ', \'' . htmlspecialchars($row['name']) . '\', ' . htmlspecialchars($row['price']) . ', ' . htmlspecialchars($row['quantity']) . ')">Modifier</button>';

                    // Bouton Supprimer
                    echo "<button class='delete-btn' onclick=\"confirmDelete(" . htmlspecialchars($row['id']) . ")\">Supprimer</button>";
                    
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>Aucun produit trouvé</td></tr>";
            }
            ?>
        </tbody>
    </table>
    
    <!-- Bouton "Revenir au tableau de bord" -->
    <div class="button-container" style="margin-top: 20px; text-align: center;">
        <button onclick="location.href='admin.php'" style="background-color: #007bff; color: white; padding: 10px 15px; border: none; border-radius: 5px; cursor: pointer;">Revenir au tableau de bord</button>
    </div>
</div>


    <!-- Modal pour modifier le produit -->
    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2>Modifier le produit</h2>
            <form id="editForm" method="POST" action="edit_product.php">
                <input type="hidden" name="product_id" id="product_id"> <!-- Champ caché pour l'ID -->
                <label for="name">Nom:</label>
                <input type="text" name="name" id="name" required>
                
                <label for="price">Prix:</label>
                <input type="number" name="price" id="price" required>
                
                <label for="quantity">Quantité:</label>
                <input type="number" name="quantity" id="quantity" required>

                <button type="submit">Sauvegarder</button>
            </form>
        </div>
    </div>
    

</body>
</html>
