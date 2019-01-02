
    <!-- Page d'accueil -->

<?php
    session_start ();
?>


<!DOCTYPE html>

<html>

<head>

    <meta charset="utf-8" />

    <title>Couz'Toujours</title>

    <link rel="stylesheet" href="style.css" />

</head>


<body>

    <?php include("include/entete.php"); ?>
    <?php include("include/laterale.php"); ?>
    

    <section class="corps">
        <?php include("include/enconstruction.php"); ?>
        <article>
            <h2>Todo list : </h2>
            <ol>
                <li>Mise en page</li>
                <li>Généalogie (Papa)</li>
                <li>Camp Cousins (liste et fichiers)</li>
                <li>Insertion accès sécurisé</li>
                <li>Réservation Margots (+ photos, projets...)</li>
            </ol>
        </article>
           
    </section>

    <?php include("include/pieddepage.php"); ?>

</body>

</html>