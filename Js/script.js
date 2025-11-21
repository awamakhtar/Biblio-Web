
// Sélection des éléments
const hamburger = document.querySelector('.hamburger');
const navLinks = document.querySelector('.nav-link');
const hamburgerIcon = document.querySelector('.hamburger i');

// Toggle du menu au clic
hamburger.addEventListener('click', () => {
    hamburger.classList.toggle('active');
    navLinks.classList.toggle('active');
    
    // Changer l'icône
    if (hamburger.classList.contains('active')) {
        hamburgerIcon.classList.remove('fa-bars');
        hamburgerIcon.classList.add('fa-xmark');
    } else {
        hamburgerIcon.classList.remove('fa-xmark');
        hamburgerIcon.classList.add('fa-bars');
    }
});

// Fermer le menu quand on clique sur un lien
const links = document.querySelectorAll('.nav-link a');
links.forEach(link => {
    link.addEventListener('click', () => {
        hamburger.classList.remove('active');
        navLinks.classList.remove('active');
        hamburgerIcon.classList.remove('fa-xmark');
        hamburgerIcon.classList.add('fa-bars');
    });
});

// Gestion du formulaire de recherche
const searchForm = document.getElementById('searchForm');

searchForm.addEventListener('submit', (e) => {
    e.preventDefault();
    
    const titre = document.getElementById('searchTitle').value.trim();
    const auteur = document.getElementById('searchAuthor').value.trim();
    
    // Vérifier qu'au moins un champ est rempli
    if (!titre && !auteur) {
        alert('Veuillez renseigner au moins un critère de recherche');
        return;
    }
    
    // Rediriger vers la page de résultats avec les paramètres
    const params = new URLSearchParams();
    if (titre) params.append('titre', titre);
    if (auteur) params.append('auteur', auteur);
    
    window.location.href = `results.php?${params.toString()}`;
});

// Fonction de confirmation avant suppression
function confirmerSuppression(idLivre, idLecteur) {
    if(confirm('Êtes-vous sûr de vouloir retirer ce livre de votre liste ?')) {
        window.location.href = `php/remove_from_wishlist.php?id_livre=${idLivre}&id_lecteur=${idLecteur}`;
    }
}



 // resultats des recherches
    function ajouterALaListe(idLivre) {
    const idLecteur = 1;
    window.location.href = `php/add_to_wishlist.php?id_livre=${idLivre}&id_lecteur=${idLecteur}`;
}

// Toggle du menu utilisateur
function toggleUserMenu() {
    const dropdown = document.getElementById('userDropdown');
    const button = document.querySelector('.user-button');
    
    dropdown.classList.toggle('active');
    button.classList.toggle('active');
}

// Fermer le menu si on clique en dehors
document.addEventListener('click', function(event) {
    const userMenu = document.querySelector('.user-menu');
    const dropdown = document.getElementById('userDropdown');
    const button = document.querySelector('.user-button');
    
    if (userMenu && !userMenu.contains(event.target)) {
        dropdown?.classList.remove('active');
        button?.classList.remove('active');
    }
});