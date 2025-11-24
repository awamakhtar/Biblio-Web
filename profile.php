<?php
session_start();
require_once 'php/config.php';

// Vérifier si l'utilisateur est connecté
if(!isset($_SESSION['id_lecteur'])) {
    header('Location: login.php');
    exit();
}

$id_lecteur = $_SESSION['id_lecteur'];
$success = '';
$error = '';

// Récupérer les infos de l'utilisateur
$sql = "SELECT * FROM lecteurs WHERE id = $id_lecteur";
$result = mysqli_query($connexion, $sql);
$lecteur = mysqli_fetch_assoc($result);

// Récupérer les statistiques
$stats_sql = "SELECT 
                COUNT(*) as total_emprunts,
                COUNT(CASE WHEN date_retour >= CURDATE() THEN 1 END) as emprunts_actifs,
                COUNT(CASE WHEN date_retour < CURDATE() THEN 1 END) as en_retard
              FROM liste_lecture 
              WHERE id_lecteur = $id_lecteur";
$stats_result = mysqli_query($connexion, $stats_sql);
$stats = mysqli_fetch_assoc($stats_result);

// Traitement du formulaire de modification
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profile'])) {
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $email = trim($_POST['email']);
    
    // Validation
    if(empty($nom) || empty($prenom) || empty($email)) {
        $error = "Tous les champs sont obligatoires.";
    } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Email invalide.";
    } else {
        // Vérifier si l'email est déjà utilisé par un autre utilisateur
        $email_safe = mysqli_real_escape_string($connexion, $email);
        $check_sql = "SELECT id FROM lecteurs WHERE email = '$email_safe' AND id != $id_lecteur";
        $check_result = mysqli_query($connexion, $check_sql);
        
        if(mysqli_num_rows($check_result) > 0) {
            $error = "Cet email est déjà utilisé par un autre compte.";
        } else {
            // Sécuriser les données
            $nom_safe = mysqli_real_escape_string($connexion, $nom);
            $prenom_safe = mysqli_real_escape_string($connexion, $prenom);
            
            // Mettre à jour le profil
            $update_sql = "UPDATE lecteurs 
                          SET nom = '$nom_safe', prenom = '$prenom_safe', email = '$email_safe'
                          WHERE id = $id_lecteur";
            
            if(mysqli_query($connexion, $update_sql)) {
                // Mettre à jour la session
                $_SESSION['nom'] = $nom;
                $_SESSION['prenom'] = $prenom;
                $_SESSION['email'] = $email;
                
                $success = "Profil mis à jour avec succès !";
                
                // Recharger les données
                $lecteur['nom'] = $nom;
                $lecteur['prenom'] = $prenom;
                $lecteur['email'] = $email;
            } else {
                $error = "Erreur lors de la mise à jour.";
            }
        }
    }
}

// Traitement du changement de mot de passe
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Validation
    if(empty($current_password) || empty($new_password) || empty($confirm_password)) {
        $error = "Tous les champs sont obligatoires.";
    } elseif(strlen($new_password) < 6) {
        $error = "Le nouveau mot de passe doit contenir au moins 6 caractères.";
    } elseif($new_password !== $confirm_password) {
        $error = "Les nouveaux mots de passe ne correspondent pas.";
    } else {
        // Vérifier l'ancien mot de passe
        if(password_verify($current_password, $lecteur['password'])) {
            // Hasher le nouveau mot de passe
            $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);
            
            // Mettre à jour le mot de passe
            $update_pwd_sql = "UPDATE lecteurs SET password = '$new_password_hash' WHERE id = $id_lecteur";
            
            if(mysqli_query($connexion, $update_pwd_sql)) {
                $success = "Mot de passe modifié avec succès !";
            } else {
                $error = "Erreur lors de la modification du mot de passe.";
            }
        } else {
            $error = "Mot de passe actuel incorrect.";
        }
    }
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
    <title>Mon Profil - Biblio Web</title>
