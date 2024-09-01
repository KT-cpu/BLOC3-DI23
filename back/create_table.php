<?php
function addTable($db, $dbName) {
    $tables = [
        "nationality" => "CREATE TABLE IF NOT EXISTS nationality (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nationality_name VARCHAR(255) NOT NULL,
            nationality_flag VARCHAR(255)
        )",
        "coach_job" => "CREATE TABLE IF NOT EXISTS coach_job (
            id INT AUTO_INCREMENT PRIMARY KEY,
            coach_job_name VARCHAR(255) NOT NULL
        )",
        "stadium" => "CREATE TABLE IF NOT EXISTS stadium (
            id INT AUTO_INCREMENT PRIMARY KEY,
            stadium_name VARCHAR(255) NOT NULL,
            stadium_capacity INT NOT NULL,
            stadium_city VARCHAR(255) NOT NULL,
            stadium_image VARCHAR(255) NOT NULL
        )",
        "club" => "CREATE TABLE IF NOT EXISTS club (
            id INT AUTO_INCREMENT PRIMARY KEY,
            stadium_id INT NOT NULL,
            club_name VARCHAR(255) NOT NULL,
            club_logo VARCHAR(255) NOT NULL,
            FOREIGN KEY (stadium_id) REFERENCES stadium(id)
        )",
        "coach" => "CREATE TABLE IF NOT EXISTS coach (
            id INT AUTO_INCREMENT PRIMARY KEY,
            coach_job_name_id INT NOT NULL,
            club_id INT NOT NULL,
            coach_name VARCHAR(255) NOT NULL,
            FOREIGN KEY (club_id) REFERENCES club(id),
            FOREIGN KEY (coach_job_name_id) REFERENCES coach_job(id)
        )",
        "player_position" => "CREATE TABLE IF NOT EXISTS player_position (
            id INT AUTO_INCREMENT PRIMARY KEY,
            player_position_name VARCHAR(255) NOT NULL
        )",
        "player" => "CREATE TABLE IF NOT EXISTS player (
            id INT AUTO_INCREMENT PRIMARY KEY,
            club_id INT NOT NULL,
            player_position_name_id INT NOT NULL,
            nationality_id INT,
            player_name VARCHAR(255) NOT NULL,
            player_number INT,
            player_picture VARCHAR(255),
            FOREIGN KEY (club_id) REFERENCES club(id),
            FOREIGN KEY (nationality_id) REFERENCES nationality(id),
            FOREIGN KEY (player_position_name_id) REFERENCES player_position(id)
        )",
        "player_selected" => "CREATE TABLE IF NOT EXISTS player_selected (
            id INT AUTO_INCREMENT PRIMARY KEY,
            player_id INT NOT NULL,
            player_selected_position_name_id INT NOT NULL,
            player_selected_captain BOOLEAN NOT NULL,
            player_selected_captain_substitute BOOLEAN NOT NULL,
            player_selected_number INT,
            FOREIGN KEY (player_id) REFERENCES player(id),
            FOREIGN KEY (player_selected_position_name_id) REFERENCES player_position(id)
        )",
        "team_lineup" => "CREATE TABLE IF NOT EXISTS team_lineup (
            id INT AUTO_INCREMENT PRIMARY KEY,
            club_id INT NOT NULL,
            FOREIGN KEY (club_id) REFERENCES club(id)
        )",
        "team_lineup_player_selected" => "CREATE TABLE IF NOT EXISTS team_lineup_player_selected (
            id INT AUTO_INCREMENT PRIMARY KEY,
            team_lineup_id INT NOT NULL,
            player_selected_id INT NOT NULL,
            FOREIGN KEY (team_lineup_id) REFERENCES team_lineup(id),
            FOREIGN KEY (player_selected_id) REFERENCES player_selected(id)
        )",
        "pre_match_team_lineup_versus" => "CREATE TABLE IF NOT EXISTS pre_match_team_lineup_versus (
            id INT AUTO_INCREMENT PRIMARY KEY,
            home_team_lineup_id INT NULL,
            visitor_team_lineup_id INT NULL,
            FOREIGN KEY (home_team_lineup_id) REFERENCES team_lineup(id),
            FOREIGN KEY (visitor_team_lineup_id) REFERENCES team_lineup(id)
        )",
        "official" => "CREATE TABLE IF NOT EXISTS official (
            id INT AUTO_INCREMENT PRIMARY KEY,
            official_name VARCHAR(255) NOT NULL
        )",
        "pre_match_official_lineup" => "CREATE TABLE IF NOT EXISTS pre_match_official_lineup (
            id INT AUTO_INCREMENT PRIMARY KEY,
            referee_official_id INT NOT NULL,
            linesmen_left_official_id INT NOT NULL,
            linesmen_right_official_id INT NOT NULL
        )",
        "pre_match" => "CREATE TABLE IF NOT EXISTS pre_match (
            id INT AUTO_INCREMENT PRIMARY KEY,
            home_team_id INT NOT NULL,
            visitor_team_id INT NOT NULL,
            pre_match_team_lineup_versus_id INT NOT NULL,
            pre_match_official_lineup_id INT NOT NULL,
            date DATETIME NOT NULL,
            FOREIGN KEY (home_team_id) REFERENCES club(id),
            FOREIGN KEY (visitor_team_id) REFERENCES club(id),
            FOREIGN KEY (pre_match_team_lineup_versus_id) REFERENCES pre_match_team_lineup_versus(id),
            FOREIGN KEY (pre_match_official_lineup_id) REFERENCES pre_match_official_lineup(id)
        )",
        "match_result" => "CREATE TABLE IF NOT EXISTS match_result (
            id INT AUTO_INCREMENT PRIMARY KEY,
            pre_match_id INT NOT NULL,
            winner_club_id INT NOT NULL,
            total_time INT NOT NULL,
            FOREIGN KEY (winner_club_id) REFERENCES club(id),
            FOREIGN KEY (pre_match_id) REFERENCES pre_match(id)
        )",
        "substitution" => "CREATE TABLE IF NOT EXISTS substitution (
            id INT AUTO_INCREMENT PRIMARY KEY,
            go_out_team_lineup_player_selected_id INT NOT NULL,
            go_in_team_lineup_player_selected_id INT NOT NULL,
            substitution_time INT NOT NULL,
            FOREIGN KEY (go_out_team_lineup_player_selected_id) REFERENCES team_lineup_player_selected(id),
            FOREIGN KEY (go_in_team_lineup_player_selected_id) REFERENCES team_lineup_player_selected(id)
        )",
        "match_result_substitution" => "CREATE TABLE IF NOT EXISTS match_result_substitution (
            id INT AUTO_INCREMENT PRIMARY KEY,
            match_result_id INT NOT NULL,
            substitution_id INT NOT NULL,
            FOREIGN KEY (match_result_id) REFERENCES match_result(id),
            FOREIGN KEY (substitution_id) REFERENCES substitution(id)
        )",
        "goal_type" => "CREATE TABLE IF NOT EXISTS goal_type (
            id INT AUTO_INCREMENT PRIMARY KEY,
            goal_type_name VARCHAR(255) NOT NULL
        )",
        "goal" => "CREATE TABLE IF NOT EXISTS goal (
            id INT AUTO_INCREMENT PRIMARY KEY,
            team_lineup_player_selected_id INT NOT NULL,
            goal_type_id INT NOT NULL,
            goal_time INT NOT NULL,
            FOREIGN KEY (team_lineup_player_selected_id) REFERENCES team_lineup_player_selected(id),
            FOREIGN KEY (goal_type_id) REFERENCES goal_type(id)
        )",
        "match_result_goal" => "CREATE TABLE IF NOT EXISTS match_result_goal (
            id INT AUTO_INCREMENT PRIMARY KEY,
            match_result_id INT NOT NULL,
            goal_id INT NOT NULL,
            FOREIGN KEY (match_result_id) REFERENCES match_result(id),
            FOREIGN KEY (goal_id) REFERENCES goal(id)
        )",
        "card_type" => "CREATE TABLE IF NOT EXISTS card_type (
            id INT AUTO_INCREMENT PRIMARY KEY,
            card_type_name VARCHAR(255) NOT NULL
        )",
        "card" => "CREATE TABLE IF NOT EXISTS card (
            id INT AUTO_INCREMENT PRIMARY KEY,
            team_lineup_player_selected_id INT NOT NULL,
            card_type_id INT NOT NULL,
            card_time INT NOT NULL,
            FOREIGN KEY (team_lineup_player_selected_id) REFERENCES team_lineup_player_selected(id),
            FOREIGN KEY (card_type_id) REFERENCES card_type(id)
        )",
        "match_result_card" => "CREATE TABLE IF NOT EXISTS match_result_card (
            id INT AUTO_INCREMENT PRIMARY KEY,
            match_result_id INT NOT NULL,
            card_id INT NOT NULL,
            FOREIGN KEY (match_result_id) REFERENCES match_result(id),
            FOREIGN KEY (card_id) REFERENCES card(id)
        )",
        "user" => "CREATE TABLE IF NOT EXISTS user (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_favorite_club_id INT,
            user_pseudo VARCHAR(255) NOT NULL,
            user_password VARCHAR(255) NOT NULL,
            user_role VARCHAR(255) NOT NULL DEFAULT 'supporter',
            UNIQUE (user_pseudo),
            FOREIGN KEY (user_favorite_club_id) REFERENCES club(id)
        )",
    ];
    $db->exec("CREATE DATABASE ".$dbName);
    $db->exec("USE ".$dbName);
    foreach ($tables as $table_name => $tableQuery) {
        createTable($db, $tableQuery, $table_name);
    }
    return $db;
}

function createTable($db, $tableQuery, $table_name) {
    $db->exec("DROP TABLE IF EXISTS {$table_name}");
    $db->exec($tableQuery);
    return $db;
}