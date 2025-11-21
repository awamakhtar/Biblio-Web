<?php
session_start();
require_once 'config.php';

// Vérifier si l'utilisateur est connecté
if(!isset($_SESSION['id_lecteur'])) {
    header('Location: ../login.php');
    exit();
}

// Récupérer l'ID de l'utilisateur connecté depuis la session
$id_lecteur = $_SESSION['id_lecteur'];

if(isset($_GET['id_livre'])) {
    $id_livre = (int)$_GET['id_livre'];
    
    // Vérifier si le livre est déjà dans la liste
    $check_sql = "SELECT * FROM liste_lecture 
                  WHERE id_livre = $id_livre AND id_lecteur = $id_lecteur";
    $check_result = mysqli_query($connexion, $check_sql);
    
    if(mysqli_num_rows($check_result) > 0) {
        // Le livre est déjà dans la liste
        header('Location: ../wishlist.php?already=1');
        exit();
    }
    
    // Ajouter le livre à la liste
    $date_emprunt = date('Y-m-d'); // Date d'aujourd'hui
    $date_retour = date('Y-m-d', strtotime('+30 days')); // Retour dans 30 jours
    
    $sql = "INSERT INTO liste_lecture (id_livre, id_lecteur, date_emprunt, date_retour) 
            VALUES ($id_livre, $id_lecteur, '$date_emprunt', '$date_retour')";
    
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