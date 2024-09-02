<?php
    session_start();

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
        <title>Login</title>
        <link rel="stylesheet" href="/BLOC3-DI23/front/css/login.css">
    </head>
    <body>
        <section id="main_container">
            <form id="sign_in" action="./process_login" method="POST">
                <div class="form_item">
                    <label for="pseudo">Nom d'utilisateur</label>
                    <input type="text" name="pseudo" id="pseudo" placeholder="Entrez votre nom d'utilisateur" required value="admin">
                </div>
                <div class="form_item">
                    <label for="password">Mot de passe</label>
                    <input type="password" name="password" id="password" placeholder="Entrez le mot de passe" required value="admin">
                </div>
                <button id="submit" type="submit">CONNEXION</button>
            </form>
        </section>
    </body>
</html>