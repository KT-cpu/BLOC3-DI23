<?php
require_once dirname(__DIR__) . '/connexion.php';

/*
Create = /
Read =  -> 
Update =  -> 
Delete = /
*/

###################### CREATE ######################

function addCard($team_lineup_player_selected_id, $card_type_id, $card_time) {
    $db = connexionDB();
    try {
        $sql = "INSERT INTO card (team_lineup_player_selected_id, card_type_id, card_time) VALUES (:team_lineup_player_selected_id, :card_type_id, :card_time)";
        $req = $db->prepare($sql);
        $req->bindParam(':team_lineup_player_selected_id', $team_lineup_player_selected_id);
        $req->bindParam(':card_type_id', $card_type_id);
        $req->bindParam(':card_time', $card_time);
        $req->execute();
        return $db->lastInsertId();
    } catch (Exception $e) {
        echo 'Erreur : ' . $e->getMessage();
        return false;
    } finally {
        $db = null;
    }
}

function addMatchResultCard($match_result_id, $card_id) {
    $db = connexionDB();
    try {
        $sql = "INSERT INTO match_result_card (match_result_id, card_id) VALUES (:match_result_id, :card_id)";
        $req = $db->prepare($sql);
        $req->bindParam(':match_result_id', $match_result_id);
        $req->bindParam(':card_id', $card_id);
        $req->execute();
        return $db->lastInsertId();
    } catch (Exception $e) {
        echo 'Erreur : ' . $e->getMessage();
        return false;
    } finally {
        $db = null;
    }
}