<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Ticketing Platform</title>
    <link rel="shortcut icon" href="../favicon.ico" type="image/x-icon">
</head>
<body>
    <nav class="sticky top-0 z-50">
        <?php
            include "./navbar.php";
        ?>
    </nav>
    <header>
         <?php
            include "./hero.php";
            
        ?>
    </header>
    <section>
        <?php
            include "./upcomingevent.php";
            include "./past_event.php";
            include "./feature.php";
            include "./logos.php";
        ?>
    </section>

    <footer>
        <?php
            include "./footer.php";
        ?>
    </footer>
    
    
</body>
</html>