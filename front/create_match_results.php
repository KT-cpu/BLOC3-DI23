<?php 
    session_start();

    if(isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'admin'){
        require_once '../back/CRUD/club.php';
        require_once '../back/CRUD/coach.php';
        require_once '../back/CRUD/pre_match.php';
        require_once '../back/CRUD/pre_match_team_lineup_versus.php';
        require_once '../back/CRUD/goal_type.php';

        echo "You are a admin.";

        if (isset($_SESSION['message'])) {
            // addslashes() allows to escape special characters
            $message = addslashes($_SESSION['message']);
            echo "<script>alert('$message');</script>";
            // Unset the message after displaying it
            unset($_SESSION['message']);
        }

        $matchId = $_GET['match_id'];
        if(isset($matchId) && !empty($matchId) && is_numeric($matchId)) {
            $clubs = getClubsOfMatch($matchId);

            $homeClub = getHomeTeamLineup($matchId);

            $visitorClub = getVisitorTeamLineup($matchId);

            $goalTypes = getAllGoalType();
            
        } else {
            $_SESSION['message'] = "Invalide match id.";
            header('Location: ./result_match_management.php');
            exit();
        }
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
    <title>Choisir un match</title>
    <link rel="stylesheet" href="./css/create_match_results.css">
</head>
<body>
    <section id="main_container">
         <div id="global_inputs_container">
            <!-- Tous les inputs pour rentrer les données du match -->
            <div>
                <label for="winner_select">Equipe gagnante : </label>
                <select name="winner_select" id="winner_select">
                    <option value="" disabled selected>Gagnant</option>
                    <?php
                        echo "<option value='" . $clubs['home_club_id'] . "'>" . ucwords($clubs['home_club_name']) . "</option>";
                        echo "<option value='" . $clubs['visitor_club_id'] . "'>" . ucwords($clubs['visitor_club_name']) . "</option>";
                    ?>
                </select>
            </div>
            <div>
                <label for="">Le match à duré :</label>
                <input type="number" name="match_duration_number" id="match_duration_number" min="0" required>
            </div>
        </div>
        <div id="clubs_container">
            <div id="home_club_container">
                <h4>Ajouter un évenement pour <?php echo  ucwords($clubs["home_club_name"]) ?></h4>
                <!-- Inputs pour l'équipe home, ajout de but, carton et changement -->
                <!-- <label for="club_select">Choisir une équipe</label>
                <select name="club_select" id="club_select">
                    <option value="" disabled selected>Choisir une équipe</option>
                    <option value="<?php echo ucwords($clubs["home_club_id"])?>"><?php echo ucwords($clubs["home_club_name"])?></option>
                    <option value="<?php echo ucwords($clubs["visitor_club_id"])?>"><?php echo ucwords($clubs["visitor_club_name"])?></option>
                </select> -->

                <div id="event_time_home_club_container">
                    <label for="event_time_home_club">Choisir la minutes de l'eventement</label>
                    <input type="number" name="event_time_home_club" id="event_time_home_club" min="0">
                </div>

                <div id="event_type_home_club_container">
                    <label for="event_type_home_club">Choisir le type de l'eventement</label>
                    <select name="event_type_home_club" id="event_type_home_club">
                        <option value="" disabled selected>Choisir le type</option>
                        <option value="goal">But</option>
                        <option value="yellow_card">Carton jaune</option>
                        <option value="red_card">Carton rouge</option>
                        <option value="substitution">Changement</option>
                    </select>
                </div>

                <div id="home_goal_type_select_container">
                    <label for="home_goal_type_select" id="home_goal_type_label" >Choisir le type de but</label>
                    <select name="home_goal_type_select" id="home_goal_type_select">
                        <option value="" disabled selected>Choisir le type de but</option>
                        <?php
                            foreach ($goalTypes as $goalType) {
                                echo "<option value='" . $goalType['id'] . "'>" . ucwords($goalType['goal_type_name']) . "</option>";
                            }
                        ?>
                    </select>
                </div>

                <div id="player_select_home_club_container">
                    <label for="player_select_home_club">Choisir le joueur</label>
                    <select name="player_select_home_club" id="player_select_home_club">
                        <option value="" disabled selected>Choisir le joueur</option>
                        <?php
                            // Condition pour afficher les joueurs selon l'équipe choisie
                            foreach ($homeClub as $player) {
                                echo "<option value='" . $player['team_lineup_player_selected_id'] . "'>" . ucwords($player['player_name']) . "</option>";
                            }
                        ?>
                    </select>
                </div>

                <div id="substitution_player_select_home_club_container">
                    <label for="substitution_player_select_home_club">Choisir le remplaçant</label>
                    <select name="substitution_player_select_home_club" id="substitution_player_select_home_club">
                        <option value="" disabled selected>Choisir le remplaçant</option>
                        <?php
                            // Condition pour afficher les joueurs selon l'équipe choisie
                            foreach ($homeClub as $player) {
                                echo "<option value='" . $player['team_lineup_player_selected_id'] . "'>" . ucwords($player['player_name']) . "</option>";
                            }
                        ?>
                    </select>
                </div>

                <button id="home_button_event">Ajouter</button>
            </div>
            <!-- VISITOR -->
            <div id="visitor_club_container">
                <h4>Ajouter un évenement pour <?php echo ucwords($clubs["visitor_club_name"]) ?></h4>
                <!-- Inputs pour l'équipe home, ajout de but, carton et changement -->
                <!-- <label for="club_select">Choisir une équipe</label>
                <select name="club_select" id="club_select">
                    <option value="" disabled selected>Choisir une équipe</option>
                    <option value="<?php echo ucwords($clubs["home_club_id"])?>"><?php echo ucwords($clubs["home_club_name"])?></option>
                    <option value="<?php echo ucwords($clubs["visitor_club_id"])?>"><?php echo ucwords($clubs["visitor_club_name"])?></option>
                </select> -->

                <div id="event_time_visitor_club_container">
                    <label for="event_time_visitor_club">Choisir la minutes de l'eventement</label>
                    <input type="number" name="event_time_visitor_club" id="event_time_visitor_club" min="0">
                </div>

                <div id="event_type_visitor_club_container">
                    <label for="event_type_visitor_club">Choisir le type de l'eventement</label>
                    <select name="event_type_visitor_club" id="event_type_visitor_club">
                        <option value="" disabled selected>Choisir le type</option>
                        <option value="goal">But</option>
                        <option value="yellow_card">Carton jaune</option>
                        <option value="red_card">Carton rouge</option>
                        <option value="substitution">Changement</option>
                    </select>
                </div>

                <div id="visitor_goal_type_select_container">
                    <label for="visitor_goal_type_select" id="visitor_goal_type_label" >Choisir le type de but</label>
                    <select name="visitor_goal_type_select" id="visitor_goal_type_select">
                        <option value="" disabled selected>Choisir le type de but</option>
                        <?php
                            foreach ($goalTypes as $goalType) {
                                echo "<option value='" . $goalType['id'] . "'>" . ucwords($goalType['goal_type_name']) . "</option>";
                            }
                        ?>
                    </select>
                </div>

                <div id="player_select_visitor_club_container">
                    <label for="player_select_visitor_club">Choisir le joueur</label>
                    <select name="player_select_visitor_club" id="player_select_visitor_club">
                        <option value="" disabled selected>Choisir le joueur</option>
                        <?php
                            // Condition pour afficher les joueurs selon l'équipe choisie
                            foreach ($visitorClub as $player) {
                                echo "<option value='" . $player['team_lineup_player_selected_id'] . "'>" . ucwords($player['player_name']) . "</option>";
                            }
                        ?>
                    </select>
                </div>

                <div id="substitution_player_select_visitor_club_container">
                    <label for="substitution_player_select_visitor_club">Choisir le remplaçant</label>
                    <select name="substitution_player_select_visitor_club" id="substitution_player_select_visitor_club">
                        <option value="" disabled selected>Choisir le remplaçant</option>
                        <?php
                            // Condition pour afficher les joueurs selon l'équipe choisie
                            foreach ($visitorClub as $player) {
                                echo "<option value='" . $player['team_lineup_player_selected_id'] . "'>" . ucwords($player['player_name']) . "</option>";
                            }
                        ?>
                    </select>
                </div>

                <button id="visitor_button_event">Ajouter</button>
            </div>
        </div>
    </section>
    <section>
        <!-- Récapitulatif des données du match -->
        <div>
            <h3>Récapitulatif</h3>
            <p>Equipe gagnante : <span id="winner_club_name"></span></p>
            <p>Durée du match : <span id="match_duration_span"></span></p>
            <div id="recap_clubs_container">
                <div id="recap_home_club_container">
                    <h4>Evenement : <?php echo ucwords($clubs["home_club_name"])?> </h4>
                    <div>
                        <p>But marqué : </p>
                        <ul id="goal_home_club_info">
                        </ul>
                    </div>
                    <div>
                        <p>Carton : </p>
                        <ul id="card_home_club_info">
                        </ul>
                    </div> 
                    <div>
                        <p>Changement : </p>
                        <ul id="substitution_home_club_info">
                        </ul>
                    </div>      
                </div>  
                <div id="recap_visitor_club_container">
                    <h4>Evenement : <?php echo ucwords($clubs["visitor_club_name"])?> </h4>
                    <div>
                        <p>But marqué : </p>
                        <ul id="goal_visitor_club_info">
                        </ul>
                    </div>
                    <div>
                        <p>Carton : </p>
                        <ul id="card_visitor_club_info">
                        </ul>
                    </div> 
                    <div>
                        <p>Changement : </p>
                        <ul id="substitution_visitor_club_info">
                        </ul>
                    </div>      
                </div> 
            </div>
        </div>
        <div>
            <!-- Formulaire d'envoi au back -->
            <form action="./actions/process_create_match_results.php" method="POST">
                <input type="hidden" name="match_id" id="match_id" value="<?php  echo $matchId ?>">
                <input type="hidden" name="winner_club" id="winner_club" required>
                <input type="hidden" name="match_duration" id="match_duration" required>
                <input type="hidden" name="home_event" id="home_event">
                <input type="hidden" name="visitor_event" id="visitor_event">
                <input type="submit" id="submit_btn" value="Valider">
            </form>
        </div>
    </section>
</body>
<script src="./js/create_match_results.js"></script>
</html>