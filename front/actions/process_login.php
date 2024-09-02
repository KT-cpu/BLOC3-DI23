<?php
    require_once dirname(__DIR__, 2) . '/back/CRUD/user.php';

    session_start();

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $userPseudo = htmlspecialchars($_POST['pseudo']);
        $userPassword = htmlspecialchars($_POST['password']);

        $user = getUserByPseudo($userPseudo);

        if (empty($user) || !password_verify($userPassword, $user['user_password'])) {
            $_SESSION['message'] = "Login failed.";
            echo $_SESSION['message'];
            header('Location: ./login');
        } else {
            // Login successful
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_pseudo'] = $user['user_pseudo'];
            $_SESSION['user_role'] = $user['user_role'];
            $_SESSION['user_favorite_club_id'] = $user['user_favorite_club_id'];
            $_SESSION['loggedin'] = true;
            header('Location: ./home');
        }
    } else {
        // Invalid request
        $_SESSION['message'] = 'Requête invalide.';
        echo $_SESSION['message'];
        header('Location: ./login');
    }
    exit();
?>