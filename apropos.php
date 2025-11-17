<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <title>À propos - Biblio Web</title>
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
    <header class="header-apropos">
        <div class="hero-text">
            <h1>📚 À propos de Biblio Web</h1>
            <p>Découvrez notre mission et nos valeurs</p>
        </div>
    </header>

    <!-- Contenu Principal -->
    <main class="main-apropos">
        <div class="container-apropos">
            
            
            <section class="apropos-section">
                <div class="section-icon">
                    <i class="fa-solid fa-bullseye"></i>
                </div>
                <h2>Notre Mission</h2>
                <p>
                    Biblio Web est une bibliothèque en ligne moderne qui vise à digitaliser l'accès à la lecture 
                    et à la culture. Notre plateforme permet à chacun de découvrir, emprunter et gérer facilement 
                    sa collection de livres préférés, où qu'il se trouve.
                </p>
            </section>

            <!-- Section Services -->
            <section class="apropos-section">
                <div class="section-icon">
                    <i class="fa-solid fa-star"></i>
                </div>
                <h2>Nos Services</h2>
                <div class="services-grid">
                    <div class="service-card">
                        <i class="fa-solid fa-search"></i>
                        <h3>Recherche Avancée</h3>
                        <p>Trouvez rapidement vos livres par titre ou auteur grâce à notre système de recherche intelligent.</p>
                    </div>
                    <div class="service-card">
                        <i class="fa-solid fa-heart"></i>
                        <h3>Liste Personnalisée</h3>
                        <p>Créez et gérez votre liste de lecture personnelle pour ne jamais perdre de vue vos prochaines lectures.</p>
                    </div>
                    <div class="service-card">
                        <i class="fa-solid fa-book-open"></i>
                        <h3>Collection Variée</h3>
                        <p>Accédez à une large sélection de livres classiques et contemporains, fiction et non-fiction.</p>
                    </div>
                    <div class="service-card">
                        <i class="fa-solid fa-calendar-check"></i>
                        <h3>Gestion des Emprunts</h3>
                        <p>Suivez vos dates d'emprunt et de retour pour gérer efficacement votre temps de lecture.</p>
                    </div>
                </div>
            </section>

            <!-- Section Statistiques -->
            <section class="apropos-section stats-section">
                <h2>Biblio Web en chiffres</h2>
                <div class="stats-grid">
                    <div class="stat-box">
                        <i class="fa-solid fa-book"></i>
                        <span class="stat-number">6+</span>
                        <span class="stat-label">Livres disponibles</span>
                    </div>
                    <div class="stat-box">
                        <i class="fa-solid fa-users"></i>
                        <span class="stat-number">5+</span>
                        <span class="stat-label">Lecteurs inscrits</span>
                    </div>
                    <div class="stat-box">
                        <i class="fa-solid fa-download"></i>
                        <span class="stat-number">3+</span>
                        <span class="stat-label">Livres empruntés</span>
                    </div>
                    <div class="stat-box">
                        <i class="fa-solid fa-star"></i>
                        <span class="stat-number">4.8/5</span>
                        <span class="stat-label">Satisfaction clients</span>
                    </div>
                </div>
            </section>

            <!-- Section Contact -->
            <section class="apropos-section contact-section">
                <div class="section-icon">
                    <i class="fa-solid fa-envelope"></i>
                </div>
                <h2>Contactez-nous</h2>
                <p>Vous avez des questions ou des suggestions ? N'hésitez pas à nous contacter !</p>
                <div class="contact-info">
                    <div class="contact-item">
                        <i class="fa-solid fa-envelope"></i>
                        <span>contact@biblioweb.com</span>
                    </div>
                    <div class="contact-item">
                        <i class="fa-solid fa-phone"></i>
                        <span>+221 33 456 78 90</span>
                    </div>
                    <div class="contact-item">
                        <i class="fa-solid fa-location-dot"></i>
                        <span>Dakar, Sénégal</span>
                    </div>
                </div>
            </section>

        </div>
    </main>
    <!-- Footer -->
     <footer>
        <div class="footer-container">
            <p>&copy; 2025 Biblio Web. Tous droits réservés.</p>
            <div class="social-links">
                <a href="#"><i class="fa-brands fa-facebook-f"></i></a>
                <a href="#"><i class="fa-brands fa-twitter"></i></a>
                <a href="#"><i class="fa-brands fa-instagram"></i></a>
            </div>
        </div>
        <div class="footer-bottom">
            <p>Conçue avec  par Awa Sylla</p>
        </div>
    </footer>

    <script src="js/script.js"></script>
</body>
</html>