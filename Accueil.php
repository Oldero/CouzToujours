
    <!-- Page d'accueil -->

<?php
    session_start ();
    try
    {
        // On se connecte à MySQL
        $bdd = new PDO('mysql:host=localhost;dbname=couztoujours;charset=utf8', 'root', 'root');
    }
    catch(Exception $e)
    {
        // En cas d'erreur, on affiche un message et on arrête tout
            die('Erreur : '.$e->getMessage());
    }

    include("php/fonctions.php");
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
<!--        ?php 
            echo 'àéç';
            $date = date("d-m-Y"); 
            $heure = date("H:i");
            echo $date . " à " . $heure;
        ?> -->
        <aside class="formulaire_cote">
            <p class="underlined">Rédaction de news</p>
        <form action="php/rediger_news.php" method="post">
            <?php echo'<input type="hidden" name="name" value="' . $_SESSION['prenom'] . ' ' . $_SESSION['nom'] . '">'; ?>
            <label for="title">Titre de la news : </label>
            <input type="text" name="title" id="title"/><br />
            <label for="msg">Quelles nouvelles ?</label>
            <textarea name="msg" id="msg" required="required"></textarea><br />
            <input type="submit" value="Poster">
        </form>
        </aside>

        <section class="section_news">
            <?php
                $reponse = $bdd->query('SELECT * FROM news ORDER BY date_du_jour DESC LIMIT 0, 10');
                echo '<table class="news">';
                // On affiche chaque entrée une à une
                while ($donnees = $reponse->fetch())
                    {
                        echo "<tr><td class=\"titre_news\"><strong>" . utf8_decode($donnees['titre']) . "</strong></td></tr>";
                        echo "<tr><td class=\"msg_news\">" . utf8_decode($donnees['message']) . "</td></tr>";
                        echo "<tr><td class=\"sign_news\"> Le " . convertdate($donnees['date_du_jour']) . " par " . $donnees['nom'] . " </td></tr>";
                    }
                echo '</table>';
                $reponse->closeCursor(); // Termine le traitement de la requête
            ?>
        </section>
    </section>

    <?php include("include/pieddepage.php"); ?>

</body>

</html>