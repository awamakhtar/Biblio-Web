<?php
require_once 'php/config.php';

// Récupérer l'ID du livre depuis l'URL
$id_livre = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if($id_livre == 0) {
    header('Location: index.php');
    exit();
}

// Requête pour récupérer les infos du livre (avec mysqli)
$sql = "SELECT * FROM livres WHERE id = $id_livre";
$result = mysqli_query($connexion, $sql);

// Vérifier si le livre existe
if(mysqli_num_rows($result) == 0) {
    header('Location: index.php');
    exit();
}

// Récupérer les données du livre
$livre = mysqli_fetch_assoc($result);

// 🖼️ Correspondance ID → nom d'image
$images_livres = [
    1 => 'peti-prince.jpg',
    2 => '1984.jpg',
    3 => 'etranger.jpg',
    4 => 'aventure.jpg',
    5 => 'germinal.jpg',
    6 => 'sous-orage.jpg'
];

// Si l'ID n'est pas dans le tableau, utiliser l'image par défaut
$image_livre = isset($images_livres[$livre['id']]) ? $images_livres[$livre['id']] : 'default-book.jpg';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <title><?php echo htmlspecialchars($livre['titre']); ?> - Biblio Web</title>
</head>
<body>
    <!-- Navbar -->
    <!-- <nav class="navbar"> -->
        <!-- <h1 class="logo">📚 Biblio Web</h1> -->
<!--          -->
        <!-- <ul class="nav-link"> -->
            <!-- <li><a href="index.php">Accueil</a></li> -->
            <!-- <li><a href="wishlist.php">Liste de Lecture</a></li> -->
            <!-- <li><a href="apropos.php">À propos</a></li> -->
        <!-- </ul>  -->
<!--          -->
        <!-- <div class="hamburger"> -->
            <!-- <i class="fa-solid fa-bars"></i> -->
        <!-- </div> -->
<!--          -->
        <!-- <button class="button"> -->
            <!-- <span class="span">🔎</span> -->
        <!-- </button> -->
    <!-- </nav> -->
        <?php include 'includes/navbar.php'; ?>

    <!-- Hero Section -->
    <header class="header-details">
        <div class="hero-text">
            <h1>📖 Détails du Livre</h1>
            <p>Découvrez toutes les informations sur ce livre</p>
        </div>
    </header>

    <!-- Section Détails du Livre -->
    <main class="main-details">
        <div class="container-details">
            <section class="book-details">
                <!-- Image de couverture -->
                <div class="book-cover">
                    <img src="images/<?php echo $image_livre; ?>" 
                         alt="<?php echo htmlspecialchars($livre['titre']); ?>"
                         onerror="this.src='images/default-book.jpg'">
                    <div class="book-badge">
                        <i class="fa-solid fa-star"></i>
                        Populaire
                    </div>
                </div>

                <!-- Informations du livre -->
                <div class="book-info-details">
                    <div class="breadcrumb">
                        <a href="index.php">
                            <i class="fa-solid fa-home"></i>
                            Accueil
                        </a>
                        <span>/</span>
                        <span>Détails</span>
                    </div>

                    <h1 class="book-title-details">
                        <?php echo htmlspecialchars($livre['titre']); ?>
                    </h1>
                    
                    <p class="book-author-details">
                        <i class="fa-solid fa-user"></i>
                        <?php echo htmlspecialchars($livre['auteur']); ?>
                    </p>

                    <!-- Métadonnées -->
                    <div class="book-meta">
                        <div class="meta-item">
                            <i class="fa-solid fa-building"></i>
                            <div>
                                <span class="meta-label">Maison d'édition</span>
                                <span class="meta-value">
                                    <?php echo htmlspecialchars($livre['maison_edition']); ?>
                                </span>
                            </div>
                        </div>
                        <div class="meta-item">
                            <i class="fa-solid fa-book"></i>
                            <div>
                                <span class="meta-label">Disponibilité</span>
                                <span class="meta-value available">
                                    <?php echo $livre['nombre_exemplaire']; ?> exemplaires
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="book-description-details">
                        <h3>
                            <i class="fa-solid fa-align-left"></i>
                            Description
                        </h3>
                        <p>
                            <?php echo nl2br(htmlspecialchars($livre['description'])); ?>
                        </p>
                    </div>

                    <!-- Boutons d'action -->
                    <div class="book-actions">
                            <?php if(!empty($livre['fichier_pdf'])): ?>
                                <a href="read.php?id=<?php echo $livre['id']; ?>" class="btn-read">
                                    <i class="fa-solid fa-book-open"></i>
                                        Lire en ligne
                                </a>
                            <?php endif; ?>

                            
                        <button class="btn-primary" onclick="ajouterALaListe(<?php echo $livre['id']; ?>)">
                            <i class="fa-solid fa-heart"></i>
                            Ajouter à ma liste
                        </button>
                    </div>
                </div>
            </section>
        </div>
    </main>

    <script src="js/script.js"></script>
    <script>
      <!-- Fonction pour ajouter un livre à la liste -->
    function ajouterALaListe(idLivre) {
        <?php if(isset($_SESSION['id_lecteur'])): ?>
             <!-- Utilisateur connecté : ajouter à sa liste -->
            window.location.href = `php/add_to_wishlist.php?id_livre=${idLivre}`;
        <?php else: ?>
             <!-- Utilisateur non connecté : rediriger vers login -->
            if(confirm('Vous devez être connecté pour ajouter un livre à votre liste. Voulez-vous vous connecter ?')) {
                window.location.href = 'login.php';
            }
        <?php endif; ?>
         }
    </script>
   
</body>
</html>

<?php
// Fermer la connexion
mysqli_close($connexion);
?>