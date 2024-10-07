<?php
session_start();

// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['admin_name'])) {
    header("Location: login.php"); // Redirigez vers la page de connexion si non connecté
    exit();
}

// Connexion à la base de données
$mysqli = new mysqli("localhost", "root", "", "gstock");

// Vérifiez la connexion
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Récupérer les produits achetés
$query = "SELECT p.name, SUM(d.quantity) as total_purchased
          FROM purchases d
          JOIN products p ON d.product_id = p.id
          GROUP BY p.name";

$result = $mysqli->query($query);

// Vérifiez si la requête a réussi
if (!$result) {
    die("Query failed: " . $mysqli->error);
}

// Initialiser les tableaux pour les labels et les données
$labels = [];
$data = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $labels[] = $row['name'];
        $data[] = $row['total_purchased'];
    }
}

// Récupérer les produits disponibles pour le tableau (mais pas encore affiché)
$query_stock = "SELECT name, quantity FROM products";
$result_stock = $mysqli->query($query_stock);

// Vérifiez si la requête a réussi
if (!$result_stock) {
    die("Query failed: " . $mysqli->error);
}

// Initialiser un tableau pour les stocks
$stocks = [];
if ($result_stock->num_rows > 0) {
    while ($row = $result_stock->fetch_assoc()) {
        $stocks[] = $row;
    }
}

$mysqli->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration</title>
    
    <style>
        /* admin.css */



/* Style du corps de la page */
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




/* Contenu principal */

/* Style pour les titres */


/* Style du graphique */


/* Style des tableaux */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
    display: none; /* Cacher le tableau par défaut */
    background-color: #f9f9f9; /* Fond doux */
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1); /* Ombre pour donner un effet d'élévation */
}

th, td {
    border: 1px solid #bdc3c7; /* Bordures gris clair pour plus de douceur */
    padding: 12px 15px;
    text-align: left;
    font-size: 14px; /* Taille de police légèrement plus petite */
}

th {
    background-color: #34495e; /* Bleu foncé/grisé pour un look professionnel */
    color: white; /* Texte blanc pour contraste */
    font-weight: 600; /* Texte en gras pour les en-têtes */
}

td {
    background-color: #ffffff; /* Fond blanc pour les cellules */
    color: #2c3e50; /* Texte sombre pour lisibilité */
}

tr:nth-child(even) td {
    background-color: #ecf0f1; /* Lignes de fond alternées pour améliorer la lisibilité */
}

tr:hover td {
    background-color: #e1e8ed; /* Changement de couleur au survol pour interactivité */
}

caption {
    caption-side: bottom;
    padding-top: 10px;
    font-size: 12px;
    color: #7f8c8d; /* Légère mention pour donner plus de contexte au tableau */
}


/* Style du bouton */
button {
    background-color: #007bff;
    color: white;
    border: none;
    padding: 10px 15px;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s; /* Animation pour le changement de couleur */
}

button:hover {
    background-color: #0056b3; /* Couleur du bouton au survol */
}

       
        .main-content {
            flex: 1;
            padding: 20px;
        }

        #myChart {
    max-width: 100%; /* S'assurer que le graphique ne dépasse pas la largeur du conteneur */
    height: auto; /* Permet au graphique de garder sa proportion */
    margin: 0 auto; /* Centre le graphique */
   
}


      
    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- Inclure Chart.js -->
    <script>
        function toggleStockTable() {
            const table = document.getElementById('stockTable');
            // Basculer l'affichage du tableau
            if (table.style.display === 'none' || table.style.display === '') {
                table.style.display = 'table'; // Afficher le tableau
            } else {
                table.style.display = 'none'; // Cacher le tableau
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
    <button onclick="toggleStockTable()">Voir le stock</button> <!-- Bouton pour voir le stock -->
</div>

    <div class="main-content">
        <center><h1>Bienvenue Mr <?php echo $_SESSION['admin_name']; ?></h1></center>
        

        <h2>Produits achetés par les utilisateurs</h2>
        <canvas id="myChart" width="330" ></canvas>

        <script>
    const labels = <?php echo json_encode($labels); ?>; // Données pour les labels
    const data = <?php echo json_encode($data); ?>; // Données pour les valeurs

    // Définir un tableau de couleurs
    const colors = [
        'rgba(75, 192, 192, 0.2)',
        'rgba(255, 99, 132, 0.2)',
        'rgba(255, 206, 86, 0.2)',
        'rgba(54, 162, 235, 0.2)',
        'rgba(153, 102, 255, 0.2)',
        'rgba(255, 159, 64, 0.2)',
        'rgba(75, 192, 192, 0.4)',
        'rgba(255, 99, 132, 0.4)',
        'rgba(255, 206, 86, 0.4)',
        'rgba(54, 162, 235, 0.4)',
        'rgba(153, 102, 255, 0.4)',
        'rgba(255, 159, 64, 0.4)'
    ];

    // Assurez-vous que le nombre de couleurs est au moins égal au nombre de produits
    const backgroundColors = colors.slice(0, labels.length);
    const borderColors = colors.map(color => color.replace('0.2', '1')); // Utiliser des couleurs plus vives pour les bordures

    const ctx = document.getElementById('myChart').getContext('2d');
    const myChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: labels,
            datasets: [{
                label: 'Produits achetés',
                data: data,
                backgroundColor: backgroundColors,
                borderColor: borderColors,
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 2
                    }
                }
            }
        }
    });
</script>


        
        <table id="stockTable">
            <thead>
                <tr>
                    <th>Nom du Produit</th>
                    <th>Quantité Disponible</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($stocks)): ?>
                    <?php foreach ($stocks as $stock): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($stock['name']); ?></td>
                            <td><?php echo htmlspecialchars($stock['quantity']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="2">Aucun produit disponible dans les stocks.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
