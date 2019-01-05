<!-- Gestion des adhésions, édition de fichier csv -->


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

?>
  

<!DOCTYPE html>

<html>

<head>

    <meta charset="utf-8" />

    <title>Gestion</title>

    <link rel="stylesheet" href="style.css" />

</head>


<body>

    <?php include("include/entete.php"); ?>
    <?php include("include/laterale.php"); ?>

    <section class="corps">
          
        <?php include("include/enconstruction.php"); ?>

        <!--Création du tableau : -->
    <table class="gestion">
        <tr class ="line">
            <th colspan="2">Nom</th>
            <th colspan="2">Type d'adhésion</th>
            <th>CA</th>
            <th>Bureau</th>
            <th colspan="2">Cotiz ?</th>
        </tr>
        <?php 
        $reponse = $bdd->query('SELECT * FROM users WHERE name != "admin" ORDER BY nom, prenom'); //WHERE name != "admin"');
        while($donnees = $reponse->fetch()){
            $test = ($donnees['name'] == $_SESSION['login'] );
            echo '<tr>';
            echo '<td class="cell_left">' . $donnees['nom'] . '</td>';
            echo '<td class="cell_none">' . $donnees['prenom'] . '</td>';
            switch($donnees['type']){
                case 0:
                    echo '<td class="cell_left">Visiteur</td>';
                    break;
                case 1:
                    echo '<td class="cell_left">P\'tit Dub</td>';
                    break;
                case 2:
                    echo '<td class="cell_left">Gros Dub solo</td>';
                    break;
                case 3:
                    echo '<td class="cell_left">Gros Dub tribu</td>';
                    break;
                case 4:
                    echo '<td class="cell_left">Étudiant-parasite</td>';
                    break;
                case 5:
                    echo '<td class="cell_left">Membre d\'honneur</td>';
                    break;
                default:
                    echo '<td class="cell_left">Superhéros</td>';
                    break;          
            }
            //Si la ligne n'est pas celle du login de session
            if (!$test && $donnees['name'] != 'admin') {
                echo '<td class="cell_none"><form name="formulaire2" method="post" action="php/gestion_edit_type.php">
                    <input name="num" type="hidden" value=' . $donnees['numero'] .'></input>
                    <select name="typ" id="typ'. $donnees['numero'] . '">
                        <option value=0>Non adhérent</option>
                        <option value=1>P\'tit Dub</option>
                        <option value=2>Gros Dub solo</option>
                        <option value=3>Gros Dub tribu</option>
                        <option value=4>Étudiant-parasite</option>
                        <option value=5>Membre d\'honneur</option>
                    </select>';
                echo '<input type="submit" value="Modifier" /></form></td>';}
            else {echo '<td class="cell_none"></td>';}
            switch($donnees['ca']){
                case 0:
                    echo '<td class="cell_left"> </td>';
                    break;
                case 1:
                    echo '<td class="cell_left">membre</td>';
                    break;
                default:
                    echo '<td class="cell_left">superhéros</td>';
                    break;          
            }
            switch($donnees['bureau']){
                case 0:
                    echo '<td class="cell_left"> </td>';
                    break;
                case 1:
                    echo '<td class="cell_left">membre</td>';
                    break;
                default:
                    echo '<td class="cell_left">superhéros</td>';
                    break;          
            }
            switch($donnees['cotiz']){
                case 0:
                    if ($donnees['type'] < 5 && $donnees['type'] > 0){
                        echo '<td class="cell_left">non payée</td>';
                    }
                    else {
                        echo '<td class="cell_left"></td>';
                    }
                    break;
                case 1:
                    if ($donnees['type'] < 5 && $donnees['type'] > 0){
                        echo '<td class="cell_left">payée</td>';
                    }
                    else {
                        echo '<td class="cell_left"></td>';
                    }                    
                    break;
                default:
                    echo '<td class="cell_left">superhéros</td>';
                    break;          
            }
            if (!$test && $donnees['type'] < 5 && $donnees['type'] > 0) {
                echo '<td class="cell_right"><form name="formulaire2" method="post" action="php/gestion_edit_cotiz.php">
                    <input name="num" type="hidden" value=' . $donnees['numero'] .'></input>
                    <select name="cotiz" id="cotiz'. $donnees['numero'] . '">
                        <option value=0>non payée</option>
                        <option value=1>payée</option>
                    </select>';
                echo '<input type="submit" value="Modifier" /></form></td>';
                echo '</tr>';}
            else {echo '<td class="cell_right"></td>';}
        }
        $reponse->closeCursor();
        ?>
    </table>
    </section>

    <?php include("include/pieddepage.php"); ?>

</body>

</html>
