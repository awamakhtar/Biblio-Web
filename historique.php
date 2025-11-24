<?php
session_start();
require_once 'php/config.php';

// Mettre à jour automatiquement les statuts
require_once 'php/update_statuts.php';


// Vérifier si l'utilisateur est connecté
if(!isset($_SESSION['id_lecteur'])) {
    header('Location: login.php');
    exit();
}

$id_lecteur = $_SESSION['id_lecteur'];

// Filtre par statut
$filtre = isset($_GET['filtre']) ? $_GET['filtre'] : 'tous';

// Construction de la requête SQL
$sql = "SELECT 
            livres.id,
            livres.titre, 
            livres.auteur,
            liste_lecture.date_emprunt,
            liste_lecture.date_retour,
            liste_lecture.statut,
            DATEDIFF(CURDATE(), liste_lecture.date_emprunt) as jours_depuis_emprunt
        FROM liste_lecture
        INNER JOIN livres ON liste_lecture.id_livre = livres.id
        WHERE liste_lecture.id_lecteur = $id_lecteur";

// Appliquer le filtre
if($filtre == 'en_cours') {
    $sql .= " AND liste_lecture.statut = 'en_cours'";
} elseif($filtre == 'termine') {
    $sql .= " AND liste_lecture.statut = 'termine'";
} elseif($filtre == 'en_retard') {
    $sql .= " AND liste_lecture.statut = 'en_retard'";
}

$sql .= " ORDER BY liste_lecture.date_emprunt DESC";

$result = mysqli_query($connexion, $sql);
$nombre_resultats = mysqli_num_rows($result);

// Statistiques globales
$stats_sql = "SELECT 
                COUNT(*) as total,
                COUNT(CASE WHEN statut = 'en_cours' THEN 1 END) as en_cours,
                COUNT(CASE WHEN statut = 'termine' THEN 1 END) as termine,
                COUNT(CASE WHEN statut = 'en_retard' THEN 1 END) as en_retard,
                MIN(date_emprunt) as premier_emprunt,
                MAX(date_emprunt) as dernier_emprunt
              FROM liste_lecture 
              WHERE id_lecteur = $id_lecteur";
$stats_result = mysqli_query($connexion, $stats_sql);
$stats = mysqli_fetch_assoc($stats_result);

// Tableau des images
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
    <title>Mon Historique - Biblio Web</title>
