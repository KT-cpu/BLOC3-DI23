<?php
session_start();

if (isset($_SESSION['message'])) {
    // addslashes() allows to escape special characters
    $message = addslashes($_SESSION['message']);
    echo "<script>alert('$message');</script>";
    // Unset the message after displaying it
    unset($_SESSION['message']);
}

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
    require_once dirname(__DIR__) . '/back/CRUD/club.php';
    require_once dirname(__DIR__) . '/back/CRUD/official.php';
    
    $user_role = $_SESSION['user_role'];
    if ($user_role !== 'admin') {
        $_SESSION['message'] = "Vous n'êtes pas autorisé à accéder à cette page.";
        header('Location: ./home');
        exit();
    }
} else {
    $_SESSION['message'] = "Vous devez vous connecter pour accéder à cette page.";
    header('Location: ./login');
    exit();
}

$officials = getAllOfficials();
$clubsWithStadium = getAllclubsWithStadium();

date_default_timezone_set('Europe/Paris');
$currentDay = date('Y-m-d');
$currentTime = date('H:i');

$id = 1;
$pseudo = "admin";
$password = "admin";
$role = "admin";
$favoriteClubId = 1;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Programmer un match</title>
    <link rel="stylesheet" href="/BLOC3-DI23/front/css/create_match.css">
</head>
<body>
    <section id="main_container">
        <form id="match_form" action="./process_create_match" method="POST">
            <div id="left_container">
                <div id="home_teamContainer" class="form_item">
                    <!-- Home team -->
                    <label for="home_team" class="pointer" >Équipe domicile :</label>
                    <select name="home_team" id="home_team" class="pointer" required>
                        <option value="" disabled selected>Choisir une équipe domicile</option>
                        <?php foreach ($clubsWithStadium as $club) {
                            $clubName = htmlspecialchars($club['club_name']); // Encode HTML special characters
                            $formattedClubName = ucwords($clubName); // Capitalize the first letter of each word
                            echo '<option value="' . $clubName . '">' . $formattedClubName . '</option>';
                        } ?>
                    </select>
                </div>
                <div class="form_item">
                    <!-- Visitor team -->
                    <label for="visitor_team" class="pointer" >Équipe extérieure :</label>
                    <select name="visitor_team" id="visitor_team" class="pointer" required>
                        <option value="" disabled selected>Choisir une équipe extérieure</option>
                        <?php foreach ($clubsWithStadium as $club) {
                            $clubName = htmlspecialchars($club['club_name']); // Encode HTML special characters
                            $formattedClubName = ucwords($clubName); // Capitalize the first letter of each word
                                echo '<option value="' . $clubName . '">' . $formattedClubName . '</option>';
                        } ?>
                    </select>
                </div>
                <!-- Date & time -->
                <div class="form_item">
                    <label for="match_date" class="pointer">Choisi une date et une heure :</label>
                    <input
                    required
                    class="pointer" 
                    type="datetime-local"
                    id="match_date"
                    name="match_date"
                    min="<?php echo $currentDay . "T" . $currentTime ?>"
                    />
                </div>
                <!-- Main official -->
                <div class="form_item">
                    <label for="main_official" class="pointer">Arbitre principal :</label>
                    <select name="main_official" id="main_official" class="pointer" required>
                        <option value="" disabled selected>Choisir un arbitre principal</option>
                        <?php foreach ($officials as $official) {
                            $officialName = htmlspecialchars($official['official_name']); // Encode HTML special characters
                            $formattedOfficialName = ucwords($officialName); // Capitalize the first letter of each word
                            echo '<option value="' . $officialName . '">' . $formattedOfficialName . '</option>';
                        } ?>
                    </select>
                </div>
                <!-- Left linesman -->
                <div class="form_item">
                    <label for="left_linesman" class="pointer">Arbitre de touche gauche :</label>
                    <select name="left_linesman" id="left_linesman" class="pointer" required>
                        <option value="" disabled selected>Choisir un arbitre de touche gauche</option>
                        <?php foreach ($officials as $official) {
                            $officialName = htmlspecialchars($official['official_name']); // Encode HTML special characters
                            $formattedOfficialName = ucwords($officialName); // Capitalize the first letter of each word
                            echo '<option value="' . $officialName . '">' . $formattedOfficialName . '</option>';
                        } ?>
                    </select>
                </div>
                <!-- Right linesman -->
                <div class="form_item">
                    <label for="right_linesman" class="pointer">Arbitre de touche droit :</label>
                    <select name="right_linesman" id="right_linesman" class="pointer" required>
                        <option value="" disabled selected>Choisir un arbitre de touche droit</option>
                        <?php foreach ($officials as $official) {
                            $officialName = htmlspecialchars($official['official_name']); // Encode HTML special characters
                            $formattedOfficialName = ucwords($officialName); // Capitalize the first letter of each word
                            echo '<option value="' . $officialName . '">' . $formattedOfficialName . '</option>';
                        } ?>
                    </select>
                </div>
            </div>
            <div id="right_container">
                <!-- Submit button -->
                 <div id="summary_container">
                    <h2 id="summary_title">Récapitulatif</h2>
                    <div id="summary_info_container">
                        <div id="summary_info_club" >
                            <p>Équipe domicile : <span id="home_team_recap"></span></p>
                            <p>Équipe extérieure : <span id="visitor_team_recap"></span></p>
                        </div>
                        <div id="summary_info_calendar">
                            <p>Lieu : <span id="match_city_recap"></span></p>
                            <p>Date : <span id="match_date_recap"></span></p>
                            <p>Heure : <span id="match_hours_recap"></span></p>
                        </div>
                        <div id="summary_info_official">
                            <p>Arbitre principal : <span id="main_official_recap"></span></p>
                            <p>Arbitre de touche gauche : <span id="left_linesman_recap"></span></p>
                            <p>Arbitre de touche droit : <span id="right_linesman_recap"></span></p>
                        </div>
                    </div>
                 </div>
                <div>
                    <button type="submit" id="submit" class="pointer">Programmer le match</button>
                </div>
            </div>
        </form>
    </section>
</body>
<script>
        // Définir la variable clubsWithStadium
        let clubsWithStadium = <?php echo json_encode($clubsWithStadium); ?>;
    </script>
<script src="/BLOC3-DI23/front/js/create_match.js"></script>
</html>