</head>
<body>
    <?php include 'includes/navbar.php'; ?>

    <!-- Hero Section -->
    <header class="header-profile">
        <div class="hero-text">
            <h1>👤 Mon Profil</h1>
            <p>Gérez vos informations personnelles</p>
        </div>
    </header>

    <!-- Contenu Principal -->
    <main class="main-profile">
        <div class="container-profile">
            
            <!-- Alertes -->
            <?php if($success): ?>
                <div class="alert alert-success">
                    <i class="fa-solid fa-check-circle"></i>
                    <?php echo $success; ?>
                </div>
            <?php endif; ?>

            <?php if($error): ?>
                <div class="alert alert-error">
                    <i class="fa-solid fa-exclamation-circle"></i>
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <div class="profile-grid">
                
                <!-- Colonne gauche : Carte utilisateur + Statistiques -->
                <div class="profile-sidebar">
                    
                    <!-- Carte utilisateur -->
                    <div class="profile-card">
                        <div class="profile-avatar">
                            <i class="fa-solid fa-user-circle"></i>
                        </div>
                        <h2><?php echo htmlspecialchars($lecteur['prenom'] . ' ' . $lecteur['nom']); ?></h2>
                        <p class="profile-email">
                            <i class="fa-solid fa-envelope"></i>
                            <?php echo htmlspecialchars($lecteur['email']); ?>
                        </p>
                        <p class="profile-date">
                            <i class="fa-solid fa-calendar"></i>
                            Membre depuis <?php echo date('F Y', strtotime($lecteur['date_inscription'])); ?>
                        </p>
                    </div>

                    <!-- Statistiques -->
                    <div class="stats-card">
                        <h3>📊 Mes Statistiques</h3>
                        <div class="stat-item">
                            <div class="stat-icon">
                                <i class="fa-solid fa-book"></i>
                            </div>
                            <div class="stat-content">
                                <span class="stat-number"><?php echo $stats['total_emprunts']; ?></span>
                                <span class="stat-label">Emprunts totaux</span>
                            </div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-icon">
                                <i class="fa-solid fa-book-open"></i>
                            </div>
                            <div class="stat-content">
                                <span class="stat-number"><?php echo $stats['emprunts_actifs']; ?></span>
                                <span class="stat-label">En cours de lecture</span>
                            </div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-icon" style="background: #e74c3c;">
                                <i class="fa-solid fa-exclamation-triangle"></i>
                            </div>
                            <div class="stat-content">
                                <span class="stat-number" style="color: #e74c3c;"><?php echo $stats['en_retard']; ?></span>
                                <span class="stat-label">En retard</span>
                            </div>
                        </div>
                    </div>

                    <!-- Actions rapides -->
                    <div class="quick-actions">
                        <a href="wishlist.php" class="quick-action-btn">
                            <i class="fa-solid fa-heart"></i>
                            Ma liste de lecture
                        </a>
                        <a href="index.php" class="quick-action-btn">
                            <i class="fa-solid fa-book"></i>
                            Parcourir les livres
                        </a>
                    </div>
                </div>

                <!-- Colonne droite : Formulaires -->
                <div class="profile-content">
                    
                    <!-- Modifier le profil -->
                    <div class="profile-section">
                        <div class="section-header">
                            <h3>
                                <i class="fa-solid fa-user-edit"></i>
                                Modifier mes informations
                            </h3>
                        </div>
                        <form method="POST" class="profile-form">
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="nom">
                                        <i class="fa-solid fa-user"></i>
                                        Nom
                                    </label>
                                    <input type="text" id="nom" name="nom" required 
                                           value="<?php echo htmlspecialchars($lecteur['nom']); ?>">
                                </div>

                                <div class="form-group">
                                    <label for="prenom">
                                        <i class="fa-solid fa-user"></i>
                                        Prénom
                                    </label>
                                    <input type="text" id="prenom" name="prenom" required 
                                           value="<?php echo htmlspecialchars($lecteur['prenom']); ?>">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="email">
                                    <i class="fa-solid fa-envelope"></i>
                                    Email
                                </label>
                                <input type="email" id="email" name="email" required 
                                       value="<?php echo htmlspecialchars($lecteur['email']); ?>">
                            </div>

                            <button type="submit" name="update_profile" class="btn-profile-save">
                                <i class="fa-solid fa-save"></i>
                                Enregistrer les modifications
                            </button>
                        </form>
                    </div>

                    <!-- Changer le mot de passe -->
                    <div class="profile-section">
                        <div class="section-header">
                            <h3>
                                <i class="fa-solid fa-lock"></i>
                                Changer mon mot de passe
                            </h3>
                        </div>
                        <form method="POST" class="profile-form">
                            <div class="form-group">
                                <label for="current_password">
                                    <i class="fa-solid fa-lock"></i>
                                    Mot de passe actuel
                                </label>
                                <input type="password" id="current_password" name="current_password" required>
                            </div>

                            <div class="form-group">
                                <label for="new_password">
                                    <i class="fa-solid fa-lock"></i>
                                    Nouveau mot de passe
                                </label>
                                <input type="password" id="new_password" name="new_password" required 
                                       minlength="6" placeholder="Au moins 6 caractères">
                            </div>

                            <div class="form-group">
                                <label for="confirm_password">
                                    <i class="fa-solid fa-lock"></i>
                                    Confirmer le nouveau mot de passe
                                </label>
                                <input type="password" id="confirm_password" name="confirm_password" required>
                            </div>

                            <button type="submit" name="change_password" class="btn-profile-password">
                                <i class="fa-solid fa-key"></i>
                                Modifier le mot de passe
                            </button>
                        </form>
                    </div>

                    <!-- Zone de danger -->
                    <div class="profile-section danger-zone">
                        <div class="section-header">
                            <h3>
                                <i class="fa-solid fa-exclamation-triangle"></i>
                                Zone de danger
                            </h3>
                        </div>
                        <p>La suppression de votre compte est définitive et irréversible.</p>
                        <button class="btn-delete-account" onclick="confirmDelete()">
                            <i class="fa-solid fa-trash"></i>
                            Supprimer mon compte
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="js/script.js"></script>
    <script>
        function confirmDelete() {
            if(confirm('⚠️ ATTENTION : Êtes-vous vraiment sûr de vouloir supprimer votre compte ?\n\nCette action est DÉFINITIVE et IRRÉVERSIBLE.\n\nToutes vos données seront supprimées.')) {
                if(confirm('Confirmez-vous DÉFINITIVEMENT la suppression de votre compte ?')) {
                    window.location.href = 'php/delete_account.php';
                }
            }
        }
    </script>
</body>
</html>

<?php
mysqli_close($connexion);
?>