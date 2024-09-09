<?php

session_start();
    if($_SESSION['user_role'] == 'supporter') {

            
            function getLatestMatchbyClub() {
            $db = connexionDB();
            try {
                $sql = "SELECT pre_match.* stadium.stadium_name FROM pre_match INNER JOIN club ON pre_match.home_team_id = club.id INNER JOIN stadium ON club.stadium_id = stadium.id WHERE user.user_favorite_club_id == pre_match.home_team_id || user.user_favorite_club_id == pre_match.visitor_team_id ORDER BY ID DESC LIMIT 0,1";
                $req = $db->prepare($sql);
                $req->execute();
                return $req->fetch(PDO::FETCH_ASSOC);
            } catch (Exception $e) {
                echo 'Erreur : ' . $e->getMessage();
            }
        }


        function getLatestResultsbyClub() {
            $db = connexionDB();
            try {
                $sql = "SELECT pre_match.* stadium.stadium_name FROM match_result INNER JOIN pre_match ON match_result.pre_match_id = pre_match.id INNER JOIN club ON pre_match.home_team_id = club.id INNER JOIN stadium ON club.stadium_id = stadium.id WHERE user.user_favorite_club_id == pre_match.home_team_id || user.user_favorite_club_id == pre_match.visitor_team_id ORDER BY ID DESC LIMIT 0,1";
                $req = $db->prepare($sql);
                $req->execute();
                return $req->fetch(PDO::FETCH_ASSOC);
            } catch (Exception $e) {
                echo 'Erreur : ' . $e->getMessage();
            }
        }


        function getLatestMatchId() {
            $db = connexionDB();
            try {
                $sql = "SELECT id FROM match_result_goal ORDER BY ID DESC LIMIT 0,1";
                $req = $db->prepare($sql);
                $req->execute();
                return $req->fetch(PDO::FETCH_ASSOC);
            } catch (Exception $e) {
                echo 'Erreur : ' . $e->getMessage();
            }
        }


        function getLatestPointsbyClub($MatchResId) {
            $db = connexionDB();
            try {
                $sql = "SELECT goal.id FROM match_result_goal INNER JOIN goal ON match_result_goal.goal_id WHERE user.user_favorite_club_id == match_result.winner_club_id AND WHERE match_result_goal.id = :MatchResId ORDER BY ID DESC LIMIT 0,1";
                $req = $db->prepare($sql);
                $req->bindParam(':MatchResId', $MatchResId);
                $req->execute();
                return $req->fetch(PDO::FETCH_ASSOC);
            } catch (Exception $e) {
                echo 'Erreur : ' . $e->getMessage();
            }
        }


        $LatestMatch = $getLatestMatchbyClub();
        $coachClubId = $_SESSION['user_favorite_club_id'] == $LatestMatch['home_team_id'] ? $LatestMatch['home_team_id'] : $LatestMatch['visitor_team_id'];
        $opponentClubId = $_SESSION['user_favorite_club_id'] == $LatestMatch['home_team_id'] ? $LatestMatch['visitor_team_id'] : $LatestMatch['home_team_id'];
        $homeClubId = $LatestMatch['home_team_id'];
        $visitorClubId = $LatestMatch['visitor_team_id'];
        $date = $LatestMatch['date'];
        $lieu = $LatestMatch['stadium_name'];

        $IdMatchRes = getLatestMatchId();
        $MatchPoint = getLatestPointsbyClub($IdMatchRes);
        $LatestResults = $getLatestResultsbyClub();
        $coachClubId2 = $_SESSION['user_favorite_club_id'] == $LatestResults['home_team_id'] ? $LatestResults['home_team_id'] : $LatestResults['visitor_team_id'];
        $opponentClubId2 = $_SESSION['user_favorite_club_id'] == $LatestResults['home_team_id'] ? $LatestResults['visitor_team_id'] : $LatestResults['home_team_id'];
        $homeClubId2 = $LatestResults['home_team_id'];
        $visitorClubId2 = $LatestResults['visitor_team_id'];
        $date2 = $LatestResults['date'];
        $lieu2 = $LatestResults['stadium_name'];

    } else {
        $_SESSION['message'] = "Vous n'êtes pas autorisé à accéder à cette page.";
        header('Location: ./home.php');
        exit();
    }
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fédération Française de Foot</title>
    <link rel="shortcut icon" type="image/x-icon" href= "../../../Ressources Cubes/favicon (1).ico">

    <link rel="stylesheet" href="AccueilSupporter.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous">
    
