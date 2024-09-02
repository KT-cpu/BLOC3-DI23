<?php
    require_once dirname(__DIR__, 2) . '/back/CRUD/user.php';
    require_once dirname(__DIR__, 2) . '/back/CRUD/club.php';

    session_start();

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $userPseudo = htmlspecialchars($_POST['pseudo']);
        $userPasswordHashed = password_hash(htmlspecialchars($_POST['password']), PASSWORD_DEFAULT); // Hash the password
        $favoriteClubId = getClubIdByName(htmlspecialchars($_POST['favorite_club']));
        echo "Club id: $favoriteClubId";

        $userId = addUser($userPseudo, $userPasswordHashed, $favoriteClubId);	

        if ($userId) {
            $user = getUserById($userId);
            // Login successful
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_pseudo'] = $user['user_pseudo'];
            $_SESSION['user_favorite_club_id'] = $user['user_favorite_club_id'];
            $_SESSION['user_role'] = $user['user_role'];
            $_SESSION['loggedin'] = true;
            header('Location: ./home');
        } else {
            $_SESSION['message'] = "Échec de la création de l'utilisateur.";
            header('Location: ./register');
        }
    } else {
        // Invalid request
        $_SESSION['message'] = 'Requête invalide.';
        header('Location: ./register');
    }
    exit();
?>