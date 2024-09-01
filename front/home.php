<?php
    session_start();

    $user_role = null;

    if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
        $user_role = $_SESSION['user_role'];
        echo "You are logged in as " . $user_role . ".";
        echo "<br>";
    }

    if (isset($_SESSION['message'])) {
        // addslashes() allows to escape special characters
        $message = addslashes($_SESSION['message']);
        echo "<script>alert('$message');</script>";
        // Unset the message after displaying it
        unset($_SESSION['message']);
    }
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil</title>
</head>
<body>
    <?php if ($user_role === 'admin') { ?>
        <div>
            <a href="./create_match.php">Créer un match</a>
        </div>
    <?php } ?>

    <?php if ($user_role === 'admin') { ?>
        <div>
            <a href="./result_match_management.php">Saisir les résultats d'un match</a>
        </div>
    <?php } ?>

    <?php if ($user_role === null) { ?>
        <div>
            <a href="./login.php">Se connecter</a>
        </div>
    <?php } ?>
        <div>
            <a href="./register.php">S'inscrire</a>
        </div>
    <?php if ($user_role !== null) { ?>
        <div>
            <a href="./account_management.php">Gestion du compte</a>
        </div>
    <?php } ?>
    <?php if ($user_role === 'coach') { ?>
        <div>
            <a href="./coach_management.php">Gérer mon équipe</a>
        </div>
    <?php } ?>
    <div>
        <a href="./clubs.php">Les équipes</a>
    </div>
</body>
</html>