</head>
<body id="main">

    <div class="header">
        <div class="icon">
            <a href="http://127.0.0.1:5500/AccueilSupporter.html"><img src="..\..\..\Ressources Cubes\LogoBloc3.png" class="iconfoot"></a>
            <img src="https://cdn.iconscout.com/icon/free/png-512/free-hamburger-menu-462145.png?f=webp&w=256" class="Menu" onclick="openNav()">
                    <div id="mySidenav" class="sidenav">
                        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
                        <a href="#">Les Matchs</a>
                        <a href="#">Les Equipes</a>
                        <a href="ApresMatch.php">Les Statistiques</a>
                        <a href="#">Mon Equipe</a>
                        <a href="#" class="GestionProfil">Gestion du profil</a>
                        <a href="#" class="Connexion">Déconnexion</a>
                    </div>
            <h1>Ma Ligue 1 - Accueil</h1>
        </div>
      </div>


    <Main>
        <div class="content2">
            <img src="/BLOC3-DI23/front/assets/club_logo/Ecran_Ligue_1.png" alt="fond bleu">
            <button class="Button1"><img src="https://pbs.twimg.com/media/FxuhuRuXwAEXROm?format=jpg&name=4096x4096"></button>
            <h2 class="Equipe">Les Equipes</h2>
            <button class="Button2"><img src="https://lepetitlillois.com/wp-content/uploads/2020/04/ligue1-ballon-1536x1024.jpg.webp"></button>
            <h2 class="Matchs">Prochains Matchs</h2>
            <h1 class="Equipes"><?php echo ucwords(getLatestMatchbyClub($coachClubId))?> - <?php echo ucwords(getLatestMatchbyClub($opponentClubId)) ?></h1>
            <div class="logo_container">
                <img src="./assets/club_logo/<?php echo htmlspecialchars($homeClubId); ?>.png" class="Club1">
                <img src="./assets/club_logo/<?php echo htmlspecialchars($visitorClubId); ?>.png" class="Club2">
            </div>
            <h1 class="Date">Date : </h1>
            <span class="date1"><?php echo $date->format('d/m/Y'); ?></span>
            <h1 class="Lieu">Lieu : </h1>
            <span class="lieu1"><?php echo ucwords->(htmlspecialchars($lieu)); ?></span>
            <button class="Button3"><img src="https://i.f1g.fr/media/cms/1194x804/2021/05/18/a8c89bc86ae642e6b43169f35f4dcd2463d8956a06e342b25204508bc6addf37.jpg"></button>
            <h2 class="Resultats">Derniers Résultats</h2>
            <h1 class="Equipes2"><?php echo ucwords($coachClubId2)?> - <?php echo ucwords($opponentClubId2) ?></h1>
            <div class="logo_container2">
                <img src="./assets/club_logo/<?php echo htmlspecialchars($homeClubId2); ?>.png" class="Club3">
                <img src="./assets/club_logo/<?php echo htmlspecialchars($visitorClubId2); ?>.png" class="Club4">
            </div>
            <span class="points"><?php echo $MatchPoint; ?> - <?php echo $MatchPoint; ?></span>
            <h1 class="Date2">Date : </h1>
            <span class="date2"><?php echo $date2->format('d/m/Y'); ?></span>
            <h1 class="Lieu2">Lieu : </h1>
            <span class="lieu2"><?php echo ucwords->(htmlspecialchars($lieu2)); ?></span>
            <button class="Button4"><img src="./assets/club_logo/<?php echo htmlspecialchars($homeClubId); ?>.png"></button>
            <h2 class="MonEquipe">Mon Equipe</h2>
        </div> 
    </Main>

    <footer>
       <div class="content">
        <div class="leftbox">
            <div class="upper">
                <div class="topic">Nos Réseaux Sociaux</div>
            </div>
            <div class="middle">
                <ul>
                    <li><a href="https://www.youtube.com/channel/UCeJlXGyEl7kBgQJKADAHM3A"><i class="fa-brands fa-youtube"></i></a></li>
                    <li><a href="https://x.com/FFF?ref_src=twsrc%5Egoogle%7Ctwcamp%5Eserp%7Ctwgr%5Eauthor"><i class="fa-brands fa-x-twitter"></i></a></li>
                    <li><a href="https://github.com/QuentinBruera/BLOC3-DI23"><i class="fa-brands fa-github fa-lg"></i></a></li>
                </ul>
            </div>
            <div class="low">
                <p>Copyright &#169; 2024 All rights reserved</p>
            </div>
        </div>
        
    
       </div>
    </footer>


    <script>
        function openNav() {
          document.getElementById("mySidenav").style.width = "250px";
          document.getElementById("main").style.marginLeft = "250px";
          document.body.style.backgroundColor = "rgba(0,0,0,0.4)";
        }
        
        function closeNav() {
          document.getElementById("mySidenav").style.width = "0";
          document.getElementById("main").style.marginLeft= "0";
          document.body.style.backgroundColor = "white";
        }
        </script>
</body>
</html>