<?php
    //Page d'accueil 

    session_start ();
    include("../doctor/bdd.php");
    include("../php/fonctions.php");
?>


<!DOCTYPE html>

<html>

<head>
    <?php include("../include/style.php"); ?>
    <title>Couz'Toujours</title>
</head>


<body>

    <?php include("../include/entete.php"); ?>
    <?php include("../include/laterale.php"); ?>
    

    <section class="corps">
    <section class="flex_formulaire">
        <table class="formulaire_cote">
            <tr><td class="underlined" colspan=2>Rédaction de news</td></tr>
        <form action="../php/rediger_news.php" method="post">
            <?php echo'<input type="hidden" name="name" value="' . $_SESSION['prenom'] . ' ' . $_SESSION['nom'] . '">'; ?>
            <tr><td><label for="title">Titre de la news : </label>
            </td><td><input type="text" name="title" id="title"/></td></tr>
            <tr><td colspan=2><label for="msg">Quelles nouvelles ?</label></td></tr>
            <tr><td colspan=2><textarea name="msg" id="msg" required="required"></textarea></td></tr>
            <tr><td colspan=2><input type="submit" value="Poster"></td></tr>
        </form>
        </table>

        <div class="section_news">
            <?php
                $reponse = $bdd->query('SELECT * FROM news ORDER BY date_du_jour DESC LIMIT 0, 10');
                echo '<table class="news">';
                // On affiche chaque entrée une à une
                while ($donnees = $reponse->fetch())
                    {
                        echo "<tr><td class=\"titre_news\"><strong>" . $donnees['titre'] . "</strong></td></tr>";
                        echo "<tr><td class=\"msg_news\">" . $donnees['message'] . "</td></tr>";
                        echo "<tr><td class=\"sign_news\"> Le " . convertdate($donnees['date_du_jour']) . " par " . $donnees['nom'] . " </td></tr>";
                    }
                echo '</table>';
                $reponse->closeCursor(); // Termine le traitement de la requête
            ?>
        </div>
    </section>
    </section>

    <?php include("../include/pieddepage.php"); ?>

</body>

</html>