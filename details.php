<?php
session_start();
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

// Tableau des images
$images_livres = [
    1 => 'petit-prince.jpg',
    2 => '1984.jpg',
    3 => 'etranger.jpg',
    4 => 'aventure-ambigue.jpg',
    5 => 'germinal.jpg',
    6 => 'sous-orage.jpg'
];

// Si l'ID n'est pas dans le tableau, utiliser l'image par défaut
$image_livre = isset($images_livres[$livre['id']]) ? $images_livres[$livre['id']] : 'default-book.jpg';

// Vérifier si le livre est déjà dans la liste de l'utilisateur (si connecté)
$deja_dans_liste = false;
if(isset($_SESSION['id_lecteur'])) {
    $id_lecteur = $_SESSION['id_lecteur'];
    $check_sql = "SELECT * FROM liste_lecture 
                  WHERE id_livre = {$livre['id']} AND id_lecteur = $id_lecteur";
    $check_result = mysqli_query($connexion, $check_sql);
    $deja_dans_liste = mysqli_num_rows($check_result) > 0;
}
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
                                    <?php echo $livre['nombre_exemplaire']; ?> exemplaire(s)
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
                        
                        <?php if(isset($_SESSION['id_lecteur'])): ?>
                            <?php if($deja_dans_liste): ?>
                                <button class="btn-primary btn-disabled" disabled>
                                    <i class="fa-solid fa-check"></i>
                                    Déjà dans votre liste
                                </button>
                            <?php else: ?>
                                <button class="btn-primary" onclick="openAddModal()">
                                    <i class="fa-solid fa-heart"></i>
                                    Ajouter à ma liste
                                </button>
                            <?php endif; ?>
                        <?php else: ?>
                            <button class="btn-primary" onclick="redirectToLogin()">
                                <i class="fa-solid fa-heart"></i>
                                Ajouter à ma liste
                            </button>
                        <?php endif; ?>
                        
                    </div>
                </div>
            </section>
        </div>
    </main>

    <!-- Modal pour choisir les dates -->
    <div id="addToListModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>
                    <i class="fa-solid fa-calendar"></i>
                    Ajouter à ma liste de lecture
                </h2>
                <button class="modal-close" onclick="closeAddModal()">
                    <i class="fa-solid fa-times"></i>
                </button>
            </div>
            
            <div class="modal-body">
                <p class="modal-book-title">
                    <strong><?php echo htmlspecialchars($livre['titre']); ?></strong>
                </p>
                <p class="modal-book-author">par <?php echo htmlspecialchars($livre['auteur']); ?></p>
                
                <form id="addToListForm" method="POST" action="php/add_to_wishlist.php">
                    <input type="hidden" name="id_livre" value="<?php echo $livre['id']; ?>">
                    
                    <div class="form-group">
                        <label for="date_emprunt">
                            <i class="fa-solid fa-calendar-plus"></i>
                            Date d'emprunt
                        </label>
                        <input type="date" id="date_emprunt" name="date_emprunt" required
                               
                               value="<?php echo date('Y-m-d'); ?>">
                        <small>La date d'emprunt ne peut pas être dans le passé</small>
                    </div>

                    <div class="form-group">
                        <label for="date_retour">
                            <i class="fa-solid fa-calendar-check"></i>
                            Date de retour prévue
                        </label>
                        <input type="date" id="date_retour" name="date_retour" required>
                               
                        <small>Choisissez quand vous pensez finir ce livre</small>
                    </div>

                    <div class="duree-info" id="dureeInfo" style="display: none;">
                        <i class="fa-solid fa-clock"></i>
                        <span id="dureeText"></span>
                    </div>

                    <div class="modal-actions">
                        <button type="button" class="btn-modal-cancel" onclick="closeAddModal()">
                            <i class="fa-solid fa-times"></i>
                            Annuler
                        </button>
                        <button type="submit" class="btn-modal-submit">
                            <i class="fa-solid fa-plus"></i>
                            Ajouter à ma liste
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- <script src="js/script.js"></script> -->
    <script>
        // Ouvrir le modal
        function openAddModal() {
            document.getElementById('addToListModal').classList.add('active');
        }

        // Fermer le modal
        function closeAddModal() {
            document.getElementById('addToListModal').classList.remove('active');
        }

        // Redirection vers login si non connecté
        function redirectToLogin() {
            if(confirm('Vous devez être connecté pour ajouter un livre à votre liste. Voulez-vous vous connecter ?')) {
                window.location.href = 'login.php';
            }
        }

        // Fermer le modal si on clique en dehors
        document.getElementById('addToListModal')?.addEventListener('click', function(e) {
            if(e.target === this) {
                closeAddModal();
            }
        });

        // Fermer avec la touche Escape
        document.addEventListener('keydown', function(e) {
            if(e.key === 'Escape') {
                closeAddModal();
            }
        });

        // Calculer la durée entre les dates
        const dateEmprunt = document.getElementById('date_emprunt');
        const dateRetour = document.getElementById('date_retour');
        const dureeInfo = document.getElementById('dureeInfo');
        const dureeText = document.getElementById('dureeText');

        function calculerDuree() {
            if(dateEmprunt.value && dateRetour.value) {
                const debut = new Date(dateEmprunt.value);
                const fin = new Date(dateRetour.value);
                const diffTime = fin - debut;
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

                if(diffDays <= 0) {
                    dureeInfo.style.display = 'none';
                    dateRetour.setCustomValidity('La date de retour doit être après la date d\'emprunt');
                } else {
                    dureeInfo.style.display = 'flex';
                    dureeText.textContent = `Durée de lecture : ${diffDays} jour${diffDays > 1 ? 's' : ''}`;
                    dateRetour.setCustomValidity('');
                }
            }
        }

        dateEmprunt?.addEventListener('change', calculerDuree);
        dateRetour?.addEventListener('change', calculerDuree);

    </script>
</body>
</html>

<?php
mysqli_close($connexion);
?>