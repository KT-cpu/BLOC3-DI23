<?php
    //require_once '../../back/CRUD/official.php';
    require_once dirname(__DIR__, 2) . '/back/CRUD/club.php';
    require_once dirname(__DIR__, 2) . '/back/CRUD/official.php';
    require_once dirname(__DIR__, 2) . '/back/CRUD/pre_match_official_lineup.php';
    require_once dirname(__DIR__, 2) . '/back/CRUD/pre_match_team_lineup_versus.php';
    require_once dirname(__DIR__, 2) . '/back/CRUD/pre_match.php';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $home_team_id = getClubIdByName(htmlspecialchars($_POST['home_team']));

        $visitor_team_id = getClubIdByName(htmlspecialchars($_POST['visitor_team']));

        $match_date = htmlspecialchars($_POST['match_date']);

        $main_official_id = getOfficialByName(htmlspecialchars($_POST['main_official']));

        $left_linesman_id = getOfficialByName(htmlspecialchars($_POST['left_linesman']));

        $right_linesman_id = getOfficialByName(htmlspecialchars($_POST['right_linesman']));

        $officialLineUp = getOfficialLineUp($main_official_id['id'], $left_linesman_id['id'], $right_linesman_id['id']);

        if (empty($officialLineUp)) {
            // Add official lineup and get the ID of the new line created
            $officialLineUp = addOfficialLineUp($main_official_id['id'], $left_linesman_id['id'], $right_linesman_id['id']);
        }

        // Add pre-match team lineup versus and get the ID of the new line created
        $preMatchTeamLineupVersus = addPreMatchTeamLineupVersus();

        // Add pre-match
        $isAddPreMatch = addPreMatch($home_team_id, $visitor_team_id, $preMatchTeamLineupVersus, $officialLineUp, $match_date);
        if ($isAddPreMatch) {
            $_SESSION['message'] = "Match created successfully.";
        } else {
            $_SESSION['message'] = "Match creation failed.";
        }
        header('Location: ./create_match');
        exit();
    } else {
        // Invalid request
        $_SESSION['message'] = 'Requête invalide.';
        header('Location: ./create_match');
        exit();
    }