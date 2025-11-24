<?php
session_start();
require_once 'config.php';

// Vérifier si l'utilisateur est connecté
if(!isset($_SESSION['id_lecteur'])) {
    header('Location: ../login.php');
    exit();
}

$id_lecteur = $_SESSION['id_lecteur'];

// Supprimer toutes les entrées de liste_lecture de cet utilisateur
$delete_liste = "DELETE FROM liste_lecture WHERE id_lecteur = $id_lecteur";
mysqli_query($connexion, $delete_liste);

// Supprimer le compte utilisateur
$delete_user = "DELETE FROM lecteurs WHERE id = $id_lecteur";

if(mysqli_query($connexion, $delete_user)) {
    // Détruire la session
    session_destroy();
    
    // Rediriger vers l'accueil avec un message
    header('Location: ../index.php?account_deleted=1');
    exit();
} else {
    // Erreur
    header('Location: ../profile.php?error=delete_failed');
    exit();
}

mysqli_close($connexion);
?>
