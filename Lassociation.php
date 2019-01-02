<!-- Asso Couz'Toujours. Le bureau, le CA, les AG -->


<?php
    session_start ();
?>
  

<!DOCTYPE html>

<html>

<head>

    <meta charset="utf-8" />

    <title>L'association</title>

    <link rel="stylesheet" href="style.css" />

</head>


<body>

    <?php include("include/entete.php"); ?>
    <?php include("include/laterale.php"); ?>

    <section class="corps">

        <article>
        <article>
         Le bureau <br />
         Le CA <br />
         Les statuts <br />
         Les CR d'AG <br />
        ... <br />
        </article>
           
        <?php include("include/enconstruction.php"); ?>
    </section>

    <?php include("include/pieddepage.php"); ?>

</body>

</html>