<?php
session_start();
require_once 'php/config.php';

// Si déjà connecté, rediriger vers l'accueil
if(isset($_SESSION['id_lecteur'])) {
    header('Location: index.php');
    exit();
}

$error = '';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    
    // Validation
    if(empty($email) || empty($password)) {
        $error = "Tous les champs sont obligatoires.";
    } else {
        // Sécuriser l'email
        $email_safe = mysqli_real_escape_string($connexion, $email);
        
        // Rechercher l'utilisateur
        $sql = "SELECT * FROM lecteurs WHERE email = '$email_safe'";
        $result = mysqli_query($connexion, $sql);
        
        if(mysqli_num_rows($result) == 1) {
            $lecteur = mysqli_fetch_assoc($result);
            
            // Vérifier le mot de passe
            if(password_verify($password, $lecteur['password'])) {
                // Mot de passe correct : créer la session
                $_SESSION['id_lecteur'] = $lecteur['id'];
                $_SESSION['nom'] = $lecteur['nom'];
                $_SESSION['prenom'] = $lecteur['prenom'];
                $_SESSION['email'] = $lecteur['email'];
                
                // Rediriger vers l'accueil
                header('Location: index.php');
                exit();
            } else {
                $error = "Email ou mot de passe incorrect.";
            }
        } else {
            $error = "Email ou mot de passe incorrect.";
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
    <title>Connexion - Biblio Web</title>
</head>
<body>
    <!-- Navbar simple -->
    <nav class="navbar">
        <h1 class="logo">📚 Biblio Web</h1>
        <a href="register.php" class="btn-login-nav">
            <i class="fa-solid fa-user-plus"></i>
            Inscription
        </a>
    </nav>

    <!-- Page de connexion -->
    <main class="auth-page">
        <div class="auth-container">
            <div class="auth-box">
                <div class="auth-header">
                    <i class="fa-solid fa-right-to-bracket"></i>
                    <h1>Connexion</h1>
                    <p>Connectez-vous pour accéder à votre bibliothèque</p>
                </div>

                <?php if($error): ?>
                    <div class="alert alert-error">
                        <i class="fa-solid fa-exclamation-circle"></i>
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>

                <form method="POST" class="auth-form">
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
                               placeholder="Votre mot de passe">
                    </div>

                    <button type="submit" class="btn-auth">
                        <i class="fa-solid fa-right-to-bracket"></i>
                        Se connecter
                    </button>
                </form>

                <div class="auth-footer">
                    <p>Pas encore de compte ? <a href="register.php">S'inscrire</a></p>
                </div>

                <!-- Compte de test pour faciliter les tests -->
                <div class="test-account">
                    <p><strong>Compte de test :</strong></p>
                    <p>Email : <code>awa@email.com</code></p>
                    <p>Mot de passe : <code>password</code></p>
                </div>
            </div>

            <div class="auth-illustration">
                <i class="fa-solid fa-book-reader"></i>
                <h2>Bienvenue !</h2>
                <ul>
                    <li><i class="fa-solid fa-check"></i> Accédez à votre liste de lecture</li>
                    <li><i class="fa-solid fa-check"></i> Gérez vos emprunts</li>
                    <li><i class="fa-solid fa-check"></i> Lisez en ligne gratuitement</li>
                    <li><i class="fa-solid fa-check"></i> Suivez votre historique</li>
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