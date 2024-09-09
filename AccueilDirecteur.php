<?php
    session_start();
    if($_SESSION['user_role'] == 'admin') {


        function getLatestPreMatchId() {
            $db = connexionDB();
            try {
                $sql = "SELECT id FROM pre_match ORDER BY ID DESC LIMIT 0,1";
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


        function getLatestResultsbyId($MatchResId) {
            $db = connexionDB();
            try {
                $sql = "SELECT goal.id FROM match_result_goal INNER JOIN goal ON match_result_goal.goal_id WHERE match_result_goal.id = :MatchResId";
                $req = $db->prepare($sql);
                $req->bindParam(':MatchResId', $MatchResId);
                $req->execute();
                return $req->fetch(PDO::FETCH_ASSOC);
            } catch (Exception $e) {
                echo 'Erreur : ' . $e->getMessage();
            }
        }



        function getLatestResMatch($preMatchId) {
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


        function getLatestResultsMatch() {
            $db = connexionDB();
            try {
                $sql = "SELECT pre_match.* FROM match_result INNER JOIN pre_match ON match_result.pre_match_id;";
                $req = $db->prepare($sql);
                $req->execute();
                return $req->fetch(PDO::FETCH_ASSOC);
            } catch (Exception $e) {
                echo 'Erreur : ' . $e->getMessage();
            }
        }


        $IdMatch = getLatestPreMatchId();
        $IdMatchRes = getLatestMatchId();
        $DernierMatch = getLatestMatch($IdMatch);
        $MatchRes = getLatestResultsMatch($IdMatchRes);
        
                
                $homeClubId = $DernierMatch['home_team_id'];
                $visitorClubId = $DernierMatch['visitor_team_id'];
                $date = new DateTime($DernierMatch['date']);


                $homeClub2Id = $MatchRes['home_team_id'];
                $visitorClub2Id = $MatchRes['visitor_team_id'];
                $date2 = new DateTime($MatchRes['date']);

                $MatchPoint = getLatestResMatch($IdMatchRes)

    

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
    <link rel="stylesheet" href="AccueilDirecteur.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous">
    
</head>

<body id="main">

    <div class="header">
        <div class="icon">
            <a href="http://127.0.0.1:5500/AccueilDirecteur.html"><img src="..\..\..\Ressources Cubes\LogoBloc3.png" class="iconfoot"></a>
            <img src="https://cdn.iconscout.com/icon/free/png-512/free-hamburger-menu-462145.png?f=webp&w=256" class="Menu" onclick="openNav()">
                    <div id="mySidenav" class="sidenav">
                        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
                        <a href="#">Les Equipes</a>
                        <a href="AvantMatch.php">Prochains Matchs</a>
                        <a href="#">Programmer un match</a>
                        <a href="ApresMatch.php">Derniers Résultats</a>
                        <a href="#" class="GestionProfil">Gestion du profil</a>
                        <a href="#" class="Déconnexion">Déconnexion</a>
                    </div>
            <h1>Ma Ligue 1 - Accueil</h1>
        </div>
      </div>


    <Main>
        <div class="content2">
            <img src="/BLOC3-DI23/front/assets/club_logo/Ecran_Ligue_1.png" alt="fond bleu" class="background">
            <button class="Button1"><img src="https://lepetitlillois.com/wp-content/uploads/2020/04/ligue1-ballon-1536x1024.jpg.webp"></button>
            <h1 class="Equipe"><?php echo ucwords(getClubNameById($homeClubId))?> - <?php echo ucwords(getClubNameById($visitorClubId)) ?></h1>
            <div class="logo_container">
                <img src="./assets/club_logo/<?php echo htmlspecialchars($homeClubId); ?>.png" class="Club1">
                <img src="./assets/club_logo/<?php echo htmlspecialchars($visitorClubId); ?>.png" class="Club2">
            </div>
            <h1 class="Date">Date : </h1>
            <span class="date2"><?php echo $date->format('d/m/Y'); ?></span>
            <h1 class="Lieu">Lieu : </h1>
            <span class="lieu2"><?php echo ucwords->(htmlspecialchars($DernierMatch['stadium_name'])); ?></span>
            <h2 class="ProchainsMatchs">Prochains Matchs</h2>
            <button class="Button2"><img src="https://i.f1g.fr/media/cms/1194x804/2021/05/18/a8c89bc86ae642e6b43169f35f4dcd2463d8956a06e342b25204508bc6addf37.jpg"></button>
            
            <h2 class="Matchs">Derniers Résultats</h2>
            <div class="logo_container2">
                <img src="./assets/club_logo/<?php echo htmlspecialchars($homeClub2Id); ?>.png" class="Club3">
                <span class="points"><?php echo $MatchPoint; ?></span>
                <span class="points2">- <?php echo $MatchPoint; ?></span>
                <img src="./assets/club_logo/<?php echo htmlspecialchars($visitorClub2Id); ?>.png" class="Club4">
            </div>
            <h1 class="Equipe1"><?php echo ucwords(getClubNameById($homeClub2Id))?> - <?php echo ucwords(getClubNameById($visitorClub2Id)) ?></h1>
            <h1 class="Date1">Date : </h1>
            <span class="date3"><?php echo $date2->format('d/m/Y'); ?></span>
            <h1 class="Lieu1">Lieu : </h1>
            <span class="lieu3"><?php echo ucwords->(htmlspecialchars($MatchRes['stadium_name'])); ?></span>
            <button class="Button3"><img src="https://img.freepik.com/vecteurs-premium/calendrier-sportif-ballon-foot-realiste_41737-5.jpg?w=740"></button>
            <h2 class="Resultats">Programmer un match</h2>
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