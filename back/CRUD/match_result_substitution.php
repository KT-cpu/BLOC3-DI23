<?php
require_once dirname(__DIR__) . '/connexion.php';

/*
Create = /
Read =  -> 
Update =  -> 
Delete = /
*/

###################### CREATE ######################

function addSubstitution($go_out_team_lineup_player_selected_id, $go_in_team_lineup_player_selected_id, $substitution_time) {
    $db = connexionDB();
    try {
        $sql = "INSERT INTO substitution (go_out_team_lineup_player_selected_id, go_in_team_lineup_player_selected_id, substitution_time) VALUES (:go_out_team_lineup_player_selected_id, :go_in_team_lineup_player_selected_id, :substitution_time)";
        $req = $db->prepare($sql);
        $req->bindParam(':go_out_team_lineup_player_selected_id', $go_out_team_lineup_player_selected_id);
        $req->bindParam(':go_in_team_lineup_player_selected_id', $go_in_team_lineup_player_selected_id);
        $req->bindParam(':substitution_time', $substitution_time);
        $req->execute();
        return $db->lastInsertId();
    } catch (Exception $e) {
        echo 'Erreur : ' . $e->getMessage();
        return false;
    } finally {
        $db = null;
    }
}

function addMatchResultSubstitution($match_result_id, $substitution_id) {
    $db = connexionDB();
    try {
        $sql = "INSERT INTO match_result_substitution (match_result_id, substitution_id) VALUES (:match_result_id, :substitution_id)";
        $req = $db->prepare($sql);
        $req->bindParam(':match_result_id', $match_result_id);
        $req->bindParam(':substitution_id', $substitution_id);
        $req->execute();
        return $db->lastInsertId();
    } catch (Exception $e) {
        echo 'Erreur : ' . $e->getMessage();
        return false;
    } finally {
        $db = null;
    }
}