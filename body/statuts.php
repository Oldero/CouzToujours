<?php
// Camps Cousins : photos ?, liste, productions

    session_start ();
    include("../php/fonctions.php");
?>
  

<!DOCTYPE html>

<html>

<head>
    <?php include("../include/style.php"); ?>
    <title>Statuts de l'asso</title>
</head>


<body>

    <?php include("../include/entete.php"); ?>
    <?php include("../include/laterale.php"); ?>

    <div class="img_fixed"><img src="../img/styled_margots.jpg"></div>

    <section class="corps">
        <table class="table_docs">
        	<tr><td>Les statuts de l'association</td><td><?php echo ucfirst(convertdate("2017-08-20")); ?></td><td>PDF</td><td><form method="post"><input type="hidden" name="file" value="statuts"><input type="submit" value="Afficher"></form></td><td><form method="post" action="../docs/statuts.pdf" target="_blank"><input type="submit" value="Télécharger"></form></td></tr>
        	<tr><td>Compte-rendu de l'AG constitutive</td><td><?php echo ucfirst(convertdate("2017-07-29")); ?></td><td>PDF</td><td><form method="post"><input type="hidden" name="file" value="CR_AG1"><input type="submit" value="Afficher"></form></td><td><form method="post" action="../docs/CR_AG1.pdf" target="_blank"><input type="submit" value="Télécharger"></form></td></tr>
        	<tr><td>Compte-rendu de l'AG n°2</td><td><?php echo ucfirst(convertdate("2018-07-28")); ?></td><td>PDF</td><td><form method="post"><input type="hidden" name="file" value="CR_AG2"><input type="submit" value="Afficher"></form></td><td><form method="post" action="../docs/CR_AG2.pdf" target="_blank"><input type="submit" value="Télécharger"></form></td></tr>
        	<tr><td>Compte-rendu de l'AG n°3</td><td><?php echo ucfirst(convertdate("2019-07-27")); ?></td><td>PDF</td><td><form method="post"><input type="hidden" name="file" value="CR_AG3"><input type="submit" value="Afficher"></form></td><td><form method="post" action="../docs/CR_AG3.pdf" target="_blank"><input type="submit" value="Télécharger"></form></td></tr>
        </table>
        <?php if(isset($_POST['file'])){ 
        echo '<form method="post"><input type="submit" value="masquer"></form>';
        echo '<iframe src="../docs/' . $_POST['file'] . '.pdf" width="800" height="600" align="middle" class="affiche_docs"></iframe>';}?>
    </section>

    <?php include("../include/pieddepage.php"); ?>

</body>

</html>