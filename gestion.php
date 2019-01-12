<?php
// Gestion des adhésions, édition de fichier csv 

    session_start ();
    include("doctor/bdd.php");
    include("php/fonctions.php");
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
    <section class="flex_formulaire">
<!--          ? setlocale(LC_ALL,'french'); echo "Dernière modification effectuée le ".date("l j F Y à H:i", getlastmod()); ?> -->
        <table class="formulaire_edition">
        <tr><td class="underlined" colspan=3>Édition de tableau récapitulatif</td></tr>
        <form action= "php/editer_tableau.php" method="post"> 
                <tr class="justify_left"><td colspan=3 class="underlined">Trier par : </td></tr>
                    <tr><td><input type="radio" name="tri" value="nom" id="nom" checked /> <label for="nom">Nom</label></td><td>
                    <input type="radio" name="tri" value="type" id="type" /> <label for="type">Type d'adhésion</label></td><td>
                    <input type="radio" name="tri" value="cotiz" id="cotiz" /> <label for="cotiz">Cotiz payée</label></td></tr>
                <tr class="justify_left"><td colspan=3 class="underlined">Sélectionner uniquement : </td></tr>
                    <tr><td><input type="radio" name="cotiz" value="non_payee" id="non_payee" /> <label for="non_payee">Cotiz non payée</label></td><td>
                    <input type="radio" name="cotiz" value="payee" id="payee" /> <label for="payee">Cotiz payée</label></td><td>
                    <input type="radio" name="cotiz" value="les_deux" id="les_deux" checked /> <label for="les_deux">Les deux</label></td></tr>
                <tr class="justify_left"><td colspan=3 class="underlined">Sélectionner uniquement : </td></tr>
                    <tr><td><input type="radio" name="adh" value="adherents" id="adherents" checked /> <label for="adherents">Les adhérents</label></td><td>
                    <input type="radio" name="adh" value="non_adherents" id="non_adherents" /> <label for="non_adherents">Les non adhérents</label></td><td>
                    <input type="radio" name="adh" value="tous" id="tous" /> <label for="tous">Tous</label></td></tr>
                <tr class="justify_left"><td colspan=3 class="underlined">Faire apparaître :</td></tr>
                    <tr><td><input type="radio" name="select" value="dubus" id="dubus" checked /> <label for="dubus">Tous</label></td><td><input type="radio" name="select" value="selection" id="selection" /> <label for="selection">Sélection :</label></td>
                    <td class="justify_left"><input type="checkbox" name="ptitsdub" id="ptitsdub" /> <label for="ptitsdub">Les p'tits Dub</label></td></tr>
                    <tr><td></td><td></td><td class="justify_left"><input type="checkbox" name="grossolo" id="grossolo" /> <label for="grossolo">Les gros Dub solo</label></td></tr>
                    <tr><td></td><td></td><td class="justify_left"><input type="checkbox" name="grostribu" id="grostribu" /> <label for="grostribu">Les gros Dub tribu</label></td></tr>
                    <tr><td></td><td></td><td class="justify_left"><input type="checkbox" name="etudiants" id="etudiants" /> <label for="etudiants">Les étudiants-parasites</label></td></tr>
                    <tr><td></td><td></td><td class="justify_left"><input type="checkbox" name="honneur" id="honneur" /> <label for="honneur">Les membres d'honneur</label></td></tr>
                    <tr><td></td><td></td><td class="justify_left"><input type="checkbox" name="visiteurs" id="visiteurs" /> <label for="visiteurs">Les non-adhérents</label></td></tr>
                    <tr></tr>
                    <tr></tr>
                    <tr><td colspan="3"><input type="submit" value="Créer"> </td></tr>
                </table>
                </p>        
        </form>
        </table>

        <div class="ensemble_gauche">
        <table class="event_officiel">
        <tr><td class="caption_center" colspan=2>Réservation des Margots</td></tr>
        <form class ="simple button" action="php/reservation.php" method="POST">
            <input type="hidden" name="official" value=1>
            <?php echo '<input type="hidden" name="login" value=' . $_SESSION['login'] . '>'; ?>
            <tr><td colspan=2 class="justify_center"><label for="nom">Nom de l'événement : &nbsp </label> <input type="text" name="nom" id="nom" value="Fête des Margots" required /></td></tr>
            <tr><td colspan=2 class="justify_center"><label for="debut">Date de début :</label> <input type="date" name="debut" id="debut" placeholder="AAAA-MM-JJ" required />
            <label for="fin"> &nbsp &nbsp Date de fin  :</label> <input type="date" name="fin" id="fin" placeholder="AAAA-MM-JJ" required /></td></tr>
            <input type="hidden" name="prive" value=0>
            <input type="hidden" name="package" value="OSEF">
            <input type="hidden" name="ptitdub" value=0>
            <input type="hidden" name="grosdub" value=0>
            <input type="hidden" name="pleintarif" value=0>
            <input type="hidden" name="tarifreduit" value=0>
            <input type="hidden" name="enfants" value=0>
            <tr><td colspan=2 class="justify_center"><input type="submit" value="Déclarer un événement officiel"></td></tr>
        </form>
        </table>

        <?php if ($_SESSION['login'] == 'admin') {
            echo "prout.";
            include("doctor/admin.php");
        } ?>

        <!--Création du tableau : -->
        <table class="gestion">
            <caption class="titre_tableau">Gestion des adhésions :</caption>
                <tr class ="line">
                <th colspan=2>Nom</th>
                <th colspan=2>Type d'adhésion</th>
                <th>CA</th>
                <th>Bureau</th>
                <th colspan=2>Cotiz ?</th>
            </tr>
            <?php 
            $reponse = $bdd->query('SELECT * FROM users ORDER BY nom, prenom'); //WHERE name != "admin"');

            while($donnees = $reponse->fetch()){
                //On n'affiche pas l'admin
                if ($donnees['name'] != "admin") {
                $test = ($donnees['name'] == $_SESSION['login'] );
                echo '<tr>';
                echo '<td class="cell_left">' . $donnees['nom'] . '</td>';
                echo '<td class="cell_none">' . $donnees['prenom'] . '</td>';
                switch($donnees['type']){
                    case 0:
                        echo '<td class="cell_left">Non adhérent</td>';
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
                //Si la ligne n'est pas celle du login de session ni de l'admin le if est là pour sélectionner par défaut le type
                if (!$test && $donnees['name'] != 'admin') {
                    echo '<td class="cell_none"><form name="formulaire_type" method="post" action="php/gestion_edit_type.php">
                        <input type="hidden" name="user" value="' . $_SESSION['login'] . '">
                        <input name="num" type="hidden" value=' . $donnees['numero'] .'></input>
                        <select name="typ" id="typ'. $donnees['numero'] . '">
                            <option value=0';
                            if($donnees['type'] == 0) {echo ' selected="selected"';}
                            echo '>Non adhérent</option>
                            <option value=1';
                            if($donnees['type'] == 1) {echo ' selected="selected"';}
                            echo '>P\'tit Dub</option>
                            <option value=2';
                            if($donnees['type'] == 2) {echo ' selected="selected"';}
                            echo '>Gros Dub solo</option>
                            <option value=3';
                            if($donnees['type'] == 3) {echo ' selected="selected"';}
                            echo '>Gros Dub tribu</option>
                            <option value=4';
                            if($donnees['type'] == 4) {echo ' selected="selected"';}
                            echo '>Étudiant-parasite</option>
                            <option value=5';
                            if($donnees['type'] == 5) {echo ' selected="selected"';}
                            echo '>Membre d\'honneur</option>
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
                    echo '<td class="cell_right"><form name="formulaire_cotiz" method="post" action="php/gestion_edit_cotiz.php">
                        <input type="hidden" name="user" value="' . $_SESSION['login'] . '">
                        <input name="num" type="hidden" value=' . $donnees['numero'] .'></input>
                        <select name="cotiz" id="cotiz'. $donnees['numero'] . '">
                            <option value=0';
                            if($donnees['cotiz'] == 0) {echo ' selected="selected"';}
                            echo '>non payée</option>
                            <option value=1';
                            if($donnees['cotiz'] == 1) {echo ' selected="selected"';}
                            echo '>payée</option>
                        </select>';
                    echo '<input type="submit" value="Modifier" /></form></td>';
                    echo '</tr>';
                }
                else {
                    echo '<td class="cell_right"></td>';
                }
                }
                //calcul de la dernière date de modif et login correspondant.
                if ($donnees['modif'] > $last_date_modif) {
                    $last_date_modif = $donnees['modif'];
                    $last_name_modif = $donnees['name'];
                }

            }
            $reponse->closeCursor();
            ?>
            <?php echo '<tr><td class="cell_none" colspan=8><a class="sign_news">Dernière modification : le ' . convertdate($last_date_modif) . ' par ' . $last_name_modif . '.</a></td></tr>'; ?>
        </table>
    </div>
    </section>
    </section>

    <?php include("include/pieddepage.php"); ?>

</body>

</html>
