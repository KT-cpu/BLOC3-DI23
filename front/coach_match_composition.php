<?php
    session_start();
    if($_SESSION['user_role'] == 'coach') {
        $matchId = $_GET['match_id'];
        if(isset($matchId) && !empty($matchId) && is_numeric($matchId)) {

            require_once '../back/CRUD/pre_match.php';
            require_once '../back/CRUD/pre_match_team_lineup_versus.php';

            $match = getPreMatchById($matchId);

            //echo "<pre>";
            //var_dump($match);
            if($_SESSION['user_favorite_club_id'] == $match['home_team_id'] || $_SESSION['user_favorite_club_id'] == $match['visitor_team_id']) {
                require_once '../back/CRUD/club.php';
                require_once '../back/CRUD/player.php';
                require_once '../back/CRUD/player_position.php';
                require_once '../back/CRUD/pre_match_team_lineup_versus.php';

                $match = getPreMatchById($matchId);
                $thisClubType = $match['home_team_id'] == $_SESSION['user_favorite_club_id'] ? "home_team_lineup_id" : "visitor_team_lineup_id";

                $preMatchTeamLineupVersus = getPreMatchTeamLineupVersusById($match['pre_match_team_lineup_versus_id']);

                if ($preMatchTeamLineupVersus[$thisClubType] !== null) {
                    $_SESSION['message'] = "Composition de l'équipe déjà enregistrée.";
                    header('Location: ./coach_management.php');
                    exit();
                }
                
                $coachClubId = $_SESSION['user_favorite_club_id'] == $match['home_team_id'] ? $match['home_team_id'] : $match['visitor_team_id'];
                $opponentClubId = $_SESSION['user_favorite_club_id'] == $match['home_team_id'] ? $match['visitor_team_id'] : $match['home_team_id'];
                $homeClubId = $match['home_team_id'];
                $visitorClubId = $match['visitor_team_id'];
                $date = new DateTime($match['date']);
                $playerPositions = getAllPlayerPositions();
                $players = getPlayerByClubId($coachClubId);
                //echo "<pre>";
                //var_dump($players);
            } else {
                $_SESSION['message'] = "Accès refusé. Vous n'êtes pas l'entraineur de ce match.";
                header('Location: ./coach_management.php');
                exit();
            }
        } else {
            $_SESSION['message'] = 'Accès refusé. Match non spécifié.';
            header('Location: ./coach_management.php');
            exit();
        }
    } else {
        $_SESSION['message'] = "Vous n'êtes pas autorisé à accéder à cette page.";
        header('Location: ./home.php');
        exit();
    }
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Composition du match</title>
    <link rel="stylesheet" href="./css/coach_match_composition.css">
</head>
<body>
    <section id="next_match_container">
        <div class="next_match_card">
            <div class="logo_container">
                <img src="./assets/club_logo/<?php echo htmlspecialchars($homeClubId); ?>.png" alt="">
                <span>-</span>
                <img src="./assets/club_logo/<?php echo htmlspecialchars($visitorClubId); ?>.png" alt="">
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
        </div>
        <h2>Composition <?php echo ucwords(getClubNameById($coachClubId))?> contre <?php echo ucwords(getClubNameById($opponentClubId)) ?></h2>
    </section>
    <section id="main_container">
        <div id="left_container">
            <div class="player_selection_container">
                <div class="player_selection selection">
                    <!-- <label for="playerSelect">Ajouter Joueur :</label> -->
                    <button type="button" id="add_player_btn" class="add_btn">Ajouter Joueur</button>
                    <select id="player_select">
                        <option value="" disabled selected>Nom</option>
                        <?php foreach ($playerPositions as $position): ?>
                            <optgroup label="<?php echo htmlspecialchars($position['player_position_name']); ?>">
                                <?php foreach ($players as $player):
                                    if ($player['player_position_name_id'] == $position['id']): ?>
                                        <option value="<?php echo $player['id']; ?>"><?php echo ucwords(htmlspecialchars($player['player_name'])); ?></option>
                                    <?php endif;
                                endforeach; ?>
                            </optgroup>
                        <?php endforeach; ?>
                    </select>
                    <select id="player_position">
                        <option value="" disabled selected>Poste</option>
                        <?php foreach ($playerPositions as $position): ?>
                            <option value="<?php echo $position['id']; ?>"><?php echo ucwords(htmlspecialchars($position['player_position_name'])); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="selection">
                    <button type="button" id="delete_player_btn" class="add_btn">Retirer Joueur</button>
                    <select id="player_delete_select">
                        <option value="" disabled selected>Nom</option>
                    </select>
                </div>
            </div>
            <div class="player_selection_container">
                <div class="selection">
                    <button type="button" id="add_captain_btn" class="add_btn">Capitaine</button>
                    <select id="captain_select">
                        <option value="" disabled selected>Nom</option>
                    </select>
                </div>
                <div class="selection">
                    <button type="button" id="add_sub_captain_btn" class="add_btn">Suppléant</button>
                    <select id="sub_captain_select">
                        <option value="" disabled selected>Nom</option>
                    </select>
                </div>
            </div>
        </div>
        <div id="middle_container">
            <h3>Titulaires</h3>
            <!-- <div id="soccer_field_container">
            </div> -->
            <div id="soccer_field_container">
                <img src="./assets/soccer_field.jpg" alt="" id="soccer_field_image">
                <div id="guardian_container" class="position_container">
                </div>
                <div id="defender_container" class="position_container">
                </div>
                <div id="midfielder_container" class="position_container">
                </div>
                <div id="attacker_container" class="position_container">
                </div>
            </div>
            <div>
                <ul>
                    <li id="captain_li">Capitaine : <span></span></li>
                    <li id="sub_captain_li">Suppléant : <span></span></li>
                    <li id="sub_player_li">Remplaçants : </li>
                </ul>
            </div>
        </div>
        <div id="right_container">
            <form action="./actions/process_submit_team_composition.php" method="POST" id="team_form">
                <input type="hidden" name="match_id" id="match_id">
                <input type="hidden" name="coach_club_id" id="coach_club_id">
                <input type="hidden" name="players" id="players">
                <button type="button" id="submit_btn">Valider la Composition</button>
            </form>
        </div>
    </section>
</body>
<script>
    const matchId = <?php echo $matchId; ?>;
    const coachClubId = <?php echo $coachClubId; ?>;
</script>
<script src="./js/coach_match_composition.js"></script>
</html>