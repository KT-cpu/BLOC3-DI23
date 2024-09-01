<?php
session_start();

if (isset($_SESSION['user_role']) && $_SESSION['user_role'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}

require_once dirname(__DIR__, 2) . '/back/CRUD/match_result.php';
require_once dirname(__DIR__, 2) . '/back/CRUD/match_result_goal.php';
require_once dirname(__DIR__, 2) . '/back/CRUD/match_result_card.php';
require_once dirname(__DIR__, 2) . '/back/CRUD/match_result_substitution.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {



    $match_id = $_POST['match_id'];
    echo "Match id: ";
    echo $match_id;
    echo "<br>";

    $winner_club = $_POST['winner_club'];
    echo "Winner club: ";
    echo $winner_club;
    echo "<br>";

    $match_duration = $_POST['match_duration'];
    echo "Match duration: ";
    echo $match_duration;
    echo "<br>";

    $home_event = json_decode($_POST['home_event'], true);
    $visitor_event = json_decode($_POST['visitor_event'], true);

    echo "<pre>";
    echo "Home event";
    echo "<br>";
    var_dump($home_event);
    echo "<br>";
    echo "<br>";

    echo "Visitor event";
    echo "<br>";
    var_dump($visitor_event);
    echo "<br>";
    echo "<br>";


    $matchResultId = addMatchResult($match_id, $winner_club, $match_duration);
    echo "Match result id: ";
    echo $matchResultId;
    echo "<br>";

    echo "home_event";
    echo "<br>";
    foreach($home_event["goal"] as $goal) {
        $goalId = addGoal($goal["scorer"], $goal["goal_type"], $goal["time"]);
        echo "Goal id: ";
        echo $goalId;
        echo "<br>";

        $matchResultGoalId = addMatchResultGoal($matchResultId, $goalId);
        echo "Match result goal id: ";
        echo $matchResultGoalId;
        echo "<br>";
    };

    foreach($home_event["yellow_card"] as $yellow_card) {
        $yellowCardId = addCard($yellow_card["player"], 1, $yellow_card["time"]);
        echo "Yellow card id: ";
        echo $yellowCardId;
        echo "<br>";

        $matchResultYellowCardId = addMatchResultCard($matchResultId, $yellowCardId);
        echo "Match result yellow card id: ";
        echo $matchResultYellowCardId;
        echo "<br>";
    };

    foreach($home_event["red_card"] as $red_card) {
        $redCardId = addCard($red_card["player"], 2, $red_card["time"]);
        echo "Red card id: ";
        echo $redCardId;
        echo "<br>";

        $matchResultRedCardId = addMatchResultCard($matchResultId, $redCardId);
        echo "Match result red card id: ";
        echo $matchResultRedCardId;
        echo "<br>";
    };

    foreach($home_event["substitution"] as $substitution) {
        $substitutionId = addSubstitution($substitution["playerGoOut"], $substitution["playerGoIn"], $substitution["time"]);
        echo "Substitution id: ";
        echo $substitutionId;
        echo "<br>";
    
        $matchResultSubstitutionId = addMatchResultSubstitution($matchResultId, $substitutionId);
        echo "Match result substitution id: ";
        echo $matchResultSubstitutionId;
        echo "<br>";
    };

    echo "visitor_event";
    echo "<br>";
    foreach($visitor_event["goal"] as $goal) {
        $goalId = addGoal($goal["scorer"], $goal["goal_type"], $goal["time"]);
        echo "Goal id : ";
        echo $goalId;
        echo "<br>";

        $matchResultGoalId = addMatchResultGoal($matchResultId, $goalId);
        echo "Match result goal id : ";
        echo $matchResultGoalId;
        echo "<br>";
    };

    foreach($visitor_event["yellow_card"] as $yellow_card) {
        $yellowCardId = addCard($yellow_card["player"], 2, $yellow_card["time"]);
        echo "Yellow card id: ";
        echo $yellowCardId;
        echo "<br>";

        $matchResultYellowCardId = addMatchResultCard($matchResultId, $yellowCardId);
        echo "Match result yellow card id: ";
        echo $matchResultYellowCardId;
        echo "<br>";
    };

    foreach($visitor_event["red_card"] as $red_card) {
        $redCardId = addCard($red_card["player"], 2, $red_card["time"]);
        echo "Red card id: ";
        echo $redCardId;
        echo "<br>";

        $matchResultRedCardId = addMatchResultCard($matchResultId, $redCardId);
        echo "Match result red card id: ";
        echo $matchResultRedCardId;
        echo "<br>";
    };


    foreach($visitor_event["substitution"] as $substitution) {
        $substitutionId = addSubstitution($substitution["playerGoOut"], $substitution["playerGoIn"], $substitution["time"]);
        echo "Substitution id: ";
        echo $substitutionId;
        echo "<br>";
    
        $matchResultSubstitutionId = addMatchResultSubstitution($matchResultId, $substitutionId);
        echo "Match result substitution id: ";
        echo $matchResultSubstitutionId;
        echo "<br>";
    };
}
?>