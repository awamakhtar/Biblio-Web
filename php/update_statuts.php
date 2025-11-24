<?php
// Script pour mettre à jour automatiquement les statuts
require_once 'config.php';

// Mettre à jour les statuts en retard
$sql_retard = "UPDATE liste_lecture 
               SET statut = 'en_retard' 
               WHERE date_retour < CURDATE() 
               AND statut != 'termine'";
mysqli_query($connexion, $sql_retard);

// Mettre à jour les statuts en cours (pour les livres qui étaient en retard mais dont la date a été corrigée)
$sql_encours = "UPDATE liste_lecture 
                SET statut = 'en_cours' 
                WHERE date_retour >= CURDATE() 
                AND statut = 'en_retard'";
mysqli_query($connexion, $sql_encours);


?>