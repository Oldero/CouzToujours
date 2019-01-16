<?php
//Adhérer à l'association, faire un don

    session_start ();
?>
  

<!DOCTYPE html>

<html>

<head>
    <?php include("../include/style.php"); ?>
    <title>Adhésion et dons</title>
</head>


<body>

    <?php include("../include/entete.php"); ?>
    <?php include("../include/laterale.php"); ?>

    <section class="corps">
        <iframe src="../docs/bulletin_adhesion.pdf" width="800" height="600" align="middle"></iframe>
    </section>

    <?php include("../include/pieddepage.php"); ?>

</body>

</html>