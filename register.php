<?php
session_start();
require_once 'php/config.php';

// Si déjà connecté, rediriger vers l'accueil
if(isset($_SESSION['id_lecteur'])) {
    header('Location: index.php');
    exit();
}

$error = '';
$success = '';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];
    
    // Validation
    if(empty($nom) || empty($prenom) || empty($email) || empty($password)) {
        $error = "Tous les champs sont obligatoires.";
    } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Email invalide.";
    } elseif(strlen($password) < 6) {
        $error = "Le mot de passe doit contenir au moins 6 caractères.";
    } elseif($password !== $password_confirm) {
        $error = "Les mots de passe ne correspondent pas.";
    } else {
        // Vérifier si l'email existe déjà
        $email_safe = mysqli_real_escape_string($connexion, $email);
        $check_sql = "SELECT id FROM lecteurs WHERE email = '$email_safe'";
        $check_result = mysqli_query($connexion, $check_sql);
        
        if(mysqli_num_rows($check_result) > 0) {
            $error = "Cet email est déjà utilisé.";
        } else {
            // Hasher le mot de passe
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            
            // Sécuriser les données
            $nom_safe = mysqli_real_escape_string($connexion, $nom);
            $prenom_safe = mysqli_real_escape_string($connexion, $prenom);
            
            // Insérer le nouvel utilisateur dans la table lecteurs
            $sql = "INSERT INTO lecteurs (nom, prenom, email, password) 
                    VALUES ('$nom_safe', '$prenom_safe', '$email_safe', '$password_hash')";
            
            if(mysqli_query($connexion, $sql)) {
                $success = "Inscription réussie ! Vous pouvez maintenant vous connecter.";
                
                // Optionnel : Connexion automatique après inscription
                /*
                $id_lecteur = mysqli_insert_id($connexion);
                $_SESSION['id_lecteur'] = $id_lecteur;
                $_SESSION['nom'] = $nom;
                $_SESSION['prenom'] = $prenom;
                $_SESSION['email'] = $email;
                header('Location: index.php');
                exit();
                */
            } else {
                $error = "Erreur lors de l'inscription. Veuillez réessayer.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <title>Inscription - Biblio Web</title>
</head>
<body>
    <!-- Navbar simple -->
    <nav class="navbar">
        <h1 class="logo">📚 Biblio Web</h1>
        <a href="login.php" class="btn-login-nav">
            <i class="fa-solid fa-right-to-bracket"></i>
            Connexion
        </a>
    </nav>

    <!-- Page d'inscription -->
    <main class="auth-page">
        <div class="auth-container">
            <div class="auth-box">
                <div class="auth-header">
                    <i class="fa-solid fa-user-plus"></i>
                    <h1>Inscription</h1>
                    <p>Créez votre compte pour accéder à notre bibliothèque</p>
                </div>

                <?php if($error): ?>
                    <div class="alert alert-error">
                        <i class="fa-solid fa-exclamation-circle"></i>
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>

                <?php if($success): ?>
                    <div class="alert alert-success">
                        <i class="fa-solid fa-check-circle"></i>
                        <?php echo $success; ?>
                        <a href="login.php" style="margin-left: 10px; text-decoration: underline;">Se connecter</a>
                    </div>
                <?php endif; ?>

                <form method="POST" class="auth-form">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="nom">
                                <i class="fa-solid fa-user"></i>
                                Nom
                            </label>
                            <input type="text" id="nom" name="nom" required 
                                   value="<?php echo isset($_POST['nom']) ? htmlspecialchars($_POST['nom']) : ''; ?>"
                                   placeholder="Votre nom">
                        </div>

                        <div class="form-group">
                            <label for="prenom">
                                <i class="fa-solid fa-user"></i>
                                Prénom
                            </label>
                            <input type="text" id="prenom" name="prenom" required 
                                   value="<?php echo isset($_POST['prenom']) ? htmlspecialchars($_POST['prenom']) : ''; ?>"
                                   placeholder="Votre prénom">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="email">
                            <i class="fa-solid fa-envelope"></i>
                            Email
                        </label>
                        <input type="email" id="email" name="email" required 
                               value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
                               placeholder="votre.email@exemple.com">
                    </div>

                    <div class="form-group">
                        <label for="password">
                            <i class="fa-solid fa-lock"></i>
                            Mot de passe
                        </label>
                        <input type="password" id="password" name="password" required 
                               minlength="6" placeholder="Au moins 6 caractères">
                    </div>

                    <div class="form-group">
                        <label for="password_confirm">
                            <i class="fa-solid fa-lock"></i>
                            Confirmer le mot de passe
                        </label>
                        <input type="password" id="password_confirm" name="password_confirm" required
                               placeholder="Répétez votre mot de passe">
                    </div>

                    <button type="submit" class="btn-auth">
                        <i class="fa-solid fa-user-plus"></i>
                        S'inscrire
                    </button>
                </form>

                <div class="auth-footer">
                    <p>Vous avez déjà un compte ? <a href="login.php">Se connecter</a></p>
                </div>
            </div>

            <div class="auth-illustration">
                <i class="fa-solid fa-book-open"></i>
                <h2>Rejoignez notre communauté</h2>
                <ul>
                    <li><i class="fa-solid fa-check"></i> Accès à des milliers de livres</li>
                    <li><i class="fa-solid fa-check"></i> Liste de lecture personnalisée</li>
                    <li><i class="fa-solid fa-check"></i> Lecture en ligne gratuite</li>
                    <li><i class="fa-solid fa-check"></i> Suivi de vos emprunts</li>
                </ul>
            </div>
        </div>
    </main>

    <script src="js/script.js"></script>
</body>
</html>

<?php
mysqli_close($connexion);
?>