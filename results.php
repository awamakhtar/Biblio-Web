<?php
require_once 'php/config.php';

// Récupérer les critères de recherche
$titre = isset($_GET['titre']) ? trim($_GET['titre']) : '';
$auteur = isset($_GET['auteur']) ? trim($_GET['auteur']) : '';

// Construction de la requête SQL
$sql = "SELECT * FROM livres WHERE 1=1";

if(!empty($titre)) {
    $titre_safe = mysqli_real_escape_string($connexion, $titre);
    $sql .= " AND titre LIKE '%$titre_safe%'";
}

if(!empty($auteur)) {
    $auteur_safe = mysqli_real_escape_string($connexion, $auteur);
    $sql .= " AND auteur LIKE '%$auteur_safe%'";
}

$sql .= " ORDER BY titre ASC";

$result = mysqli_query($connexion, $sql);
$nombre_resultats = mysqli_num_rows($result);

// les images
$images_livres = [
    1 => 'petit-prince.jpg',
    2 => '1984.jpg',
    3 => 'etranger.jpg',
    4 => 'aventure-ambigue.jpg',
    5 => 'germinal.jpg',
    6 => 'sous-orage.jpg'
];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <title>Résultats de recherche - Biblio Web</title>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <h1 class="logo">📚 Biblio Web</h1>
        
        <ul class="nav-link">
            <li><a href="index.php">Accueil</a></li>
            <li><a href="wishlist.php">Liste de Lecture</a></li>
            <li><a href="apropos.php">À propos</a></li>
        </ul> 
        
        <div class="hamburger">
            <i class="fa-solid fa-bars"></i>
        </div>
        
        <button class="button">
            <span class="span">🔎</span>
        </button>
    </nav>

    <!-- Hero Section -->
    <header class="header-results">
        <div class="hero-text">
            <h1>🔍 Résultats de recherche</h1>
            <p>
                <?php 
                if(!empty($titre) && !empty($auteur)) {
                    echo "Recherche : \"$titre\" par \"$auteur\"";
                } elseif(!empty($titre)) {
                    echo "Recherche : \"$titre\"";
                } elseif(!empty($auteur)) {
                    echo "Recherche par auteur : \"$auteur\"";
                } else {
                    echo "Tous les livres disponibles";
                }
                ?>
            </p>
        </div>
    </header>

    <main class="main-results">
        <div class="container-results">
            
            <!-- Statistiques de recherche -->
            <div class="results-stats">
                <div class="stat-info">
                    <i class="fa-solid fa-book"></i>
                    <span><strong><?php echo $nombre_resultats; ?></strong> résultat(s) trouvé(s)</span>
                </div>
                <a href="index.php" class="btn-back">
                    <i class="fa-solid fa-arrow-left"></i>
                    Nouvelle recherche
                </a>
            </div>

            <?php if($nombre_resultats == 0): ?>
                <!-- Aucun résultat -->
                <div class="no-results">
                    <i class="fa-solid fa-search"></i>
                    <h2>Aucun résultat trouvé</h2>
                    <p>Essayez avec d'autres mots-clés ou parcourez notre collection complète.</p>
                    <a href="index.php" class="btn-discover">
                        <i class="fa-solid fa-home"></i>
                        Retour à l'accueil
                    </a>
                </div>
            <?php else: ?>
                <!-- Grille des résultats -->
                <div class="results-grid">
                    <?php while($livre = mysqli_fetch_assoc($result)): ?>
                        <?php 
                            $image_livre = isset($images_livres[$livre['id']]) ? $images_livres[$livre['id']] : 'default-book.jpg';
                        ?>
                        
                        <div class="book-card">
                            <div class="book-image">
                                <img src="images/<?php echo $image_livre; ?>" 
                                     alt="<?php echo htmlspecialchars($livre['titre']); ?>"
                                     onerror="this.src='images/default-book.jpg'">
                                <div class="book-overlay">
                                    <a href="details.php?id=<?php echo $livre['id']; ?>" class="btn-details">
                                        <i class="fa-solid fa-eye"></i>
                                        Voir détails
                                    </a>
                                </div>
                            </div>
                            <div class="book-info">
                                <h3 class="book-title"><?php echo htmlspecialchars($livre['titre']); ?></h3>
                                <p class="book-author">
                                    <i class="fa-solid fa-user"></i>
                                    <?php echo htmlspecialchars($livre['auteur']); ?>
                                </p>
                                <p class="book-description">
                                    <?php echo htmlspecialchars(substr($livre['description'], 0, 100)) . '...'; ?>
                                </p>
                                <div class="book-footer">
                                    <span class="book-available">
                                        <i class="fa-solid fa-book"></i>
                                        <?php echo $livre['nombre_exemplaire']; ?> disponibles
                                    </span>
                                    <button class="btn-wishlist" onclick="ajouterALaListe(<?php echo $livre['id']; ?>)">
                                        <i class="fa-solid fa-heart"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <script src="js/script.js"></script>
</body>
</html>

<?php
mysqli_close($connexion);
?>