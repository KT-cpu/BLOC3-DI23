<?php
require_once dirname(__DIR__) . '/connexion.php';

/*
Create = /
Read =  -> 
Update =  -> 
Delete = /
*/

###################### CREATE ######################

function addMatchResult($pre_match_id, $winner_club_id, $total_time){
    $db = connexionDB();
    try {
        $sql = "INSERT INTO match_result (pre_match_id, winner_club_id, total_time) VALUES (:pre_match_id, :winner_club_id, :total_time)";
        $req = $db->prepare($sql);
        $req->bindParam(':pre_match_id', $pre_match_id);
        $req->bindParam(':winner_club_id', $winner_club_id);
        $req->bindParam(':total_time', $total_time);
        $req->execute();
        return $db->lastInsertId();
    } catch (Exception $e) {
        echo 'Erreur : ' . $e->getMessage();
        return false;
    } finally {
        $db = null;
    }
}