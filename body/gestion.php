<?php
// Gestion des adhésions, édition de fichier csv 

    session_start ();
    include("../doctor/bdd.php");
    include("../php/fonctions.php");

    // résultat du formulaire edit-type
    if (isset($_POST['typ']) && isset ($_POST['num']) && isset($_POST['user'])) {
        //update avec tag.
        $req = $bdd->prepare('UPDATE users SET type = ? WHERE numero = ?');
        $req->execute(array($_POST['typ'], $_POST['num']));
        $req->closeCursor();
        //termine le traitement de la requête
        //changement de date de modif :
        $req = $bdd->prepare('UPDATE users SET modif = ? WHERE name = ?');
        $req->execute(array(date("Y-m-d H:i:s"), $_POST['user']));
        $req->closeCursor();     
        header ('location: ../body/gestion.php'); //on recharge la page gestion
        
    }
     // résultat du formulaire edit-cotiz
    if (isset($_POST['cotiz']) && isset ($_POST['num']) && isset($_POST['user'])) {
        //update avec tag.
        $req = $bdd->prepare('UPDATE users SET cotiz = ? WHERE numero = ?');
        $req->execute(array($_POST['cotiz'], $_POST['num']));
        $req->closeCursor();
        //termine le traitement de la requête
        //changement de date de modif :
        $req = $bdd->prepare('UPDATE users SET modif = ? WHERE name = ?');
        $req->execute(array(date("Y-m-d H:i:s"), $_POST['user']));
        $req->closeCursor(); 
        header ('location: ../body/gestion.php'); //on recharge la page gestion
        
    }
    //réponse du formulaire suppression de tribu
    if (isset($_POST['param'])) {
        //delete avec tag numero.
        $req = $bdd->prepare('DELETE FROM tribus WHERE numero = ?');
        $req->execute(array($_POST['param']));
        $req->closeCursor();
        //termine le traitement de la requête
        header ('location: ../body/gestion.php'); //on recharge la page gestion
    }
    //réponse du formulaire de création de tribu
    if (isset($_POST['nom_famille']) && isset($_POST['adh1']) && isset($_POST['adh2']) && isset($_POST['etu'])) {
        //generate avec tag numero.
        $req = $bdd->prepare('INSERT INTO tribus(nom,adherent1,adherent2,etudiant) VALUES(:nom,:adherent1,:adherent2,:etudiant)');
        $req->execute(array(
            'nom' => $_POST['nom_famille'],
            'adherent1' => $_POST['adh1'],
            'adherent2' => $_POST['adh2'],
            'etudiant' => $_POST['etu'],
            ));
        $req->closeCursor();
        //termine le traitement de la requête
        header ('location: ../body/gestion.php'); //on recharge la page gestion
    }
    //date temoin pour dernière modification
    $last_date_modif = "2018-01-01";
?>
  

<!DOCTYPE html>

<html>

<head>

    <?php include("../include/style.php"); ?>
    <title>Gestion</title>
</head>


<body>
    
    <?php include("../include/entete.php"); ?>
    <?php include("../include/laterale.php"); ?>

    <section class="corps">
    <section class="flex_formulaire">
<!--          ? setlocale(LC_ALL,'french'); echo "Dernière modification effectuée le ".date("l j F Y à H:i", getlastmod()); ?> -->
        <table class="formulaire_edition">
        <tr><td class="underlined" colspan=3>Édition de tableau récapitulatif</td></tr>
        <form action= "../php/editer_tableau.php" method="post"> 
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
        <tr><td class="caption_center" colspan=2><a>Réservation des Margots</a></td></tr>
        <form class ="simple_button" action="../php/reservation.php" method="post">
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
             include("../doctor/admin.php");
        } ?>

        <!--Création du tableau : -->
        <table class="gestion">
            <tl><td class="unique_case" colspan=8>Gestion des adhésions :</td></tl>
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
                $test = FALSE;//($donnees['name'] == $_SESSION['login'] );
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
                    echo '<td class="cell_none"><form name="formulaire_type" method="post">
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
                    echo '<td class="cell_right"><form name="formulaire_cotiz" method="post">
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
                    echo '<td class="cell_right"></td></tr>';
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
            <?php echo '<tr><td class="unique_case" colspan=8><a class="sign_news">Dernière modification : le ' . convertdate($last_date_modif) . ' par ' . $last_name_modif . '.</a></td></tr>'; ?>
        </table>