</head>
<body>
    <?php include 'includes/navbar.php'; ?>

    <!-- Hero Section -->
    <header class="header-historique">
        <div class="hero-text">
            <h1>📊 Mon Historique de Lecture</h1>
            <p>Suivez tous vos emprunts et lectures</p>
        </div>
    </header>

    <!-- Contenu Principal -->
    <main class="main-historique">
        <div class="container-historique">
            
            <!-- Statistiques globales -->
            <div class="historique-stats">
                <div class="stat-box-hist">
                    <div class="stat-icon-hist">
                        <i class="fa-solid fa-book"></i>
                    </div>
                    <div class="stat-content-hist">
                        <span class="stat-number-hist"><?php echo $stats['total']; ?></span>
                        <span class="stat-label-hist">Total emprunts</span>
                    </div>
                </div>

                <div class="stat-box-hist">
                    <div class="stat-icon-hist" style="background: #3498DB;">
                        <i class="fa-solid fa-book-open"></i>
                    </div>
                    <div class="stat-content-hist">
                        <span class="stat-number-hist"><?php echo $stats['en_cours']; ?></span>
                        <span class="stat-label-hist">En cours</span>
                    </div>
                </div>

                <div class="stat-box-hist">
                    <div class="stat-icon-hist" style="background: #27ae60;">
                        <i class="fa-solid fa-check-circle"></i>
                    </div>
                    <div class="stat-content-hist">
                        <span class="stat-number-hist"><?php echo $stats['termine']; ?></span>
                        <span class="stat-label-hist">Terminés</span>
                    </div>
                </div>

                <div class="stat-box-hist">
                    <div class="stat-icon-hist" style="background: #e74c3c;">
                        <i class="fa-solid fa-exclamation-triangle"></i>
                    </div>
                    <div class="stat-content-hist">
                        <span class="stat-number-hist"><?php echo $stats['en_retard']; ?></span>
                        <span class="stat-label-hist">En retard</span>
                    </div>
                </div>
            </div>

            <!-- Infos période -->
            <?php if($stats['total'] > 0): ?>
            <div class="periode-info">
                <i class="fa-solid fa-calendar"></i>
                Premier emprunt : <strong><?php echo date('d/m/Y', strtotime($stats['premier_emprunt'])); ?></strong>
                &nbsp;&nbsp;|&nbsp;&nbsp;
                Dernier emprunt : <strong><?php echo date('d/m/Y', strtotime($stats['dernier_emprunt'])); ?></strong>
            </div>
            <?php endif; ?>

            <!-- Filtres -->
            <div class="historique-filters">
                <a href="historique.php?filtre=tous" class="filter-btn <?php echo $filtre == 'tous' ? 'active' : ''; ?>">
                    <i class="fa-solid fa-list"></i>
                    Tous (<?php echo $stats['total']; ?>)
                </a>
                <a href="historique.php?filtre=en_cours" class="filter-btn <?php echo $filtre == 'en_cours' ? 'active' : ''; ?>">
                    <i class="fa-solid fa-book-open"></i>
                    En cours (<?php echo $stats['en_cours']; ?>)
                </a>
                <a href="historique.php?filtre=termine" class="filter-btn <?php echo $filtre == 'termine' ? 'active' : ''; ?>">
                    <i class="fa-solid fa-check-circle"></i>
                    Terminés (<?php echo $stats['termine']; ?>)
                </a>
                <a href="historique.php?filtre=en_retard" class="filter-btn <?php echo $filtre == 'en_retard' ? 'active' : ''; ?>">
                    <i class="fa-solid fa-exclamation-triangle"></i>
                    En retard (<?php echo $stats['en_retard']; ?>)
                </a>
            </div>

            <?php if($nombre_resultats == 0): ?>
                <!-- Liste vide -->
                <div class="empty-historique">
                    <i class="fa-solid fa-book-open"></i>
                    <h2>Aucun emprunt trouvé</h2>
                    <p>
                        <?php if($filtre == 'tous'): ?>
                            Vous n'avez pas encore emprunté de livre.
                        <?php else: ?>
                            Aucun livre avec ce statut.
                        <?php endif; ?>
                    </p>
                    <a href="index.php" class="btn-discover">
                        <i class="fa-solid fa-compass"></i>
                        Découvrir des livres
                    </a>
                </div>
            <?php else: ?>
                <!-- Liste des emprunts -->
                <div class="historique-list">
                    <?php while($emprunt = mysqli_fetch_assoc($result)): ?>
                        <?php 
                            $image_livre = isset($images_livres[$emprunt['id']]) ? $images_livres[$emprunt['id']] : 'default-book.jpg';
                            
                            // Formater les dates
                            $date_emprunt = date('d/m/Y', strtotime($emprunt['date_emprunt']));
                            $date_retour = $emprunt['date_retour'] ? date('d/m/Y', strtotime($emprunt['date_retour'])) : 'Non définie';
                            
                            // Déterminer la classe de statut
                            $statut_class = '';
                            $statut_text = '';
                            $statut_icon = '';
                            
                            switch($emprunt['statut']) {
                                case 'en_cours':
                                    $statut_class = 'status-active';
                                    $statut_text = 'En cours';
                                    $statut_icon = 'fa-book-open';
                                    break;
                                case 'termine':
                                    $statut_class = 'status-completed';
                                    $statut_text = 'Terminé';
                                    $statut_icon = 'fa-check-circle';
                                    break;
                                case 'en_retard':
                                    $statut_class = 'status-late';
                                    $statut_text = 'En retard';
                                    $statut_icon = 'fa-exclamation-triangle';
                                    break;
                            }
                        ?>
                        
                        <div class="historique-card">
                            <!-- Image -->
                            <div class="historique-image">
                                <img src="images/<?php echo $image_livre; ?>" 
                                     alt="<?php echo htmlspecialchars($emprunt['titre']); ?>"
                                     onerror="this.src='images/default-book.jpg'">
                                <div class="status-badge <?php echo $statut_class; ?>">
                                    <i class="fa-solid <?php echo $statut_icon; ?>"></i>
                                    <?php echo $statut_text; ?>
                                </div>
                            </div>

                            <!-- Informations -->
                            <div class="historique-info">
                                <h3 class="historique-title">
                                    <?php echo htmlspecialchars($emprunt['titre']); ?>
                                </h3>
                                
                                <p class="historique-author">
                                    <i class="fa-solid fa-user"></i>
                                    <?php echo htmlspecialchars($emprunt['auteur']); ?>
                                </p>

                                <div class="historique-dates">
                                    <div class="date-info">
                                        <i class="fa-solid fa-calendar-plus"></i>
                                        <span>Emprunté le : <strong><?php echo $date_emprunt; ?></strong></span>
                                    </div>
                                    <div class="date-info">
                                        <i class="fa-solid fa-calendar-check"></i>
                                        <span>À retourner : <strong><?php echo $date_retour; ?></strong></span>
                                    </div>
                                    <div class="date-info">
                                        <i class="fa-solid fa-clock"></i>
                                        <span>Il y a <strong><?php echo $emprunt['jours_depuis_emprunt']; ?> jour(s)</strong></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="historique-actions">
                                <a href="details.php?id=<?php echo $emprunt['id']; ?>" class="btn-hist-action">
                                    <i class="fa-solid fa-eye"></i>
                                    Voir détails
                                </a>
                                
                                <?php if($emprunt['statut'] == 'termine'): ?>
                                    <button onclick="reemprunter(<?php echo $emprunt['id']; ?>)" class="btn-hist-action btn-reemprunt">
                                        <i class="fa-solid fa-rotate"></i>
                                        Réémprunter
                                    </button>
                                <?php elseif($emprunt['statut'] == 'en_cours' || $emprunt['statut'] == 'en_retard'): ?>
                                    <button onclick="marquerTermine(<?php echo $emprunt['id']; ?>)" class="btn-hist-action btn-terminer">
                                        <i class="fa-solid fa-check"></i>
                                        Marquer terminé
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <script src="js/script.js"></script>
    <script>
        function marquerTermine(idLivre) {
            if(confirm('Marquer ce livre comme terminé ?')) {
                window.location.href = `php/mark_completed.php?id_livre=${idLivre}`;
            }
        }

        function reemprunter(idLivre) {
            window.location.href = `details.php?id=${idLivre}`;
        }
    </script>
</body>
</html>

<?php
mysqli_close($connexion);
?>