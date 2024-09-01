<?php
    require_once '../back/CRUD/club.php';
    $clubs = getAllClubsName();

    session_start();

    if(!isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == !true) {
        header('Location: ./login.php');
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
    <title>Gestion du compte</title>
    <link rel="stylesheet" href="./css/account_management.css">
</head>
<body>
    <section id="main_container">
        <!-- pseudo form -->
        <div  class="form_container">
            <form  action="./actions/process_account_management.php" method="POST">
                <input type="hidden" name="form_type" value="pseudo_form">
                <div class="form_item">
                    <label for="pseudo" class="pointer" >Modifier nom d'utilisateur</label>
                    <input type="text" name="pseudo" id="pseudo" placeholder="Entrez votre nom d'utilisateur" required value="admin">
                </div>
                <div class="submit_container">
                    <button id="pseudo_submit" type="submit" class="submit">Valider nom d'utilisateur</button>
                </div>
            </form>
        </div>
        <!-- password form -->
        <div  class="form_container">
            <form id="password_form" action="./actions/process_account_management.php" method="POST">
                <input type="hidden" name="form_type" value="password_form">
                <div class="form_item">
                    <label for="password" class="pointer" >Modifier mot de passe</label>
                    <input type="password" name="password" id="password" placeholder="Entrez votre mot de passe" required value="admin">
                    <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirmer votre nouveau mot de passe">
                </div>
                <div class="submit_container">
                    <button id="password_submit" type="submit" class="submit">Valider mot de passe</button>
                </div>
            </form>
        </div>
        <!-- favorite club form -->
        <?php
        if ($_SESSION['user_role'] !== 'coach') {
            ?>
            <div  class="form_container">
                <form  action="./actions/process_account_management.php" method="POST">
                    <input type="hidden" name="form_type" value="favorite_club_form">
                    <div class="form_item">
                        <label for="favorite_club" class="pointer" >Changer d'équipe favorite</label>
                        <select name="favorite_club" id="favorite_club" class="pointer" required>
                            <option value="" disabled selected>Choisir une équipe favorite</option>
                            <?php foreach ($clubs as $club) {
                                $clubName = htmlspecialchars($club['club_name']); // Encode HTML special characters
                                $formattedClubName = ucwords($clubName); // Capitalize the first letter of each word
                                echo '<option value="' . $clubName . '">' . $formattedClubName . '</option>';
                            } ?>
                        </select>
                    </div>
                    <div class="submit_container">
                        <button id="favorite_club_submit" type="submit" class="submit">Valider équipe</button>
                    </div>
                </form>
            </div>
            <?php
        }
        ?>
        <!-- delete account form -->
        <div  class="form_container">
            <form  action="./actions/process_account_management.php" method="POST">
                <input type="hidden" name="form_type" value="delete_account_form">
                <div class="form_item">
                    <label for="delete_account" class="pointer" >Supprimer mon compte</label>
                    <!-- <input type="password" name="delete_account" id="delete_account" placeholder="Entrez votre mot de passe" required> -->
                </div>
                <div class="submit_container">
                    <button id="delete_account_submit" type="submit" class="submit">Supprimer le compte</button>
                </div>
            </form>
    </section>
</body>
<script src="./js/account_management.js"></script>
</html>