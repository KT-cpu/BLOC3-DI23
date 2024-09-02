<?php
session_start();

if (isset($_SESSION['user_role']) && $_SESSION['user_role'] !== 'admin') {
    header('Location: ./login');
    exit();
}

require_once dirname(__DIR__, 2) . '/back/CRUD/match_result.php';
require_once dirname(__DIR__, 2) . '/back/CRUD/match_result_goal.php';
require_once dirname(__DIR__, 2) . '/back/CRUD/match_result_card.php';
require_once dirname(__DIR__, 2) . '/back/CRUD/match_result_substitution.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $match_id = $_POST['match_id'];

    $winner_club = $_POST['winner_club'];

    $match_duration = $_POST['match_duration'];

    $home_event = json_decode($_POST['home_event'], true);
    $visitor_event = json_decode($_POST['visitor_event'], true);

    $matchResultId = addMatchResult($match_id, $winner_club, $match_duration);

    foreach($home_event["goal"] as $goal) {
        $goalId = addGoal($goal["scorer"], $goal["goal_type"], $goal["time"]);

        $matchResultGoalId = addMatchResultGoal($matchResultId, $goalId);
    };

    foreach($home_event["yellow_card"] as $yellow_card) {
        $yellowCardId = addCard($yellow_card["player"], 1, $yellow_card["time"]);

        $matchResultYellowCardId = addMatchResultCard($matchResultId, $yellowCardId);
    };

    foreach($home_event["red_card"] as $red_card) {
        $redCardId = addCard($red_card["player"], 2, $red_card["time"]);

        $matchResultRedCardId = addMatchResultCard($matchResultId, $redCardId);
    };

    foreach($home_event["substitution"] as $substitution) {
        $substitutionId = addSubstitution($substitution["playerGoOut"], $substitution["playerGoIn"], $substitution["time"]);
    
        $matchResultSubstitutionId = addMatchResultSubstitution($matchResultId, $substitutionId);
    };

    foreach($visitor_event["goal"] as $goal) {
        $goalId = addGoal($goal["scorer"], $goal["goal_type"], $goal["time"]);

        $matchResultGoalId = addMatchResultGoal($matchResultId, $goalId);
    };

    foreach($visitor_event["yellow_card"] as $yellow_card) {
        $yellowCardId = addCard($yellow_card["player"], 2, $yellow_card["time"]);

        $matchResultYellowCardId = addMatchResultCard($matchResultId, $yellowCardId);
    };

    foreach($visitor_event["red_card"] as $red_card) {
        $redCardId = addCard($red_card["player"], 2, $red_card["time"]);

        $matchResultRedCardId = addMatchResultCard($matchResultId, $redCardId);
    };


    foreach($visitor_event["substitution"] as $substitution) {
        $substitutionId = addSubstitution($substitution["playerGoOut"], $substitution["playerGoIn"], $substitution["time"]);
    
        $matchResultSubstitutionId = addMatchResultSubstitution($matchResultId, $substitutionId);
    };

    $_SESSION['message'] = "Résultat du match enregistré avec succès.";
    header('Location: ./result_match_management');
    exit();
}
?>