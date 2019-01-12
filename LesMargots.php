<?php
// Les Margots : histoire, réservation, photos, projets...

    session_start ();
?>
  

<!DOCTYPE html>

<html>

<head>

    <meta charset="utf-8" />

    <title>Les Margots</title>

    <link rel="stylesheet" href="style.css" />

</head>


<body>

    <?php include("include/entete.php"); ?>
    <?php include("include/laterale.php"); ?>

    <section class="corps">
        <?php include("include/enconstruction.php"); ?>
    </section>

    <?php include("include/pieddepage.php"); ?>

</body>

</html>