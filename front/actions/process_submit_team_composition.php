<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: ./login');
    exit();
}

require_once dirname(__DIR__, 2) . '/back/CRUD/pre_match.php';
require_once dirname(__DIR__, 2) . '/back/CRUD/player_selected.php';
require_once dirname(__DIR__, 2) . '/back/CRUD/player.php';
require_once dirname(__DIR__, 2) . '/back/CRUD/team_lineup.php';
require_once dirname(__DIR__, 2) . '/back/CRUD/team_lineup_player_selected.php';
require_once dirname(__DIR__, 2) . '/back/CRUD/pre_match_team_lineup_versus.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $match_id = $_POST['match_id'];
    $coach_club_id = $_POST['coach_club_id'];
    $players = json_decode($_POST['players'], true);

    list($preMatchTeamLineupVersus, $match) = getMatchAndTeamLineupType($match_id, $coach_club_id);

    $selectedPlayersLineUp = [];
    $needNewTeamLineUp = false;

    processPlayers($players, $selectedPlayersLineUp, $needNewTeamLineUp);

    $teamLineUpId = null;
    if (!$needNewTeamLineUp) {
        $teamLineUpId = checkIfTeamLineupExists($selectedPlayersLineUp, $coach_club_id);
        if ($teamLineUpId === null) {
            $needNewTeamLineUp = true;
        }
    }

    if ($needNewTeamLineUp) {
        $teamLineUpId = createNewTeamLineupAndAddPlayers($coach_club_id, $selectedPlayersLineUp);
    }

    if(updatePreMatch($match_id, $match['pre_match_team_lineup_versus_id'] ,$teamLineUpId, $preMatchTeamLineupVersus)) {
        $_SESSION['message'] = "Composition de l'équipe enregistrée avec succès.";

    } else {
        $_SESSION['message'] = "Echec de l'enregistrement de la composition de l'équipe.";
    }
    header('Location: ./coach_management');
    exit();
}

function getMatchAndTeamLineupType($match_id, $coach_club_id) {
    $match = getPreMatchById($match_id);
    if (!$match) {
        $_SESSION['message'] = "Le match n'existe pas.";
        header('Location: ./coach_management');
        exit();
    }
    if ($match['home_team_id'] == $coach_club_id) {
        return ['home_team_lineup_id', $match];
        //return 'home_team_lineup_id';
    } elseif ($match['visitor_team_id'] == $coach_club_id) {
        return ['visitor_team_lineup_id', $match];
        //return 'visitor_team_lineup_id';
    } else {
        $_SESSION['message'] = "Vous n'êtes pas l'entraineur de l'une des deux équipes.";
        header('Location: ./coach_management');
        exit();
    }
}

function processPlayers($players, &$selectedPlayersLineUp, &$needNewTeamLineUp) {
    foreach ($players as $player) {
        $playerSelectedId = getPlayerSelected($player['id'], $player['position_id'], $player['captain'], $player['sub_captain']);
        if ($playerSelectedId) {
            $selectedPlayersLineUp[] = $playerSelectedId;
        } else {
            $needNewTeamLineUp = true;
            $playerNumber = getPlayerById($player['id'])['player_number'];
            $playerSelectedId = addPlayerSelected($player['id'], $player['position_id'], $player['captain'], $player['sub_captain'], $playerNumber);
            $selectedPlayersLineUp[] = $playerSelectedId;
        }
    }
}

function checkIfTeamLineupExists($selectedPlayersLineUp, $coach_club_id) {
    $allTeamLineUpOfClub = getAllTeamLineupByClubId($coach_club_id);
    foreach ($allTeamLineUpOfClub as $teamLineUp) {
        $allTeamLineupPlayerSelectedById = getAllTeamLineupPlayerSelectedById($teamLineUp['id']);
        if (count($selectedPlayersLineUp) == count($allTeamLineupPlayerSelectedById)) {
            $currentTeamPlayerIds = array_column($allTeamLineupPlayerSelectedById, 'player_selected_id');
            if (!array_diff($selectedPlayersLineUp, $currentTeamPlayerIds)) {
                return $teamLineUp['id'];
            }
        }
    }
    return null;
}

function createNewTeamLineupAndAddPlayers($coach_club_id, $selectedPlayersLineUp) {
    $teamLineUpId = addNewTeamLineup($coach_club_id);
    foreach ($selectedPlayersLineUp as $selectedPlayerId) {
        addPlayerSelectedToTeamLineup($teamLineUpId, $selectedPlayerId);
    }
    return $teamLineUpId;
}

function updatePreMatch($match_id, $preMatchTeamLineupVersusId, $teamLineUpId, $preMatchTeamLineupVersus) {
    $teamSide = null;
    if ($preMatchTeamLineupVersus === 'home_team_lineup_id') {
        $teamSide = 'home_team_lineup_id';
    } elseif ($preMatchTeamLineupVersus === 'visitor_team_lineup_id') {
        $teamSide = 'visitor_team_lineup_id';
    }
    return updatePreMatchTeamLineupVersus($match_id, $teamSide, $teamLineUpId);
}
?>
