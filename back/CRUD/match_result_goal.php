<?php
require_once dirname(__DIR__) . '/connexion.php';

/*
Create = /
Read =  -> 
Update =  -> 
Delete = /
*/

###################### CREATE ######################

function addGoal($team_lineup_player_selected_id, $goal_type_id, $goal_time) {
    $db = connexionDB();
    try {
        $sql = "INSERT INTO goal (team_lineup_player_selected_id, goal_type_id, goal_time) VALUES (:team_lineup_player_selected_id, :goal_type_id, :goal_time)";
        $req = $db->prepare($sql);
        $req->bindParam(':team_lineup_player_selected_id', $team_lineup_player_selected_id);
        $req->bindParam(':goal_type_id', $goal_type_id);
        $req->bindParam(':goal_time', $goal_time);
        $req->execute();
        return $db->lastInsertId();
    } catch (Exception $e) {
        echo 'Erreur : ' . $e->getMessage();
        return false;
    } finally {
        $db = null;
    }
}

function addMatchResultGoal($match_result_id, $goal_id) {
    $db = connexionDB();
    try {
        $sql = "INSERT INTO match_result_goal (match_result_id, goal_id) VALUES (:match_result_id, :goal_id)";
        $req = $db->prepare($sql);
        $req->bindParam(':match_result_id', $match_result_id);
        $req->bindParam(':goal_id', $goal_id);
        $req->execute();
        return $db->lastInsertId();
    } catch (Exception $e) {
        echo 'Erreur : ' . $e->getMessage();
        return false;
    } finally {
        $db = null;
    }
}