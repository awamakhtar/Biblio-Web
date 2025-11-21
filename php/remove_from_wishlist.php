<?php
session_start();
require_once 'config.php';

// Vérifier si l'utilisateur est connecté
if(!isset($_SESSION['id_lecteur'])) {
    header('Location: ../login.php');
    exit();
}

// Récupérer l'ID de l'utilisateur connecté
$id_lecteur = $_SESSION['id_lecteur'];

if(isset($_GET['id_livre'])) {
    $id_livre = (int)$_GET['id_livre'];
    
    // Supprimer le livre de la liste de CET utilisateur uniquement
    $sql = "DELETE FROM liste_lecture 
            WHERE id_livre = $id_livre AND id_lecteur = $id_lecteur";
    
    if(mysqli_query($connexion, $sql)) {
        // Redirection avec message de succès
        header('Location: ../wishlist.php?success=removed');
        exit();
    } else {
        // Redirection avec message d'erreur
        header('Location: ../wishlist.php?error=1');
        exit();
    }
} else {
    // Paramètres manquants
    header('Location: ../wishlist.php');
    exit();
}

mysqli_close($connexion);
?>