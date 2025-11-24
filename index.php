<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <title>Bibliothèque en Ligne</title>
</head>
<body>
    <!--  -->
    <header>
        <div class="hero-text">
            <h1 class="">Bienvenue Chez 📚 Biblio Web</h1>
            <p class="">Votre bibliothèque en ligne pour explorer,
                 lire et gérer vos livres préférés.</p>
        </div>
        <?php include 'includes/navbar.php'; ?>
    </header>

    <!-- Section de recherche -->
    <section class="search-section">
        <div class="search-container">
            <h2>Rechercher un livre</h2>
            <p>Trouvez votre prochain livre préféré</p>      
            <form id="searchForm" class="search-form">
                <div class="search-inputs">
                    <div class="input-group">
                        <i class="fa-solid fa-book"></i>
                        <input type="text" id="searchTitle" name="titre" placeholder="Rechercher par titre...">
                    </div>             
                    <div class="input-group">
                        <i class="fa-solid fa-user"></i>
                        <input type="text" id="searchAuthor" name="auteur" placeholder="Rechercher par auteur...">
                    </div>
                </div>           
                <button type="submit" class="search-btn">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    Rechercher
                </button>
            </form>
        </div>
    </section>
    <!-- Section des livres populaires -->
    <section class="books-section">
        <div class="books-container">
            <h2 class="section-title">Livres Populaires</h2>
            <p class="section-subtitle">Découvrez nos coups de cœur du moment</p>
        
            <div class="books-grid">
                <!-- Card 1 -->
                <div class="book-card">
                    <div class="book-image">
                        <img src="images/peti-prince.jpg" alt="Titre du livre">
                        <div class="book-overlay">
                            <a href="details.php?id=1" class="btn-details">
                                <i class="fa-solid fa-eye"></i>
                                Voir détails
                            </a>
                        </div>
                    </div>
                    <div class="book-info">
                        <h3 class="book-title">Le Petit Prince</h3>
                        <p class="book-author">
                            <i class="fa-solid fa-user"></i>
                            Antoine de Saint-Exupéry
                        </p>
                        <p class="book-description">
                            Un conte philosophique et poétique qui raconte l'histoire d'un petit prince...
                        </p>
                        <div class="book-footer">
                            <span class="book-available">
                                <i class="fa-solid fa-book"></i>
                                5 disponibles
                            </span>
                            <button class="btn-wishlist">
                                <i class="fa-solid fa-heart"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="book-card">
                    <div class="book-image">
                        <img src="images/1984.jpg" alt="Titre du livre">
                        <div class="book-overlay">
                            <a href="details.php?id=2" class="btn-details">
                                <i class="fa-solid fa-eye"></i>
                                Voir détails
                            </a>
                        </div>
                    </div>
                    <div class="book-info">
                        <h3 class="book-title">1984</h3>
                        <p class="book-author">
                            <i class="fa-solid fa-user"></i>
                            George Orwell
                        </p>
                        <p class="book-description">
                            Un roman dystopique qui dépeint une société totalitaire où règne la surveillance...
                        </p>
                        <div class="book-footer">
                            <span class="book-available">
                                <i class="fa-solid fa-book"></i>
                                3 disponibles
                            </span>
                            <button class="btn-wishlist">
                                <i class="fa-solid fa-heart"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="book-card">
                    <div class="book-image">
                        <img src="images/etranger.jpg" alt="Titre du livre">
                        <div class="book-overlay">
                            <a href="details.php?id=3" class="btn-details">
                                <i class="fa-solid fa-eye"></i>
                                Voir détails
                            </a>
                        </div>
                    </div>
                    <div class="book-info">
                        <h3 class="book-title">L'Étranger</h3>
                        <p class="book-author">
                            <i class="fa-solid fa-user"></i>
                            Albert Camus
                        </p>
                        <p class="book-description">
                            L'histoire de Meursault, un homme indifférent à tout qui commet un meurtre...
                        </p>
                        <div class="book-footer">
                            <span class="book-available">
                                <i class="fa-solid fa-book"></i>
                                8 disponibles
                            </span>
                            <button class="btn-wishlist">
                                <i class="fa-solid fa-heart"></i>
                            </button>
                        </div>
                    </div>
                </div>

            <!-- Card 4 -->
                <div class="book-card">
                    <div class="book-image">
                        <img src="images/aventure.jpg" alt="Titre du livre">
                        <div class="book-overlay">
                            <a href="details.php?id=4" class="btn-details">
                                <i class="fa-solid fa-eye"></i>
                                Voir détails
                            </a>
                        </div>
                    </div>
                    <div class="book-info">
                        <h3 class="book-title">L'Aventure ambiguë</h3>
                        <p class="book-author">
                            <i class="fa-solid fa-user"></i>
                        Cheikh Hamidou Kane
                        </p>
                        <p class="book-description">
                            Un roman historique qui suit le destin de Samba Diallo, un jeune homme tiraillé entre deux cultures...
                        </p>
                        <div class="book-footer">
                            <span class="book-available">
                                <i class="fa-solid fa-book"></i>
                                2 disponibles
                            </span>
                            <button class="btn-wishlist">
                                <i class="fa-solid fa-heart"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Card 5 -->
                <div class="book-card">
                    <div class="book-image">
                        <img src="images/Germinal.jpg" alt="Titre du livre">
                        <div class="book-overlay">
                            <a href="details.php?id=5" class="btn-details">
                                <i class="fa-solid fa-eye"></i>
                                Voir détails
                            </a>
                        </div>
                    </div>
                    <div class="book-info">
                        <h3 class="book-title">Germinal</h3>
                        <p class="book-author">
                            <i class="fa-solid fa-user"></i>
                            Émile Zola
                        </p>
                        <p class="book-description">
                            Un roman naturaliste qui décrit les conditions de vie des mineurs...
                        </p>
                        <div class="book-footer">
                            <span class="book-available">
                                <i class="fa-solid fa-book"></i>
                                6 disponibles
                            </span>
                            <button class="btn-wishlist">
                                <i class="fa-solid fa-heart"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Card 6 -->
                <div class="book-card">
                    <div class="book-image">
                        <img src="images/sous-orage.jpg" alt="Sous">
                        <div class="book-overlay">
                            <a href="details.php?id=6" class="btn-details">
                                <i class="fa-solid fa-eye"></i>
                                Voir détails
                            </a>
                        </div>
                    </div>
                    <div class="book-info">
                        <h3 class="book-title">Sous l'orage</h3>
                        <p class="book-author">
                            <i class="fa-solid fa-user"></i>
                            Seydou Badian Kouyaté
                        </p>
                        <p class="book-description">
                            Un roman qui explore les défis sociaux et politiques en Afrique de l'Ouest...
                        </p>
                        <div class="book-footer">
                            <span class="book-available">
                                <i class="fa-solid fa-book"></i>
                                4 disponibles
                            </span>
                            <button class="btn-wishlist">
                                <i class="fa-solid fa-heart"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


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
<style> */

</style>
    <script src="js/script.js"></script>
</body>
</html>