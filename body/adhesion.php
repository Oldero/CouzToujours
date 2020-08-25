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

    <div class="img_fixed"><img src="../img/styled_margots.jpg"></div>

    <section class="corps">
   	<table class="table_docs">
        	<tr><td>Adhérer à l'association et tarifs en vigueur</td><td><form method="post" action="../docs/bulletin_adhesion.pdf" target="_blank"><input type="submit" value="Télécharger"></form></td></tr>
        </table>

         <iframe src="../docs/bulletin_adhesion.pdf" width="800" height="600" align="middle"></iframe>

    </section>

    <?php include("../include/pieddepage.php"); ?>

</body>

</html>