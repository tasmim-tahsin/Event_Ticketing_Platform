<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Ticketing Platform</title>
</head>
<body>
    <nav>
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