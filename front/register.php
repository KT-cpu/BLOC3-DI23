<?php
    require_once '../back/CRUD/club.php';

    $clubs = getAllClubsName();

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
        <title>Creer un compte</title>
        <link rel="stylesheet" href="./css/register.css">
        <link rel="stylesheet" href="./css/modal.css">
    </head>
    <body>
        <section id="main_container">
            <form id="sign_in" action="./actions/process_register.php" method="POST">
                <div class="form_item">
                    <label for="pseudo" class="pointer" >Nom d'utilisateur</label>
                    <input type="text" name="pseudo" id="pseudo" placeholder="Entrez votre nom d'utilisateur" required value="admin">
                </div>
                <div class="form_item password_container">
                    <label for="password" class="pointer" >Mot de passe</label>
                    <input type="password" name="password" id="password" placeholder="Entrez le mot de passe" required value="admin">
                </div>
                <div class="form_item">
                    <label for="confirm_password" class="pointer" >Confirmer le mot de passe</label>
                    <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirmez le mot de passe" required value="admin">
                </div>
                <div class="form_item">
                    <label for="favorite_club" class="pointer" >Equipe favorite</label>
                    <select name="favorite_club" id="favorite_club" class="pointer" required>
                        <option value="" disabled selected>Choisir une équipe favorite</option>
                        <?php foreach ($clubs as $club) {
                            $clubName = htmlspecialchars($club['club_name']); // Encode HTML special characters
                            $formattedClubName = ucwords($clubName); // Capitalize the first letter of each word
                            echo '<option value="' . $clubName . '">' . $formattedClubName . '</option>';
                        } ?>
                    </select>
                </div>
                <button id="submit" type="submit">VALIDER</button>
            </form>
        </section>
    </body>
<script src="./js/register.js"></script>
</html>