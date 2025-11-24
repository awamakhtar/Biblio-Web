<?php
session_start();
require_once 'config.php';

// Vérifier si l'utilisateur est connecté
if(!isset($_SESSION['id_lecteur'])) {
    header('Location: ../login.php');
    exit();
}

$id_lecteur = $_SESSION['id_lecteur'];
$id_livre = isset($_GET['id_livre']) ? (int)$_GET['id_livre'] : 0;

if($id_livre == 0) {
    header('Location: ../historique.php');
    exit();
}

// Marquer le livre comme terminé
$sql = "UPDATE liste_lecture 
        SET statut = 'termine' 
        WHERE id_livre = $id_livre AND id_lecteur = $id_lecteur";

if(mysqli_query($connexion, $sql)) {
    header('Location: ../historique.php?success=marked');
    exit();
} else {
    header('Location: ../historique.php?error=1');
    exit();
}

mysqli_close($connexion);
?>