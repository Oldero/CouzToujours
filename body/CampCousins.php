<?php
// Camps Cousins : photos ?, liste, productions

    session_start ();
?>
  

<!DOCTYPE html>

<html>

<head>
    <?php include("../include/style.php"); ?>
    <title>Camps cousins</title>
</head>


<body>

    <?php include("../include/entete.php"); ?>
    <?php include("../include/laterale.php"); ?>

    <section class="corps">
    <section class="page_deuxcolonnes">
        <section class="colonne_droite">
            <div class="img_small"><img src="../img/Campcouz/train.jpg"></div>
            <div class="img_small"><img src="../img/Campcouz/troupe1.jpg"></div>
        </section>
        <section class="colonne_gauche">
            <div class="img_small"><img src="../img/Campcouz/troupe2.jpg"></div>
            <div class="img_small"><img src="../img/Campcouz/troupe3.jpg"></div>
        </section>
    </section>

    <?php include("../include/pieddepage.php"); ?>

</body>

</html>