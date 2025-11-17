<?php 

// 🔌 Connexion à la base de donné
$server = "localhost";
$user = "root";
$password = "";
$database = "bibliotheque";


$connexion = mysqli_connect($server, $user, $password, $database);
if (!$connexion) {
    die("<p style='color:red;'> Erreur de connexion : " . mysqli_connect_error() . "</p>");
}
 

?>