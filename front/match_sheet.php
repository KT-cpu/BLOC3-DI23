<?php
session_start();
if (isset($_SESSION['message'])) {
    // addslashes() allows to escape special characters
    $message = addslashes($_SESSION['message']);
    echo "<script>alert('$message');</script>";
    // Unset the message after displaying it
    unset($_SESSION['message']);
}
require_once dirname(__DIR__) . '/back/CRUD/pre_match.php';
require_once dirname(__DIR__) . '/back/CRUD/pre_match_team_lineup_versus.php';
require_once dirname(__DIR__) . '/back/CRUD/pre_match_official_lineup.php';

$matchId = $_GET['match_id'];
$match = getPreMatchById($matchId);

$teamLineup = getPreMatchTeamLineupVersusById($match["pre_match_team_lineup_versus_id"]);
if($teamLineup["home_team_lineup_id"] == null || $teamLineup["visitor_team_lineup_id"] == null) {
    $_SESSION['message'] = "Les compositions des équipes ne sont pas encore prêtes.";
    header('Location: ./coach_management');
    exit();
}

$stadiumName = ucwords($match['stadium_name']);
$date = new DateTime($match['date']);
$date = $date->format('d/m/Y');

$officials = getOfficialNameOfLineUpById($match["pre_match_official_lineup_id"]);

$homeLineUp = getHomeTeamLineup($matchId);
$homeData = getHomeClubNameAndCoachName($matchId);

$visitorLineUp = getVisitorTeamLineup($matchId);
$visitorData = getVisitorClubNameAndCoachName($matchId);

function addPlayerList($lineUp, $playerPosition){
    $first = true;
    if($lineUp == null) {
        return;
    }
    foreach($lineUp as $player) {
        if ($player["player_position_name"] == $playerPosition) {
            if (!$first) {
                echo ', ';
            }
            echo ucwords($player['player_name']);
            $first = false;
        }
    }
}

function createSoccerFieldPosition($lineUp, $playerPosition) {
    if($lineUp == null) {
        return;
    }
    foreach($lineUp as $player) {
        if ($player["player_position_name"] == $playerPosition) {
            $playerName = ucwords($player['player_name']);
            if(str_word_count($playerName) == 1) {
                echo '<span>' . htmlspecialchars($playerName) . '</span>';
            } else {
                $nameParts = explode(' ', $playerName);
                array_shift($nameParts);
                $remainingName = implode(' ', $nameParts);
                echo '<span>' . htmlspecialchars($remainingName) . '</span>';
            }
        }
    }
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Avant match</title>
    <link rel="stylesheet" href="/BLOC3-DI23/front/css/match_sheet.css">
</head>
<body>
    <section id="information_title_container">
        <h2>HOME - VISITOR</h2>
        <p>Date : <span><?php echo $date ?></span> - Lieu : <span><?php echo $stadiumName ?></span></p>
        <p>Arbitre du match : <span><?php echo $officials["referee_official_name"] ?></span> - Arbitres adjoints : <span><?php echo $officials["linesmen_left_official_name"] . ", " . $officials["linesmen_right_official_name"] ?></span></p>
    </section>
    <section id="main_container">
        <div id="home_club_container">
            <div>
                <img src="/BLOC3-DI23/front/assets/club_logo/<?php echo $match['home_team_id']?>.png" alt="home_club_logo">
                <h3>Composition du <?php echo ucwords($homeData["club_name"]) ?></h3>
            </div>
            <div>
                <ul>
                    <li>Entraineur : <?php echo ucwords($homeData["coach_name"]) ?></li>
                    <li>Gardien : 
                        <?php 
                            addPlayerList($homeLineUp, 'Gardien')
                        ?>
                    </li>
                    <li>Defenseurs : 
                        <?php 
                            addPlayerList($homeLineUp, 'Défenseur')
                        ?>
                    </li>
                    <li>Milieux : 
                        <?php 
                            addPlayerList($homeLineUp, 'Milieu')
                        ?>
                    </li>
                    <li>Attaquants : 
                        <?php 
                            addPlayerList($homeLineUp, 'Attaquant')
                        ?>
                    </li>
                    <li>Remplaçants : 
                        <?php 
                            addPlayerList($homeLineUp, 'Remplaçant')
                        ?>
                    </li>
                </ul>
            </div>
        </div>
        <div id="soccer_field_container">
            <img id="soccer_field_container_img" src="/BLOC3-DI23/front/assets/soccer_field_vertical.jpg" alt="">
            <div id="home_guardian_container" class="position_container home_player_field">
                <?php 
                    createSoccerFieldPosition($homeLineUp, 'Gardien');
                ?>
            </div>
            <div id="home_defender_container" class="position_container home_player_field">
                <?php 
                    createSoccerFieldPosition($homeLineUp, 'Défenseur');
                ?>
            </div>
            <div id="home_midfielder_container" class="position_container home_player_field">
                <?php 
                    createSoccerFieldPosition($homeLineUp, 'Milieu');
                ?>
            </div>
            <div id="home_attacker_container" class="position_container home_player_field">
                <?php 
                    createSoccerFieldPosition($homeLineUp, 'Attaquant');
                ?>
            </div>
            <div id="visitor_guardian_container" class="position_container visitor_player_field">
                <?php 
                    createSoccerFieldPosition($visitorLineUp, 'Gardien');
                ?>
            </div>
            <div id="visitor_defender_container" class="position_container visitor_player_field">
                <?php 
                    createSoccerFieldPosition($visitorLineUp, 'Défenseur');
                ?>
            </div>
            <div id="visitor_midfielder_container" class="position_container visitor_player_field">
                <?php 
                    createSoccerFieldPosition($visitorLineUp, 'Milieu');
                ?>
            </div>
            <div id="visitor_attacker_container" class="position_container visitor_player_field">
                <?php 
                    createSoccerFieldPosition($visitorLineUp, 'Attaquant');
                ?>
            </div>
        </div>
        <div id="visitor_club_container">
            <div>
                <img src="/BLOC3-DI23/front/assets/club_logo/<?php echo $match['visitor_team_id']?>.png" alt="visitor_club_logo">
                <h3>Composition du <?php echo ucwords($visitorData["club_name"]) ?></h3>
            </div>
            <div>
                <ul>
                    <li>Entraineur : <?php echo ucwords($visitorData["coach_name"]) ?></li>
                    <li>Gardien : 
                        <?php 
                            addPlayerList($visitorLineUp, 'Gardien')
                        ?>
                    </li>
                    <li>Defenseurs : 
                        <?php 
                            addPlayerList($visitorLineUp, 'Défenseur')
                        ?>
                    </li>
                    <li>Milieux : 
                        <?php 
                            addPlayerList($visitorLineUp, 'Milieu')
                        ?>
                    </li>
                    <li>Attaquants : 
                        <?php 
                            addPlayerList($visitorLineUp, 'Attaquant')
                        ?>
                    </li>
                    <li>Remplaçants : 
                        <?php 
                            addPlayerList($visitorLineUp, 'Remplaçant')
                        ?>
                    </li>
                </ul>
            </div>
        </div>
    </section>
</body>
<script src="/BLOC3-DI23/front/js/match_sheet.js"></script>
</html>