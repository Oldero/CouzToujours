
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

        <section>
            <?php
                $reponse = $bdd->query('SELECT * FROM news ORDER BY date DESC LIMIT 0, 10');

                // On affiche chaque entrée une à une
                while ($donnees = $reponse->fetch())
                    {
                        echo "Le " . convertdate($donnees['date']) . " par " . $donnees['user'] . " : <br />";
                        echo $donnees['message'] . "<br />";
                        echo "~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ <br />";
                    }

                $reponse->closeCursor(); // Termine le traitement de la requête
            ?>
        </section>
    </section>

    <?php include("include/pieddepage.php"); ?>

</body>

</html>