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
    <title>Livre d'or</title>
</head>


<body>

    <?php include("../include/entete.php"); ?>
    <?php include("../include/laterale.php"); ?>
    

    <section class="corps">
    <section class="flex_formulaire">
        <table class="formulaire_idees">
            <tr><td></td><td class="underlined">Livre d'or / Boîte à idées</td><td></td></tr>
        <form action="../php/rediger_idee.php" method="post">
            <?php echo'<input type="hidden" name="name" value="' . $_SESSION['prenom'] . ' ' . $_SESSION['nom'] . '">'; ?>
            <tr><td colspan=3><label for="msg">Une idée à implémenter ? Un message à faire passer ? <br />(N'oublie pas de signer)</label></td></tr>
            <tr><td colspan=3><textarea name="msg" id="msg" required="required"></textarea></td></tr>
            <tr><td>Direction : </td><td><input type="radio" name="direction" value="idee" id="idee"/> <label for="idee">Boîte à idées</label></td><td><input type="radio" name="direction" value="livre" id="livre" checked /> <label for="livre">Livre d'or</label></td></tr>
            <tr><td></td><td class="justify_center"><input type="submit" value="Poster"></td><td></td></tr>
        </form>
        </table>

        <div class="resume_resa">
            <?php
                $reponse = $bdd->query('SELECT * FROM livredor WHERE type = 1');
                echo '<table class="table_livre">';
                // On affiche chaque entrée une à une
                echo "<tr><th class=\"cell_none\">Livre d'or</th></tr>";
                while ($donnees = $reponse->fetch())
                    {  
                        echo "<tr><td class=\"cell_none\">" . $donnees['message'] . "</td></tr>";
                    }
                echo '</table>';
                $reponse->closeCursor(); // Termine le traitement de la requête
            ?>
        </div>
        <div class="idees">
            <?php
                $reponse = $bdd->query('SELECT * FROM livredor WHERE type = 2 ORDER BY numero DESC');
                echo '<table class="gestion">';
                // On affiche chaque entrée une à une
                echo "<tr><th class=\"\">Boîte à idées</th><th class=\"\">Réponse de l'admin</th></tr>";
                while ($donnees = $reponse->fetch())
                    {  
                        echo "<tr><td class=\"\">" . $donnees['message'] . "</td><td class=\"\">" . $donnees['reponse'] . "</td></tr>";
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