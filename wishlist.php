<?php
require_once 'php/config.php';


$id_lecteur = 1; 

// Requête pour récupérer la liste de lecture
$sql = "SELECT 
            livres.id,
            livres.titre, 
            livres.auteur,
            livres.nombre_exemplaire,
            liste_lecture.date_emprunt,
            liste_lecture.date_retour,
            liste_lecture.id_lecteur
        FROM liste_lecture
        INNER JOIN livres ON liste_lecture.id_livre = livres.id
        WHERE liste_lecture.id_lecteur = $id_lecteur
        ORDER BY liste_lecture.date_emprunt DESC";

$result = mysqli_query($connexion, $sql);

// Définir $nombre_livres ICI
$nombre_livres = mysqli_num_rows($result);

// Tableau des images (comme dans details.php)
$images_livres = [
    1 => 'peti-prince.jpg',
    2 => '1984.jpg',
    3 => 'etranger.jpg',
    4 => 'aventure.jpg',
    5 => 'germinal.jpg',
    6 => 'sous-orage.jpg'
];



?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <title>Liste de lecture</title>
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
    <header class="header-wishlist">
        <div class="hero-text">
            <h1>📚 Ma Liste de Lecture</h1>
            <p>Les livres que vous avez ajoutés
                 à votre liste de lecture</p>
        </div>
    </header>

    <!-- Contenu Principal -->
    <main class="main-wishlist">
        <div class="container-wishlist">
            <?php if(isset($_GET['message'])): ?>
                <div class="alert-message">
                    <?php echo htmlspecialchars($_GET['message']); ?>
                </div>
        <?php endif; ?>
            <!-- Statistiques -->
            <div class="wishlist-stats">
                <div class="stat-item">
                    <i class="fa-solid fa-book"></i>
                    <div>
                        <span class="stat-number"><?php echo $nombre_livres; ?></span>
                        <span class="stat-label">Livre(s) dans votre liste</span>
                    </div>
                </div>
            </div>

            <?php if($nombre_livres == 0): ?>
                <!-- Message si la liste est vide -->
                <div class="empty-wishlist">
                    <i class="fa-solid fa-book-open"></i>
                    <h2>Votre liste de lecture est vide</h2>
                    <p>Commencez à explorer notre collection et ajoutez vos livres préférés !</p>
                    <a href="index.php" class="btn-discover">
                        <i class="fa-solid fa-compass"></i>
                        Découvrir des livres
                    </a>
                </div>
            <?php else: ?>
                <!-- Liste des livres -->
                <div class="wishlist-books">
                    <?php while($livre = mysqli_fetch_assoc($result)): ?>
                        <?php 
                            $image_livre = isset($images_livres[$livre['id']]) ? $images_livres[$livre['id']] : 'default-book.jpg';
                            
                            // Formater les dates
                            $date_emprunt = date('d/m/Y', strtotime($livre['date_emprunt']));
                            $date_retour = $livre['date_retour'] ? date('d/m/Y', strtotime($livre['date_retour'])) : 'Non définie';                       
                            // Vérifier si le livre est en retard
                            $en_retard = false;
                            if($livre['date_retour']) {
                                $aujourd_hui = new DateTime();
                                $date_limite = new DateTime($livre['date_retour']);
                                $en_retard = $aujourd_hui > $date_limite;
                            }
                        ?>
                        
                        <div class="wishlist-card">
                            <div class="wishlist-book-image">
                                <img src="images/<?php echo $image_livre; ?>" 
                                     alt="<?php echo htmlspecialchars($livre['titre']); ?>"
                                     onerror="this.src='images/default-book.jpg'">
                                <?php if($en_retard): ?>
                                    <div class="late-badge">
                                        <i class="fa-solid fa-exclamation-triangle"></i>
                                        En retard
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="wishlist-book-info">
                                <h3 class="wishlist-book-title">
                                    <?php echo htmlspecialchars($livre['titre']); ?>
                                </h3>
                                
                                <p class="wishlist-book-author">
                                    <i class="fa-solid fa-user"></i>
                                    <?php echo htmlspecialchars($livre['auteur']); ?>
                                </p>

                                <div class="wishlist-dates">
                                    <div class="date-item">
                                        <i class="fa-solid fa-calendar-plus"></i>
                                        <span>Emprunté le : <strong><?php echo $date_emprunt; ?></strong></span>
                                    </div>
                                    <div class="date-item <?php echo $en_retard ? 'late' : ''; ?>">
                                        <i class="fa-solid fa-calendar-check"></i>
                                        <span>À retourner : <strong><?php echo $date_retour; ?></strong></span>
                                    </div>
                                </div>

                                <div class="wishlist-actions">
                                    <a href="details.php?id=<?php echo $livre['id']; ?>" class="btn-view-details">
                                        <i class="fa-solid fa-eye"></i>
                                        Voir détails
                                    </a>
                                    <button onclick="confirmerSuppression(<?php echo $livre['id']; ?>, <?php echo $livre['id_lecteur']; ?>)" class="btn-remove">
                                        <i class="fa-solid fa-trash"></i>
                                        Retirer
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php endif; ?>
        </div>

        
   


    <script src="js/script.js"></script>
</body>
</html>