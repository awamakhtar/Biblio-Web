<?php
// Si la session n'est pas démarrée, la démarrer
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Vérifier si l'utilisateur est connecté
$is_logged_in = isset($_SESSION['id_lecteur']);
?>

<nav class="navbar">
    <h1 class="logo">
        <a href="index.php" style="color: white; text-decoration: none;">
            📚 Biblio Web
        </a>
    </h1>
    
    <ul class="nav-link">
        <li><a href="index.php">Accueil</a></li>
        
        <?php if($is_logged_in): ?>
            <li><a href="wishlist.php"> Liste de Lecture</a></li>
        <?php endif; ?>
        
        <li><a href="apropos.php">À propos</a></li>
    </ul> 
    
    <div class="hamburger">
        <i class="fa-solid fa-bars"></i>
    </div>
    
    <div class="nav-actions">
        <?php if($is_logged_in): ?>
            <!-- Utilisateur connecté -->
            <div class="user-menu">
                <button class="user-button" onclick="toggleUserMenu()">
                    <i class="fa-solid fa-user-circle"></i>
                    <span class="user-name"><?php echo htmlspecialchars($_SESSION['prenom']); ?></span>
                    <i class="fa-solid fa-chevron-down"></i>
                </button>
                
                <div class="user-dropdown" id="userDropdown">
                    <div class="user-info">
                        <i class="fa-solid fa-user-circle"></i>
                        <div>
                            <strong><?php echo htmlspecialchars($_SESSION['prenom'] . ' ' . $_SESSION['nom']); ?></strong>
                            <small><?php echo htmlspecialchars($_SESSION['email']); ?></small>
                        </div>
                    </div>
                    <hr>
                    <a href="profile.php">
                        <i class="fa-solid fa-user"></i>
                        Mon profil
                    </a>
                    <a href="historique.php">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                        Mon historique
                    </a>
                    <hr>
                    <a href="logout.php" class="logout-link">
                        <i class="fa-solid fa-right-from-bracket"></i>
                        Déconnexion
                    </a>
                </div>
            </div>
        <?php else: ?>
            <!-- Utilisateur non connecté -->
            <a href="login.php" class="btn-login-nav">
                <i class="fa-solid fa-right-to-bracket"></i>
                Connexion
            </a>
            <a href="register.php" class="btn-register-nav">
                <i class="fa-solid fa-user-plus"></i>
                Inscription
            </a>
        <?php endif; ?>
    </div>
</nav>