<!-- Tableau des tribus -->
        <table class="gestion">
            <tl><td class="unique_case" colspan=9>Tribus :</td></tl>
            <tr class ="line">
                <th>Nom</th>
                <th colspan=2>Adhérent 1</th>
                <th colspan=2>Adhérent 2</th>
                <th colspan=2>Étudiant à charge</th>
                <th>Action</th>
            </tr>
            <?php 
            $tribus = $bdd->query('SELECT * FROM tribus ORDER BY nom'); //WHERE name != "admin"');

            while($donnees = $tribus->fetch()){
                echo '<tr>';
                echo '<td class="cell_left">' . $donnees['nom'] . '</td>';
                
                //Récupération des noms prénoms des adhérents depuis la table
                $requ = $bdd->prepare('SELECT nom,prenom FROM users WHERE name = ?');
                $requ->execute(array($donnees['adherent1']));
                $nom = $requ->fetch();
                echo '<td class="cell_left">' . $nom['nom'] . '</td>';
                echo '<td class="cell_right">' . $nom['prenom'] . '</td>';
                $requ->closeCursor();
                
                $requ = $bdd->prepare('SELECT nom,prenom FROM users WHERE name = ?');
                $requ->execute(array($donnees['adherent2']));
                $nom = $requ->fetch();
                echo '<td class="cell_left">' . $nom['nom'] . '</td>';
                echo '<td class="cell_right">' . $nom['prenom'] . '</td>';
                $requ->closeCursor();

                if($donnees['etudiant'] != "Aucun"){
                $requ = $bdd->prepare('SELECT nom,prenom FROM users WHERE name = ?');
                $requ->execute(array($donnees['etudiant']));
                $nom = $requ->fetch();
                echo '<td class="cell_left">' . $nom['nom'] . '</td>';
                echo '<td class="cell_right">' . $nom['prenom'] . '</td>';
                $requ->closeCursor();
                }
                else{
                echo '<td class="cell_left" colspan=2></td>';
                }

                echo '<td class="unique_case"><form name="suppr" method="post" onsubmit="return confirm(\'Es-tu sûr de vouloir supprimer ce truc ?\');">';
                echo'<input type="hidden" name="param" value=' . $donnees['numero'] .'>
                    <input type="submit" value="Supprimer">
                    </form></td>';

                echo'</tr>';
               }
            $tribus->closeCursor();

            echo '<tr><form method="post">';
            echo '<td class="cell_left"><input type="text" name="nom_famille" required="required"></td>';
            //case adhérent 1 avec sélecteur
            echo '<td class="cell_left" colspan=2><select name="adh1" id="adh1">';
            $reponse1 = $bdd->query('SELECT name,nom,prenom FROM users WHERE type = 3');
            while ($dubtribu = $reponse1->fetch()) {
                echo '<option value="' . $dubtribu['name'] .'"">' . $dubtribu['nom'] . ' ' . $dubtribu['prenom'] . '</option>';
            }
            $reponse1->closeCursor();
            echo '</select></td>';
            //case adhérent 2 avec sélecteur
            echo '<td class="cell_left" colspan=2><select name="adh2" id="adh2">';
            $reponse2 = $bdd->query('SELECT name,nom,prenom FROM users WHERE type = 3');
            while ($dubtribu = $reponse2->fetch()) {
                echo '<option value="' . $dubtribu['name'] .'"">' . $dubtribu['nom'] . ' ' . $dubtribu['prenom'] . '</option>';
            }
            $reponse2->closeCursor();
            //case etudiant avec sélecteur
            echo '<td class="cell_left" colspan=2><select name="etu" id="etu">';
            $reponse3 = $bdd->query('SELECT name,nom,prenom FROM users WHERE type = 4');
            echo '<option value="Aucun">Aucun</option>';
            while ($dubtribu = $reponse3->fetch()) {
                echo '<option value="' . $dubtribu['name'] .'"">' . $dubtribu['nom'] . ' ' . $dubtribu['prenom'] . '</option>';
            }
            $reponse3->closeCursor();

            echo '</select></td>';
            echo '<td class="unique_case"><input type="submit" value="Créer tribu"></td>';
            echo '</form></tr>';
            ?>
         </table>
        </div>
    </section>
    </section>

    <?php include("../include/pieddepage.php"); ?>

</body>

</html>
