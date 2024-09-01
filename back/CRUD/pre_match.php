<?php
require_once dirname(__DIR__) . '/connexion.php';

/*
Create = /
Read =  -> 
Update =  -> 
Delete = /
*/

###################### CREATE ######################

function addPreMatch($home_team_id, $visitor_team_id, $pre_match_team_lineup_versus_id, $pre_match_official_lineup_id, $date ){
    $db = connexionDB();
    try {
        $sql = "INSERT INTO pre_match (home_team_id, visitor_team_id, pre_match_team_lineup_versus_id, pre_match_official_lineup_id, date) VALUES (:home_team_id, :visitor_team_id, :pre_match_team_lineup_versus_id, :pre_match_official_lineup_id, :date)";
        $req = $db->prepare($sql);
        $req->bindParam(':home_team_id', $home_team_id);
        $req->bindParam(':visitor_team_id', $visitor_team_id);
        $req->bindParam(':pre_match_team_lineup_versus_id', $pre_match_team_lineup_versus_id);
        $req->bindParam(':pre_match_official_lineup_id', $pre_match_official_lineup_id);
        $req->bindParam(':date', $date);
        $req->execute();
        return true;
    } catch (Exception $e) {
        echo 'Erreur : ' . $e->getMessage();
        return false;
    } finally {
        $db = null;
    }
}

####################### READ #######################

function getAllPreMatchsOfClubWithStadiumName($clubId) {
    $db = connexionDB();
    try {
        $sql = "SELECT pre_match.id, pre_match.date, pre_match.home_team_id, pre_match.visitor_team_id, pre_match.pre_match_team_lineup_versus_id, stadium.stadium_name FROM pre_match INNER JOIN club ON pre_match.home_team_id = club.id INNER JOIN stadium ON club.stadium_id = stadium.id WHERE home_team_id = :clubId OR visitor_team_id = :clubId ORDER BY date DESC";
        $req = $db->prepare($sql);
        $req->bindParam(':clubId', $clubId);
        $req->execute();
        return $req->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        echo 'Erreur : ' . $e->getMessage();
    }
}

