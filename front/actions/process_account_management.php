<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: ./login');
    exit();
} else {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        require_once dirname(__DIR__, 2) . '/back/CRUD/user.php';
        require_once dirname(__DIR__, 2) . '/back/CRUD/club.php';

        if (isset($_POST['form_type'])){
            switch ($_POST['form_type']) {
                case 'pseudo_form':
                    // Traiter le formulaire de modification du pseudo
                    $newPseudo = htmlspecialchars($_POST['pseudo']);
                    echo "Pseudo: $newPseudo";
                    $_SESSION['user_pseudo'] = setUserPseudo($_SESSION['user_id'], $newPseudo);
                    echo "User session : " . $_SESSION['user_pseudo'];
                    $_SESSION['message'] = 'Pseudo modifié avec succès.';
                    break;
        
                case 'password_form':
                    // Traiter le formulaire de modification du mot de passe
                    $newPassword = password_hash(htmlspecialchars($_POST['password']), PASSWORD_DEFAULT); // Hash the password
                    $_SESSION['user_password'] = setUserPassword($_SESSION['user_id'], $newPassword);
                    $_SESSION['message'] = 'Mot de passe modifié avec succès.';
                    break;
        
                case 'favorite_club_form':
                    // Traiter le formulaire de modification de l'équipe favorite
                    $newFavoriteClubId = getClubIdByName($_POST['favorite_club']);
                    $_SESSION['user_favorite_club_id'] = setUserFavoriteClub($_SESSION['user_id'], $newFavoriteClubId);
                    $_SESSION['message'] = 'Équipe favorite modifiée avec succès.';
                    // Logique de traitement pour l'équipe favorite
                    break;
        
                case 'delete_account_form':
                    if (deleteUser($_SESSION['user_id'])) {
                        // Logout
                        session_unset();
                        session_destroy();
                        $_SESSION['loggedin'] = false;
                        $_SESSION['message'] = 'Compte supprimé avec succès.';
                        header('Location: ./home');
                        exit();
                    } else {
                        $_SESSION['message'] = 'Échec de la suppression du compte.';
                    }
                    break;
                default:
                    // Formulaire non reconnu
                    $_SESSION['message'] = 'Formulaire non reconnu.';
                    break;
            } 
            header('Location: ./account_management');
            exit();
        } else {
            // Requête invalide
            $_SESSION['message'] = 'Requête invalide.';
            header('Location: ./account_management');
            exit();
        }
    } else {
        // Invalid request
        $_SESSION['message'] = 'Requête invalide.';
        echo $_SESSION['message'];
        header('Location: ./account_management');
        exit();
    }
}
?>