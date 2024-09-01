<?php
require_once dirname(__DIR__) . '/connexion.php';

/*
Create = /
Read = 12 -> 102
Update = 107 -> 118
Delete = /
*/

####################### READ #######################
function getAllPlayers() {
    $db = connexionDB();
    try {
        $sql = "SELECT * FROM player INNER JOIN nationality ON player.nationality_id = nationality.id INNER JOIN player_position ON player.player_position_name_id = player_position.id INNER JOIN club ON player.club_id = club.id ORDER BY player.id ASC";
        $req = $db->prepare($sql);
        $req->execute();
        return $req->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        echo 'Erreur : ' . $e->getMessage();
    }
}
//echo "<pre>";
//var_dump(getAllPlayers());




function getPlayerById($playerId) {
    $db = connexionDB();
    try {
        $sql = "SELECT * FROM player INNER JOIN nationality ON player.nationality_id = nationality.id INNER JOIN player_position ON player.player_position_name_id = player_position.id INNER JOIN club ON player.club_id = club.id WHERE player.id = :playerId";
        $req = $db->prepare($sql);
        $req->bindValue(':playerId', $playerId, PDO::PARAM_INT);
        $req->execute();
        return $req->fetch(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        echo 'Erreur : ' . $e->getMessage();
    }
}
//echo "<pre>";
//var_dump(getPlayerById(1));

// Cherche un joueur par une partie de son nom (LIKE) et retourne un tableau de joueurs
function getPlayerByName($playerName) {
    $db = connexionDB();
    try {
        $sql = "SELECT * FROM player INNER JOIN nationality ON player.nationality_id = nationality.id INNER JOIN player_position ON player.player_position_name_id = player_position.id INNER JOIN club ON player.club_id = club.id WHERE player_name LIKE :playerName";
        $req = $db->prepare($sql);
        $req->bindValue(':playerName', '%' . $playerName . '%', PDO::PARAM_STR);
        $req->execute();
        return $req->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        echo 'Erreur : ' . $e->getMessage();
    }
}
//echo "<pre>";
//var_dump(getPlayerByName('hernandez'));
//var_dump(getPlayerByName('lucas'));

function getPlayerByClub($clubName) {
    $db = connexionDB();
    try {
        $sql = "SELECT * FROM player INNER JOIN nationality ON player.nationality_id = nationality.id INNER JOIN player_position ON player.player_position_name_id = player_position.id INNER JOIN club ON player.club_id = club.id WHERE club.club_name = :clubName";
        $req = $db->prepare($sql);
        $req->bindValue(':clubName', $clubName, PDO::PARAM_STR);
        $req->execute();
        return $req->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        echo 'Erreur : ' . $e->getMessage();
    }
}
//echo "<pre>";
//var_dump(getPlayerByClub('paris saint-germain'));

function getPlayerByClubId($clubId) {
    $db = connexionDB();
    try {
        $sql = "SELECT *, player.id FROM player INNER JOIN nationality ON player.nationality_id = nationality.id INNER JOIN player_position ON player.player_position_name_id = player_position.id INNER JOIN club ON player.club_id = club.id WHERE club.id = :clubId";
        $req = $db->prepare($sql);
        $req->bindValue(':clubId', $clubId, PDO::PARAM_INT);
        $req->execute();
        return $req->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        echo 'Erreur : ' . $e->getMessage();
    }
}

// Cherche un joueur par son poste et retourne un tableau de joueurs
function getAllPlayerByPosition($playerPosition) {
    $db = connexionDB();
    try {
        $sql = "SELECT * FROM player INNER JOIN player_position ON player.player_position_name_id = player_position.id INNER JOIN nationality ON player.nationality_id = nationality.id INNER JOIN club ON player.club_id = club.id WHERE player_position.player_position_name = :playerPosition";
        $req = $db->prepare($sql);
        $req->bindValue(':playerPosition', $playerPosition, PDO::PARAM_STR);
        $req->execute();
        return $req->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        echo 'Erreur : ' . $e->getMessage();
    }
}
//echo "<pre>";
//var_dump(getAllPlayerByPosition('gardien'));

// Cherche un joueur par son poste et son club et retourne un tableau de joueurs
function getPlayerByPostionAndClub($playerPosition, $clubId) {
    $db = connexionDB();
    try {
        $sql = "SELECT * FROM player INNER JOIN nationality ON player.nationality_id = nationality.id INNER JOIN player_position ON player.player_position_name_id = player_position.id INNER JOIN club ON player.club_id = club.id WHERE player_position.player_position_name = :playerPosition AND club.club_name = :clubId";
        $req = $db->prepare($sql);
        $req->bindValue(':playerPosition', $playerPosition, PDO::PARAM_STR);
        $req->bindValue(':clubId', $clubId, PDO::PARAM_STR);
        $req->execute();
        return $req->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        echo 'Erreur : ' . $e->getMessage();
    }
}
//echo "<pre>";
//var_dump(getPlayerByPostionAndClub('attaquant', 'paris saint-germain'));

####################### UPDATE #######################
function setPlayerClub($player_id, $new_club_id) {
    $db = connexionDB();
    try {
        $sql = "UPDATE player SET club_id = :new_club_id WHERE id = :player_id";
        $req = $db->prepare($sql);
        $req->bindValue(':new_club_id', $new_club_id, PDO::PARAM_INT);
        $req->bindValue(':player_id', $player_id, PDO::PARAM_INT);
        $req->execute();
    } catch (Exception $e) {
        echo 'Erreur : ' . $e->getMessage();
    }
}
//setPlayerClub(1, 2);$$


//Stats du Joueur

function getMatchsJoues($player_id) {
    $db = connexionDB();
    try {
        $sql = "SELECT COUNT(*) as match_count FROM player_selected WHERE player_id = :player_id";
        $req = $db->prepare($sql);
        $req->bindParam(':player_id', $player_id, PDO::PARAM_INT);
        $req->execute();
        $result = $req->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['match_count'] : 0;
    } catch (Exception $e) {
        echo 'Erreur : ' . $e->getMessage();
        return 0;
    }
}

function getButsMarques($player_id) {
    $db = connexionDB();
    try {
        // Requête mise à jour pour récupérer les buts marqués par un joueur
        $sql = "
            SELECT COUNT(*) as but_count 
            FROM goal 
            INNER JOIN team_lineup_player_selected 
            ON goal.team_lineup_player_selected_id = team_lineup_player_selected.id
            INNER JOIN player_selected 
            ON team_lineup_player_selected.player_selected_id = player_selected.id
            WHERE player_selected.player_id = :player_id";
        
        $req = $db->prepare($sql);
        $req->bindParam(':player_id', $player_id, PDO::PARAM_INT);
        $req->execute();
        $result = $req->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['but_count'] : 0;
    } catch (Exception $e) {
        echo 'Erreur : ' . $e->getMessage();
        return 0;
    }
}

function getButsParType($player_id, $goal_type_id) {
    $db = connexionDB();
    try {
        $sql = "
            SELECT COUNT(*) as but_count 
            FROM goal 
            INNER JOIN team_lineup_player_selected 
            ON goal.team_lineup_player_selected_id = team_lineup_player_selected.id
            INNER JOIN player_selected 
            ON team_lineup_player_selected.player_selected_id = player_selected.id
            WHERE player_selected.player_id = :player_id
            AND goal.goal_type_id = :goal_type_id";
        
        $req = $db->prepare($sql);
        $req->bindParam(':player_id', $player_id, PDO::PARAM_INT);
        $req->bindParam(':goal_type_id', $goal_type_id, PDO::PARAM_INT);
        $req->execute();
        $result = $req->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['but_count'] : 0;
    } catch (Exception $e) {
        echo 'Erreur : ' . $e->getMessage();
        return 0;
    }
}


function getCartons($player_id, $card_type_id) {
    $db = connexionDB();
    try {
        // Requête pour récupérer le nombre de cartons (jaunes ou rouges) reçus par un joueur
        $sql = "
            SELECT COUNT(*) as card_count 
            FROM card 
            INNER JOIN team_lineup_player_selected 
            ON card.team_lineup_player_selected_id = team_lineup_player_selected.id
            INNER JOIN player_selected 
            ON team_lineup_player_selected.player_selected_id = player_selected.id
            WHERE player_selected.player_id = :player_id
            AND card.card_type_id = :card_type_id";
        
        $req = $db->prepare($sql);
        $req->bindParam(':player_id', $player_id, PDO::PARAM_INT);
        $req->bindParam(':card_type_id', $card_type_id, PDO::PARAM_INT);
        $req->execute();
        $result = $req->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['card_count'] : 0;
    } catch (Exception $e) {
        echo 'Erreur : ' . $e->getMessage();
        return 0;
    }
}