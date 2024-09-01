<?php
    session_start();

    if(isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'coach') {
        require_once '../back/CRUD/club.php';
        require_once '../back/CRUD/coach.php';
        require_once '../back/CRUD/pre_match.php';
        require_once '../back/CRUD/pre_match_team_lineup_versus.php';

        if (isset($_SESSION['message'])) {
            // addslashes() allows to escape special characters
            $message = addslashes($_SESSION['message']);
            echo "<script>alert('$message');</script>";
            // Unset the message after displaying it
            unset($_SESSION['message']);
        }

        $clubId = $_SESSION['user_favorite_club_id'];

        $coachName = ucwords(getCoachNameByClubId($clubId));

        $nextMatchs = getAllPreMatchsOfClubWithStadiumName($clubId);

    } else {
        header('Location: ./home.php');
        exit();
    }

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gérer mon équipe</title>
    <link rel="stylesheet" href="./css/coach_management.css">
</head>
<body>
    <!-- Banner -->
    <section id="banner">
        <div>
            <img id="logo" src="./assets/club_logo/<?php echo htmlspecialchars($clubId); ?>.png" alt="">
        </div>
        <div id="coach_name_container">
            <h2>
                <?php
                    echo $coachName;
                ?>
            </h2>
            <h2>Entraineur</h2>
        </div>
    </section>
    <!-- Main container -->
    <section>
        <div id="next_match_container">
            <h3>Prochains matchs</h3>
            <div class="next_match_card_container">
                <?php
                    foreach ($nextMatchs as $match) {
                        $homeClubId = $match['home_team_id'];
                        $visitorClubId = $match['visitor_team_id'];
                        $date = new DateTime($match['date']);

                        $thisClubType = $homeClubId == $clubId ? "home_team_lineup_id" : "visitor_team_lineup_id";

                        $preMatchTeamLineupVersus = getPreMatchTeamLineupVersusById($match['pre_match_team_lineup_versus_id']);

                        $url = "";
                        if ($preMatchTeamLineupVersus[$thisClubType] == null) {
                            $url = "./coach_match_composition.php?match_id=" . $match['id'];
                        } else {
                            $url = "./match_sheet.php?match_id=" . $match['id'];
                        }

                        /*
                        echo "<pre>";
                        var_dump($match);
                        */
                ?>
                    <!-- <a href="./coach_match_composition.php?match_id=<?php echo $match['id']; ?>" class="next_match_card"> -->
                    <a href="<?php echo $url; ?>" class="next_match_card">
                        <div class="logo_container">
                            <img src="./assets/club_logo/<?php echo htmlspecialchars($homeClubId); ?>.png" alt="Logo domicile">
                            <span>-</span>
                            <img src="./assets/club_logo/<?php echo htmlspecialchars($visitorClubId); ?>.png" alt="Logo visiteur">
                        </div>
                        <div class="info_container">
                            <div class="match_date">
                                <h4 class="info_item">Date : </h4>
                                <span class="info_item"><?php echo $date->format('d/m/Y'); ?></span>
                            </div>
                            <div class="match_stadium">
                                <h4 class="info_item">Lieu :</h4>
                                <span class="info_item"><?php echo ucwords(htmlspecialchars($match['stadium_name'])); ?></span>
                            </div>
                        </div>
                    </a>
                <?php
                    }
                ?>
            </div>
            <!--
            <div class="next_match_card">
                <div class="logo_container">
                    <img src="./assets/club_logo/2.png" alt="">
                    <span>-</span>
                    <img src="./assets/club_logo/18.png" alt="">
                </div>
                <div class="info_container">
                    <div class="match_date">
                        <h4 class="info_item">Date : </h4>
                        <span class="info_item">01/01/2021</span>
                    </div>
                    <div class="match_stadium">
                        <h4 class="info_item">Lieu :</h4>
                        <span class="info_item">Stade de la Mosson</span>
                    </div>
                </div>
            </div>
            -->
        </div>
    </section>
</body>
</html>