<?php
session_start();
require_once 'config.php';

// Vérifier si l'utilisateur est connecté
if(!isset($_SESSION['id_lecteur'])) {
    header('Location: ../login.php');
    exit();
}

$id_lecteur = $_SESSION['id_lecteur'];

if(isset($_POST['id_livre']) && isset($_POST['date_emprunt']) && isset($_POST['date_retour'])) {
    $id_livre = (int)$_POST['id_livre'];
    $date_emprunt = $_POST['date_emprunt'];
    $date_retour = $_POST['date_retour'];
    
    // Validation des dates
    $date_emprunt_obj = new DateTime($date_emprunt);
    $date_retour_obj = new DateTime($date_retour);
    
    // Vérifier que la date de retour est après la date d'emprunt
    if($date_retour_obj <= $date_emprunt_obj) {
        header('Location: ../details.php?id=' . $id_livre . '&error=date_invalide');
        exit();
    }
    
    // Vérifier si le livre est déjà dans la liste
    $check_sql = "SELECT * FROM liste_lecture 
                  WHERE id_livre = $id_livre AND id_lecteur = $id_lecteur";
    $check_result = mysqli_query($connexion, $check_sql);
    
    if(mysqli_num_rows($check_result) > 0) {
        header('Location: ../wishlist.php?already=1');
        exit();
    }
    
    // Sécuriser les dates
    $date_emprunt_safe = mysqli_real_escape_string($connexion, $date_emprunt);
    $date_retour_safe = mysqli_real_escape_string($connexion, $date_retour);
    
    // Déterminer le statut automatiquement selon la date de retour
    $aujourd_hui = new DateTime();
    $statut = 'en_cours';
    
    if($date_retour_obj < $aujourd_hui) {
        $statut = 'en_retard';
    }
    
    // Ajouter le livre à la liste
    $sql = "INSERT INTO liste_lecture (id_livre, id_lecteur, date_emprunt, date_retour, statut) 
            VALUES ($id_livre, $id_lecteur, '$date_emprunt_safe', '$date_retour_safe', '$statut')";
    
    if(mysqli_query($connexion, $sql)) {
        // Succès : rediriger vers la wishlist
        header('Location: ../wishlist.php?success=added');
        exit();
    } else {
        // Erreur
        die("Erreur lors de l'ajout : " . mysqli_error($connexion));
    }
} else {
    // Paramètres manquants
    header('Location: ../index.php');
    exit();
}

mysqli_close($connexion);
?>