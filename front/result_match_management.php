<?php 
        session_start();

        if(isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'admin') {
            require_once dirname(__DIR__) . '/back/CRUD/club.php';
            require_once dirname(__DIR__) . '/back/CRUD/coach.php';
            require_once dirname(__DIR__) . '/back/CRUD/pre_match.php';
            require_once dirname(__DIR__) . '/back/CRUD/pre_match_team_lineup_versus.php';

            if (isset($_SESSION['message'])) {
                // addslashes() allows to escape special characters
                $message = addslashes($_SESSION['message']);
                echo "<script>alert('$message');</script>";
                // Unset the message after displaying it
                unset($_SESSION['message']);
            }
            $AllmatchPlayed = getAllPreMatchNotPlayed();
        } else {
            header('Location: ./home');
            exit();
        }
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Choisir un match</title>
    <link rel="stylesheet" href="/BLOC3-DI23/front/css/result_match_management.css">
</head>
<body>
<section>
        <div id="next_match_container">
            <h3>Matchs joués</h3>
            <div class="next_match_card_container">
                <?php
                    foreach ($AllmatchPlayed as $match) {
                        $homeClubId = $match['home_team_id'];
                        $visitorClubId = $match['visitor_team_id'];
                        $date = new DateTime($match['date']);

                        $url = "./create_match_results?match_id=" . $match['id'];

                ?>
                    <a href="<?php echo $url; ?>" class="next_match_card">
                        <div class="logo_container">
                            <img src="/BLOC3-DI23/front/assets/club_logo/<?php echo htmlspecialchars($homeClubId); ?>.png" alt="Logo domicile">
                            <span>-</span>
                            <img src="/BLOC3-DI23/front/assets/club_logo/<?php echo htmlspecialchars($visitorClubId); ?>.png" alt="Logo visiteur">
                        </div>
                        <div class="info_container">
                            <div class="match_date">
                                <h4 class="info_item">Date : </h4>
                                <span class="info_item"><?php echo $date->format('d/m/Y'); ?></span>
                            </div>
                        </div>
                    </a>
                <?php
                    }
                ?>
            </div>
        </div>
    </section>
</body>
</html>