function getPreMatchById($preMatchId) {
    $db = connexionDB();
    try {
        $sql = "SELECT pre_match.*, stadium.stadium_name FROM pre_match INNER JOIN club ON pre_match.home_team_id = club.id INNER JOIN stadium ON club.stadium_id = stadium.id WHERE pre_match.id = :preMatchId";
        $req = $db->prepare($sql);
        $req->bindParam(':preMatchId', $preMatchId);
        $req->execute();
        return $req->fetch(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        echo 'Erreur : ' . $e->getMessage();
    }
}

function getHomeTeamLineup($preMatchId) {
    $db = connexionDB();
    try {
        $sql = "SELECT player.id as player_id ,player.player_name, player_position.player_position_name, team_lineup_player_selected.id as team_lineup_player_selected_id  FROM pre_match
        INNER JOIN pre_match_team_lineup_versus ON pre_match.pre_match_team_lineup_versus_id = pre_match_team_lineup_versus.id
        INNER JOIN team_lineup ON pre_match_team_lineup_versus.home_team_lineup_id = team_lineup.id
        INNER JOIN team_lineup_player_selected ON team_lineup.id = team_lineup_player_selected.team_lineup_id
        INNER JOIN player_selected ON team_lineup_player_selected.player_selected_id = player_selected.id
        INNER JOIN player ON player_selected.player_id = player.id
        INNER JOIN player_position ON player_selected.player_selected_position_name_id = player_position.id
        WHERE pre_match.id = :preMatchId";
        $req = $db->prepare($sql);
        $req->bindParam(':preMatchId', $preMatchId);
        $req->execute();
        return $req->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        echo 'Erreur : ' . $e->getMessage();
    }
}

function getHomeClubNameAndCoachName($preMatchId) {
    $db = connexionDB();
    try {
        $sql = "SELECT club.club_name, coach.coach_name FROM pre_match
        INNER JOIN pre_match_team_lineup_versus ON pre_match.pre_match_team_lineup_versus_id = pre_match_team_lineup_versus.id
        INNER JOIN team_lineup ON pre_match_team_lineup_versus.home_team_lineup_id = team_lineup.id
        INNER JOIN club ON team_lineup.club_id = club.id
        INNER JOIN coach ON club.id = coach.club_id
        INNER JOIN coach_job ON coach.coach_job_name_id = coach_job.id
        WHERE pre_match.id = :preMatchId AND coach_job.coach_job_name = 'entraineur'";
        $req = $db->prepare($sql);
        $req->bindParam(':preMatchId', $preMatchId);
        $req->execute();
        return $req->fetch(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        echo 'Erreur : ' . $e->getMessage();
    }
}

function getVisitorTeamLineup($preMatchId) {
    $db = connexionDB();
    try {
        $sql = "SELECT player.id as player_id, player.player_name, player_position.player_position_name, team_lineup_player_selected.id as team_lineup_player_selected_id FROM pre_match
        INNER JOIN pre_match_team_lineup_versus ON pre_match.pre_match_team_lineup_versus_id = pre_match_team_lineup_versus.id
        INNER JOIN team_lineup ON pre_match_team_lineup_versus.visitor_team_lineup_id = team_lineup.id
        INNER JOIN team_lineup_player_selected ON team_lineup.id = team_lineup_player_selected.team_lineup_id
        INNER JOIN player_selected ON team_lineup_player_selected.player_selected_id = player_selected.id
        INNER JOIN player ON player_selected.player_id = player.id
        INNER JOIN player_position ON player_selected.player_selected_position_name_id = player_position.id
        WHERE pre_match.id = :preMatchId";
        $req = $db->prepare($sql);
        $req->bindParam(':preMatchId', $preMatchId);
        $req->execute();
        return $req->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        echo 'Erreur : ' . $e->getMessage();
    }
}

function getVisitorClubNameAndCoachName($preMatchId) {
    $db = connexionDB();
    try {
        $sql = "SELECT club.club_name, coach.coach_name FROM pre_match
        INNER JOIN pre_match_team_lineup_versus ON pre_match.pre_match_team_lineup_versus_id = pre_match_team_lineup_versus.id
        INNER JOIN team_lineup ON pre_match_team_lineup_versus.visitor_team_lineup_id = team_lineup.id
        INNER JOIN club ON team_lineup.club_id = club.id
        INNER JOIN coach ON club.id = coach.club_id
        INNER JOIN coach_job ON coach.coach_job_name_id = coach_job.id
        WHERE pre_match.id = :preMatchId AND coach_job.coach_job_name = 'entraineur'";
        $req = $db->prepare($sql);
        $req->bindParam(':preMatchId', $preMatchId);
        $req->execute();
        return $req->fetch(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        echo 'Erreur : ' . $e->getMessage();
    }
}

function getAllPreMatchPlayed(){
    $db = connexionDB();
    try {
        $sql = "SELECT * FROM pre_match
        INNER JOIN pre_match_team_lineup_versus ON pre_match.pre_match_team_lineup_versus_id = pre_match_team_lineup_versus.id
        WHERE pre_match_team_lineup_versus.home_team_lineup_id IS NOT NULL AND pre_match_team_lineup_versus.visitor_team_lineup_id IS NOT NULL
        ORDER BY pre_match.date DESC";
        $req = $db->prepare($sql);
        $req->execute();
        return $req->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        echo 'Erreur : ' . $e->getMessage();
    }
}

function getAllPreMatchNotPlayed(){
    $db = connexionDB();
    try {
        $sql = "SELECT * FROM pre_match
        LEFT JOIN match_result ON pre_match.id = match_result.pre_match_id
        INNER JOIN pre_match_team_lineup_versus ON pre_match.pre_match_team_lineup_versus_id = pre_match_team_lineup_versus.id
        WHERE match_result.id IS NULL 
        AND pre_match_team_lineup_versus.home_team_lineup_id IS NOT NULL 
        AND pre_match_team_lineup_versus.visitor_team_lineup_id IS NOT NULL;";
        $req = $db->prepare($sql);
        $req->execute();
        return $req->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        echo 'Erreur : ' . $e->getMessage();
    }
}

function getClubsOfMatch($preMatchId) {
    $db = connexionDB();
    try {
        $sql = "SELECT home_club.id AS home_club_id, home_club.club_name AS home_club_name, visitor_club.id AS visitor_club_id, visitor_club.club_name AS visitor_club_name
        FROM pre_match
        INNER JOIN club AS home_club ON pre_match.home_team_id = home_club.id
        INNER JOIN club AS visitor_club ON pre_match.visitor_team_id = visitor_club.id
        WHERE pre_match.id = :preMatchId";
        $req = $db->prepare($sql);
        $req->bindParam(':preMatchId', $preMatchId);
        $req->execute();
        return $req->fetch(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        echo 'Erreur : ' . $e->getMessage();
    }
}

?>