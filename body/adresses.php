<?php
// Camps Cousins : photos ?, liste, productions

    session_start ();
    include("../php/fonctions.php");
?>
  

<!DOCTYPE html>

<html>

<head>
    <?php include("../include/style.php"); ?>
    <title>Carnet d'adresses</title>
</head>


<body>

    <?php include("../include/entete.php"); ?>
    <?php include("../include/laterale.php"); ?>

    <div class="img_fixed"><img src="../img/dix.jpg" id="img_adresse"></div>

    <section class="corps">
        <table class="table_docs">
        	<tr><td>Annuaire</td><td>PDF</td><td><form method="post"><input type="hidden" name="file" value="annuaire"><input type="submit" value="Afficher"></form></td><td><form method="post" action="../docs/annuaire/Annuaire.pdf" target="_blank"><input type="submit" value="Télécharger"></form></td></tr>
        	<tr><td>Généalogie par familles</td><td>PDF</td><td><form method="post"><input type="hidden" name="file" value="Gene_famille"><input type="submit" value="Afficher"></form></td><td><form method="post" action="../docs/annuaire/Gene_famille.pdf" target="_blank"><input type="submit" value="Télécharger"></form></td></tr>
        	<tr><td>Naissances chronologiques</td></td><td>PDF</td><td><form method="post"><input type="hidden" name="file" value="Naissances_chrono"><input type="submit" value="Afficher"></form></td><td><form method="post" action="../docs/annuaire/Naissances_chrono.pdf" target="_blank"><input type="submit" value="Télécharger"></form></td></tr>
        	<tr><td>Anniversaires</td><td>PDF</td><td><form method="post"><input type="hidden" name="file" value="Anniversaires"><input type="submit" value="Afficher"></form></td><td><form method="post" action="../docs/annuaire/Anniversaires.pdf" target="_blank"><input type="submit" value="Télécharger"></form></td></tr>
            <tr><td colspan=4><a class="sign_news">Version : 26 mars 2020</a></td></tr>
        </table>
        <?php if(isset($_POST['file'])){ 
        echo '<form method="post"><input type="submit" value="masquer"></form>';
        echo '<iframe src="../docs/annuaire/' . $_POST['file'] . '.pdf" width="800" height="600" align="middle" class="affiche_docs"></iframe>';}?>
    </section>

    <?php include("../include/pieddepage.php"); ?>

</body>

</html>