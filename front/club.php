<?php
    session_start();

    if (isset($_SESSION['message'])) {
        // addslashes() allows to escape special characters
        $message = addslashes($_SESSION['message']);
        echo "<script>alert('$message');</script>";
        // Unset the message after displaying it
        unset($_SESSION['message']);
    }

    require_once dirname(__DIR__) . '/back/CRUD/club.php';

    $clubId = $_GET['club_id'];
    $clubData = getAllDataOfClubById($clubId);

    //echo "<pre>";
    //var_dump($clubData);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Equipe</title>
    <link rel="stylesheet" href="./css/club.css">
</head>
<body>
    <section>
        <div id="banner_container">
            <img src="./assets/club_logo/<?php echo $clubId; ?>.png" alt="logo" id="club_logo">
            <p id="stadium_name"><?php echo "Stade : " . ucwords($clubData['stadium_name']); ?></p>
        </div>
    </section>
    <section id="main_container">
        <h2><?php echo strtoupper($clubData["club_name"]);?></h2>
        <div id="club_data_container">
            <div id="club_general_data" class="data_container">
                <h3>GÉNÉRAL</h3>
                <div id="card_general" class="card">
                    <ul>
                        <li>Matchs Gagnés : 10
                        </li>
                        <li>Matchs Perdus : 1
                        </li>
                        <li>% de Victoires : 90%
                        </li>
                        <li>Buts Marqués : 39
                        </li>
                        <li>Buts Encaissés : 6
                        </li>
                        <li>Différence de buts : + 33
                        </li>
                        <li>Plus Grande Victoire : 5-1
                        </li>
                        <li>Plus Grande Défaite : 0-2
                        </li>
                        <li>Cartons Rouges : 1
                        </li>
                        <li>Cartons Jaunes : 7</li>
                    </ul>
                </div>
            </div>
            <div id="club_top_scorer_data" class="data_container">
                <h3>MEILLEURS BUTEURS</h3>
                <div id="card_top_scorer" class="card">
                    <div>
                        <img src="" alt="" id="top_scorer_img">
                        <p>1 - </p>
                    </div>
                    <div id="off_top_soccer">
                        <p>2 - </p>
                        <p>3 - </p>
                        <p>4 - </p>
                        <p>5 - </p>
                    </div>

                </div>
            </div>
            <div id="club_top_scorer_data" class="data_container">
                <h3>EQUIPE TYPE</h3>
                <div id="starting_team_container" class="card">
                    <div id="guardian_container" class="position_container">
                        <!-- Garidan players -->
                    </div>
                    <div id="defender_container" class="position_container">
                        <!-- Defender players -->
                    </div>
                    <div id="midfielder_container" class="position_container">
                        <!-- Midfielder players -->
                    </div>
                    <div id="attacker_container" class="position_container">
                        <!-- Attacker players -->
                    </div>
                </div>
            </div>
            <div id="club_top_scorer_data" class="data_container">
                <h3>ENTRAINEURS</h3>
                <div class="card coach-card">
                    <p>Entraineur : </p>
                </div>
                <h3>PROCHAIN MATCH</h3>
                <div class="card next-match-card">
                    <p>Coup d'envoi : </p>
                    <p>Date - Heure</p>
                    <p>Adversaire : </p>
                    <p>Lieu : </p>
            </div>
        </div>
    </section>
</body>
</html>