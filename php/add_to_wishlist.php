<?php
require_once 'config.php';

if(isset($_GET['id_livre']) && isset($_GET['id_lecteur'])) {
    $id_livre = (int)$_GET['id_livre'];
    $id_lecteur = (int)$_GET['id_lecteur'];
    
    // Vérifier si le lecteur existe
    $check_lecteur = "SELECT * FROM lecteurs WHERE id = $id_lecteur";
    $result_lecteur = mysqli_query($connexion, $check_lecteur);
    
    if(mysqli_num_rows($result_lecteur) == 0) {
        die("Erreur : Le lecteur avec l'ID $id_lecteur n'existe pas dans la base de données. Veuillez créer un lecteur d'abord.");
    }
    
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
    $date_emprunt = date('Y-m-d'); //date emprunt 
    $date_retour = date('Y-m-d', strtotime('+30 days')); // date retour 30 jours plus tard
    
    $sql = "INSERT INTO liste_lecture (id_livre, id_lecteur, date_emprunt, date_retour) 
            VALUES ($id_livre, $id_lecteur, '$date_emprunt', '$date_retour')";
    
    if(mysqli_query($connexion, $sql)) {
        header('Location: ../wishlist.php?success=added');
        exit();
    } else {
        // Erreur
        die("Erreur lors de l'ajout : " . mysqli_error($connexion));
    }
} else {
    header('Location: ../index.php');
    exit();
}

mysqli_close($connexion);
?>