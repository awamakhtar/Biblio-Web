<?php
require_once 'config.php';

if(isset($_GET['id_livre']) && isset($_GET['id_lecteur'])) {
    $id_livre = (int)$_GET['id_livre'];
    $id_lecteur = (int)$_GET['id_lecteur'];
    
    // Supprimer le livre de la liste
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