<?php
    require_once dirname(__DIR__) . '/back/CRUD/club.php';

    session_start();

    if (isset($_SESSION['message'])) {
        // addslashes() allows to escape special characters
        $message = addslashes($_SESSION['message']);
        echo "<script>alert('$message');</script>";
        // Unset the message after displaying it
        unset($_SESSION['message']);
    }

    $clubsId = getAllClubSId();

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Les équipes</title>
    <link rel="stylesheet" href="./css/clubs.css">
</head>
<body>
    <section id="main_container">
        <h1>CLUBS</h1>
        <div id="card_container">
            <?php
                foreach ($clubsId as $clubId) {
                    ?>
                        <a href="./club.php?club_id=<?php echo $clubId['id']; ?>" id="card_<?php echo $clubId['id']; ?>" class="card">
                            <img src="./assets/club_logo/<?php echo $clubId['id']; ?>.png" alt="logo" class="card_img">
                        </a>
                    <?php
                }
            ?>
        </div>
    </section>
</body>
</html>