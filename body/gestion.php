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
        header ('location: ../body/gestion.php?page=2'); //on recharge la page gestion
        
    }
    // résultat du formulaire edit-weoff
    if (isset($_POST['weoff']) && isset ($_POST['num']) && isset($_POST['user'])) {
        //update avec tag.
        if ($_POST['tribu'] != NULL) {
            $req = $bdd->prepare('UPDATE users SET we_offert = ? WHERE tribu = ?');
            $req->execute(array($_POST['weoff'], $_POST['tribu']));
            $req->closeCursor();
        }
        else {
            $req = $bdd->prepare('UPDATE users SET we_offert = ? WHERE numero = ?');
            $req->execute(array($_POST['weoff'], $_POST['num']));
            $req->closeCursor();
        }
        //termine le traitement de la requête
        //changement de date de modif :
        $req = $bdd->prepare('UPDATE users SET modif = ? WHERE name = ?');
        $req->execute(array(date("Y-m-d H:i:s"), $_POST['user']));
        $req->closeCursor(); 
        header ('location: ../body/gestion.php?page=2'); //on recharge la page gestion
    }
    // résultat du formulaire edit-cotiz
    if (isset($_POST['cotiz']) && isset ($_POST['num']) && isset($_POST['user'])) {
        //update avec tag.
        if ($_POST['tribu'] != NULL) {
            $req = $bdd->prepare('UPDATE users SET cotiz = ? WHERE tribu = ?');
            $req->execute(array($_POST['cotiz'], $_POST['tribu']));
            $req->closeCursor();
        }
        else {
            $req = $bdd->prepare('UPDATE users SET cotiz = ? WHERE numero = ?');
            $req->execute(array($_POST['cotiz'], $_POST['num']));
            $req->closeCursor();
        }
        //termine le traitement de la requête
        //changement de date de modif :
        $req = $bdd->prepare('UPDATE users SET modif = ? WHERE name = ?');
        $req->execute(array(date("Y-m-d H:i:s"), $_POST['user']));
        $req->closeCursor(); 
        header ('location: ../body/gestion.php?page=2'); //on recharge la page gestion
    }
    // résultat du formulaire reglement de reservation
    if (isset($_POST['reglement_resa']) && isset ($_POST['num_resa'])) {
        //update avec tag.
            $req = $bdd->prepare('UPDATE reservation SET paye = ? WHERE numero = ?');
            $req->execute(array($_POST['reglement_resa'], $_POST['num_resa']));
            $req->closeCursor();
        //termine le traitement de la requête
        header ('location: ../body/gestion.php?page=3'); //on recharge la page gestion
    }    
    //réponse du formulaire suppression de tribu
    if (isset($_POST['a_suppr'])) {
        //delete avec tag tribu.
        $req = $bdd->prepare('UPDATE users SET tribu = NULL WHERE tribu = ?');
        $req->execute(array($_POST['a_suppr']));
        $req->closeCursor();
        //termine le traitement de la requête
        header ('location: ../body/gestion.php?page=4'); //on recharge la page gestion
    }
    //réponse du formulaire de création de tribu
    if (isset($_POST['nom_famille']) && isset($_POST['adh'])) {
        //update adherent pour chaque adhérent.
        $array_adh = $_POST['adh'];
        foreach ($array_adh as $value) {
            $req = $bdd->prepare('UPDATE users SET tribu = :tribu,we_offert = 1 WHERE name = :name');
            $req->execute(array(
                'tribu' => $_POST['nom_famille'],
                'name' => $value,
                ));
        $req->closeCursor();}
        //update etudiant pour chaque étudiant du select multiple
        if (isset($_POST['etu'])){
            $array_etu = $_POST['etu'];
            foreach ($array_etu as $value) {
                $req = $bdd->prepare('UPDATE users SET tribu = :tribu,we_offert = 1 WHERE name = :name');
                $req->execute(array(
                    'tribu' => $_POST['nom_famille'],
                    'name' => $value,
                    ));
            $req->closeCursor();
            //termine le traitement de la requête
        }}
        if (isset($_POST['enf'])){
            $array_enf = $_POST['enf'];
            foreach ($array_enf as $value) {
                $req = $bdd->prepare('UPDATE users SET tribu = :tribu,we_offert = 1 WHERE name = :name');
                $req->execute(array(
                    'tribu' => $_POST['nom_famille'],
                    'name' => $value,
                    ));
            $req->closeCursor();
            //termine le traitement de la requête
        }}
        
        header ('location: ../body/gestion.php?page=4'); //on recharge la page gestion
    }
    //date temoin pour dernière modification
    $last_date_modif = "2018-01-01";
    //page par défaut
    if (isset($_GET['page'])) {
        $page = $_GET['page'];
    }
    else {$page = 1;}
    if ($_SESSION['ca'] != 1 && $_SESSION['bureau'] != 1 && $_SESSION['admin'] != 1) {
        header ('location: ../body/Accueil.php');
    }
    //if septembre passé, nouvelle année scolaire. Sinon, c'est l'année en cours.
    //marqueur aujourd'hui : séparer année en cours des autres.
    //compare le mois pour savoir si l'année scolaire a commencé.
    if (date("m")>= 9) {$cur_year = date("Y");} else {$cur_year = date("Y")-1;}
    //Réponse de la sélection de la tranche année
    if (isset($_POST['tranche_annee']) && ($_POST['tranche_annee'] != "0000")){
        //du 1er sept de l'année en cours au 31 aout de l'année qui suit
        $tranche = $_POST['tranche_annee'] . "-09-01";
        $tranche_fin = $_POST['tranche_annee'] + 1 . "-08-31";
        $aujourdhui = 0;
    }
    else {
        //Si pas de choix ou choix = année en cours.
    	$tranche = $cur_year . "-09-01";
        $aujourdhui = 1;
    }
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
    <?php
    //menu en onglets
    echo '<div class="menu_gestion">
        <ul id="onglets">';
                echo'<li';
                if ($page==1){echo' class="active"';}
                echo '><a href="gestion.php?page=1">Gestion</a></li>
                <li';
                if ($page==2){echo' class="active"';}
                echo '><a href="gestion.php?page=2">Cotiz\'</a></li>
                <li';
                if ($page==3){echo' class="active"';}
                echo '><a href="gestion.php?page=3">Résas</a></li>
                <li';
                if ($page==4){echo' class="active"';}
                echo '><a href="gestion.php?page=4">Tribus</a></li>';
                if($_SESSION['admin'] == 1) {
                    echo '<li';
                    if ($page==5){echo' class="active"';}
                    echo '><a href="gestion.php?page=5" class="red">new user/pwd</a></li>';
                    echo '<li';
                    if ($page==6){echo' class="active"';}
                    echo '><a href="gestion.php?page=6" class="red">BDD users</a></li>';
                    echo '<li';
                    if ($page==7){echo' class="active"';}
                    echo '><a href="gestion.php?page=7" class="red">BDD resas</a></li>';
                    echo '<li';
                    if ($page==8){echo' class="active"';}
                    echo '><a href="gestion.php?page=8" class="red">BDD anniv</a></li>';
                }
        echo '</ul>
    </div>';
    switch($page) {
        case 1:?>
        <section class="ensemble_gauche">
        <p>Page de gestion de l'association Couz'Toujours. Pour commencer, clique sur un onglet ci-dessus.</p>
        <p>Attention ! Il faut s'assurer que les tribus sont bien constituées avant de modifier les infos des adhérents concernés !</p>
        
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
        </section>
        <?php break;
        case 5: ?>
            <?php if ($_SESSION['admin'] == 1) {
                 include("../doctor/admin.php");
            }
        break;
        case 6:
            if ($_SESSION['admin'] == 1) {
                 include("../doctor/superadmin_bdd1.php");
            }
        break;
        case 7:
            if ($_SESSION['admin'] == 1) {
                 include("../doctor/superadmin_bdd2.php");
            }
        break;
        case 8:
            if ($_SESSION['admin'] == 1) {
                 include("../doctor/superadmin_bdd3.php");
            }
        break;
        case 2: ?>
            <section class="page_deuxcolonnes">
            <section class="colonne_droite">
    <!--          ? setlocale(LC_ALL,'french'); echo "Dernière modification effectuée le ".date("l j F Y à H:i", getlastmod()); ?> -->
            <table class="formulaire_edition">
            <tr><td class="underlined" colspan=3>Édition de tableau récapitulatif</td></tr>
            <form action= "../php/editer_tableau.php" method="post"> 
                <tr class="justify_left"><td colspan=3 class="underlined">Trier par : </td></tr>
                    <tr><td><input type="radio" name="tri" value="nom" id="nom" checked /><label for="nom">Nom</label></td><td>
                    <input type="radio" name="tri" value="type" id="type" /><label for="type">Type d'adhésion</label></td><td>
                    <input type="radio" name="tri" value="cotiz" id="cotiz" /><label for="cotiz">Cotiz payée</label></td></tr>
                <tr class="justify_left"><td colspan=3 class="underlined">Sélectionner uniquement : </td></tr>
                    <tr><td><input type="radio" name="cotiz" value="non_payee" id="non_payee" /><label for="non_payee">Cotiz non payée</label></td><td>
                    <input type="radio" name="cotiz" value="payee" id="payee" /><label for="payee">Cotiz payée</label></td><td>
                    <input type="radio" name="cotiz" value="les_deux" id="les_deux" checked /><label for="les_deux">Les deux</label></td></tr>
                <tr class="justify_left"><td colspan=3 class="underlined">Sélectionner uniquement : </td></tr>
                    <tr><td><input type="radio" name="adh" value="adherents" id="adherents" checked /> <label for="adherents">Les adhérents</label></td><td>
                    <input type="radio" name="adh" value="non_adherents" id="non_adherents" /><label for="non_adherents">Les non adhérents</label></td><td>
                    <input type="radio" name="adh" value="tous" id="tous" /><label for="tous">Tous</label></td></tr>
                <tr class="justify_left"><td colspan=3 class="underlined">Faire apparaître :</td></tr>
                    <tr><td><input type="radio" name="select" value="dubus" id="dubus" checked /> <label for="dubus">Tous</label></td><td><input type="radio" name="select" value="selection" id="selection" /> <label for="selection">Sélection :</label></td>
                    <td class="justify_left"><input type="checkbox" name="ptitsdub" id="ptitsdub" /> <label for="ptitsdub">Les p'tits Dub</label></td></tr>
                    <tr><td></td><td></td><td class="justify_left"><input type="checkbox" name="grossolo" id="grossolo" /><label for="grossolo">Les gros Dub solo</label></td></tr>
                    <tr><td></td><td></td><td class="justify_left"><input type="checkbox" name="grostribu" id="grostribu" /><label for="grostribu">Les gros Dub tribu</label></td></tr>
                    <tr><td></td><td></td><td class="justify_left"><input type="checkbox" name="etudiants" id="etudiants" /><label for="etudiants">Les étudiants/enfants-parasites</label></td></tr>
                    <tr><td></td><td></td><td class="justify_left"><input type="checkbox" name="honneur" id="honneur" /><label for="honneur">Les membres bienfaiteurs</label></td></tr>
                    <tr><td></td><td></td><td class="justify_left"><input type="checkbox" name="visiteurs" id="visiteurs" /><label for="visiteurs">Les non-adhérents</label></td></tr>
                    <tr></tr>
                    <tr></tr>
                    <tr><td colspan=3><input type="submit" value="Créer"> </td></tr>  
            </form>
            </table>
            </section>
            <section class="ensemble_gauche">
            <!--Création du tableau de gestion des adhérents: -->
            <table class="gestion">
                <tl><td class="cell_left" colspan=5>Gestion des adhésions :</td>
                    <?php $req_count=$bdd->query('SELECT COUNT(*) FROM users');
                    $total = $req_count->fetch();
                    $req_count->closeCursor();
                    $req_count=$bdd->query('SELECT COUNT(*) FROM users WHERE numero != 1 AND type > 0');
                    $total_adh = $req_count->fetch();
                    $req_count->closeCursor();
                    echo '<td class="cell_right" colspan = 3>';
                    echo $total_adh[0] . ' adhérents sur ' . $total[0] . ' potentiels';
                    echo '</td>';
                     ?>
                </tl>
                <tr class ="line">
                    <th colspan=2>Nom</th>
                    <th colspan=1>Type d'adhésion</th>
                    <th>Tribu</th>
                    <th colspan=1>WE offert</th>
                    <th>CA</th>
                    <th>Bureau</th>
                    <th colspan=1>Cotiz ?</th>
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
                    //Si la ligne n'est pas celle du login de session ni de l'admin le if est là pour sélectionner par défaut le type
                    if (!$test && $donnees['name'] != 'admin') {
                        echo '<td class="unique_case"><form name="formulaire_type" method="post">
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
                                echo '>Membre bienfaiteur</option>
                                <option value=6';
                                if($donnees['type'] == 6) {echo ' selected="selected"';}
                                echo '>Enfant</option>
                            </select>';
                        echo '<input type="submit" value="Modifier" /></form></td>';}
                    else {echo '<td class="cell_none"></td>';}
                    if ($donnees['type'] == 3 || $donnees['type'] == 4 || $donnees['type'] == 6 ) {
                        echo '<td class="cell_left">' . $donnees['tribu'] . '</td>';
                    }
                    else {
                        echo '<td class="cell_left"></td>';
                    }
                    if ($donnees['type'] >= 2 && $donnees['type'] <= 4) {
                        //echo '<td class="cell_left">' . $donnees['we_offert'] . '</td>';
                        echo '<td class="unique_case"><form name="formulaire_weoff" method="post">
                            <input type="hidden" name="user" value="' . $_SESSION['login'] . '">
                            <input name="num" type="hidden" value=' . $donnees['numero'] .'>';
                        echo '<input name="tribu" type="hidden" value=' . $donnees['tribu'] . '>';
                        echo '<select name="weoff" id="weoff'. $donnees['numero'] . '">
                                <option value=0';
                                if($donnees['we_offert'] == 0) {echo ' selected="selected"';}
                                echo '>0</option>
                                <option value=1';
                                if($donnees['we_offert'] == 1) {echo ' selected="selected"';}
                                echo '>1</option>
                            </select>';
                        echo '<input type="submit" value="Modifier" /></form></td>';
                    }
                    else {echo '<td class="unique_case"></td>';}
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
                    if (!$test && $donnees['type'] < 5 && $donnees['type'] > 0) {
                        echo '<td class="unique_case"><form name="formulaire_cotiz" method="post">
                            <input type="hidden" name="user" value="' . $_SESSION['login'] . '">
                            <input name="num" type="hidden" value=' . $donnees['numero'] .'>';
                        echo '<input name="tribu" type="hidden" value=' . $donnees['tribu'] . '>';
                        echo '<select name="cotiz" id="cotiz'. $donnees['numero'] . '">
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
                        echo '<td class="unique_case"></td></tr>';
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
            </section>
            </section>
        <?php break;
        case 3: ?>
        <section class="page_deuxcolonnes">
            <section class="ensemble_gauche">
                <?php //sélecteur d'année - année en cours est galère.
                echo '<form method="post"><select name="tranche_annee">';
                echo '<option value="0000">Année en cours</option>';
                for ($i=$cur_year-1; $i >= 2017 ; $i--) { 
                    echo '<option value="' . $i . '">Année ' . $i . ' - ' . ($i+1) . '</option>';
                }
                echo '</select>
                <input type="submit" value="Afficher"></form>'; ?>

            <?php if ($aujourdhui) { ?>
            <!--Création du tableau de réservations à venir : -->
            <table class="gestion">
                <tr><td class="unique_case" colspan=9>Réservations à venir :</td></tr>
                <tr class ="line">
                    <th>Adhérent</th>
                    <th>Nom de réservation</th>
                    <th>Du</th>
                    <th>Au</th>
                    <th>nb d'adh</th>
                    <th>nb de visi</th>
                    <th>prix</th>
                    <th colspan=2>réglé ?</th>
                </tr>
                <?php
                    $sql_req = "SELECT * FROM reservation WHERE fin >= '" . date("Y-m-d") . "' ORDER BY fin";
                    $reque = $bdd->query($sql_req);
                    while ($resass = $reque->fetch()){
                        echo '<tr><td class="cell_left">';
                        echo $resass['username'];
                        echo '</td><td class="cell_left">';
                        echo $resass['nom'];
                        echo '</td><td class="cell_left">';
                        echo short_convertdate($resass['debut']);
                        echo '</td><td class="cell_left">';
                        echo short_convertdate($resass['fin']);
                        echo '</td><td class="cell_left">';
                        echo $resass['nbptitdub'] + $resass['nbgrosdub'];
                        echo '</td><td class="cell_left">';
                        echo $resass['nbvis_pt'] + $resass['nbvis_tr'] + $resass['nbvis_enf'];
                        echo '</td><td class="cell_left">';
                        if ($resass['we_gratuit'] == 1) {echo ' (WE offert)';}
                        elseif ($resass['officiel'] == 1) {echo ' (Séjour officiel)';}
                        else {echo $resass['prix'] . " €";
                        if ($resass['prive'] == 1) {echo ' (privatisé)';}}
                        echo '</td><td class="cell_left">';
                        switch ($resass['paye']) {
                            case 0:
                                echo 'non';
                                break;
                            case 1:
                                echo 'oui';
                                break;
                            default:
                                break;
                        }
                        echo '</td><td class="cell_right">';
                        echo '<form name="resa_reglement" method="post">
                            <input name="num_resa" type="hidden" value=' . $resass['numero'] .'>';
                        echo '<select name="reglement_resa" id="reglement'. $resass['numero'] . '">
                                <option value=0';
                                if($resass['paye'] == 0) {echo ' selected="selected"';}
                                echo '>non réglé</option>
                                <option value=1';
                                if($resass['paye'] == 1) {echo ' selected="selected"';}
                                echo '>réglé</option>
                            </select>';
                        echo '<input type="submit" value="Modifier" /></form>';
                        echo '</td></tr>';
                    }
                    $reque->closeCursor();
                ?>
            </table>
            <!--Création du tableau de réservations passées: -->
            <?php
            	$sql_req = "SELECT * FROM reservation WHERE fin BETWEEN '" . $tranche . "' AND '" . date("Y-m-d") . "' ORDER BY fin DESC";
            	echo '<table class="gestion">
                <tr><td class="unique_case" colspan=9>Réservations passées depuis le ' . convertdate($tranche) . ' :</td></tr>';
            }
            else { 
            	$sql_req = "SELECT * FROM reservation WHERE fin BETWEEN '" . $tranche . "' AND '" . $tranche_fin . "' ORDER BY fin DESC";
        	    echo '<table class="gestion">
                	<tr><td class="unique_case" colspan=9>Réservations du ' . convertdate($tranche) . ' au ' . convertdate($tranche_fin)  . ':</td></tr>';
            } ?>
                <tr class ="line">
                    <th>Adhérent</th>
                    <th>Nom de réservation</th>
                    <th>Du</th>
                    <th>Au</th>
                    <th>nb d'adh</th>
                    <th>nb de visi</th>
                    <th>prix</th>
                    <th colspan=2>réglé ?</th>
                </tr>
                <?php
                    $reque = $bdd->query($sql_req);
                    while ($resass = $reque->fetch()){
                        echo '<tr><td class="cell_left">';
                        echo $resass['username'];
                        echo '</td><td class="cell_left">';
                        echo $resass['nom'];
                        echo '</td><td class="cell_left">';
                        echo short_convertdate($resass['debut']);
                        echo '</td><td class="cell_left">';
                        echo short_convertdate($resass['fin']);
                        echo '</td><td class="cell_left">';
                        echo $resass['nbptitdub'] + $resass['nbgrosdub'];
                        echo '</td><td class="cell_left">';
                        echo $resass['nbvis_pt'] + $resass['nbvis_tr'] + $resass['nbvis_enf'];
                        echo '</td><td class="cell_left">';
                        if ($resass['we_gratuit'] == 1) {echo ' (WE offert)';}
                        elseif ($resass['officiel'] == 1) {echo ' (Séjour officiel)';}
                        else {echo $resass['prix'] . " €";
                        if ($resass['prive'] == 1) {echo ' (privatisé)';}}
                        echo '</td><td class="cell_left">';
                        switch ($resass['paye']) {
                            case 0:
                                echo 'non';
                                break;
                            case 1:
                                echo 'oui';
                                break;
                            default:
                                break;
                        }
                        echo '</td><td class="cell_right">';
                        echo '<form name="resa_reglement" method="post">
                            <input name="num_resa" type="hidden" value=' . $resass['numero'] .'>';
                        echo '<select name="reglement_resa" id="reglement'. $resass['numero'] . '">
                                <option value=0';
                                if($resass['paye'] == 0) {echo ' selected="selected"';}
                                echo '>non réglé</option>
                                <option value=1';
                                if($resass['paye'] == 1) {echo ' selected="selected"';}
                                echo '>réglé</option>
                            </select>';
                        echo '<input type="submit" value="Modifier" /></form>';
                        echo '</td></tr>';
                    }
                    $reque->closeCursor();
                ?>
            </table>
            </section>
            <section class="colonne_droite">
            <table class="formulaire_edition">
            <tr><td class="underlined" colspan=3>Édition de tableau récapitulatif</td></tr>
            <form action= "../php/editer_tableau.php" method="post">
                <tr class="justify_left"><td colspan=3 class="underlined">Trier par : </td></tr>
                    <tr><td>
                    <input type="radio" name="resa_tri" value="tri_login" id="tri_login" checked /><label for="tri_login">Adhérent</label></td><td>
                    <input type="radio" name="resa_tri" value="tri_resa_regle" id="tri_resa_regle" /><label for="tri_resa_regle">Facture réglée</label></td><td><input type="radio" name="resa_tri" value="tri_resa_date" id="tri_resa_date" /><label for="tri_resa_date">Date</label></td></tr>
                <tr class="justify_left"><td colspan=3 class="underlined">Sélectionner uniquement : </td></tr>
                    <tr><td><input type="radio" name="reglement" value="resa_non_payee" id="resa_non_payee" /><label for="resa_non_payee">Facture non réglée</label></td><td>
                    <input type="radio" name="reglement" value="resa_payee" id="resa_payee" /><label for="resa_payee">Facture réglée</label></td><td>
                    <input type="radio" name="reglement" value="resa_les_deux" id="resa_les_deux" checked /><label for="resa_les_deux">Les deux</label></td></tr>
                <tr class="justify_left"><td colspan=3 class="underlined">Sélectionner les réservations :</td></tr>
                    <tr><td><input type="radio" name="passe" value="future" id="future"><label for="future">À venir</label></td><td>
                    <input type="radio" name="passe" value="past" id="past"><label for="past">Passées</label></td><td>
                    <input type="radio" name="passe" value="toutes" id="toutes" checked><label for="toutes">Les deux</label>
                    </td></tr>
                <tr class="justify_left"><td colspan=2 class="underlined">Sélectionner : </td><td><select name="annee_resa">
                	<option value="0000" selected="selected">Année en cours</option>
                	<?php for ($i=$cur_year-1; $i >= 2017 ; $i--) { 
                    	echo '<option value="' . $i . '">Année ' . $i . ' - ' . ($i+1) . '</option>';
                	} ?>
                	<option value="9999">Toutes</option>
                	</select></td></tr>
	                <?php echo	'<input type="hidden" name="cur_year" value="' . $cur_year . '">'; ?>
	            <tr class="justify_left"><td colspan=2 class="underlined">Faire apparaître le détail des occupants :</td>
                    <td><input type="radio" name="show_occ" value="Oui" id="Oui"><label for="Oui">Oui</label>
                    <input type="radio" name="show_occ" value="Non" id="Non" checked><label for="Non">Non</label></td></tr>    
                <tr><td colspan=3><input type="submit" value="Créer"> </td></tr>  
            </form>
            </table>
            </section>
        </section>
        <?php break;
        case 4: ?>
    <!-- Tableau des tribus -->
            <section class="ensemble_gauche">
            <table class="gestion">
                <tl><td class="unique_case" colspan=8>Tribus :</td></tl>
                <tr class ="line">
                    <th>Nom</th>
                    <th colspan=2>Adhérent(s)</th>
                    <th colspan=2>Étudiant(s) à charge</th>
                    <th colspan=2>Enfant(s) à charge</th>
                    <th>Action</th>
                </tr>
                <?php
                //difficulté : deux ou trois personnes par tribu OU PLUS !
                $tribus = $bdd->query('SELECT nom,prenom,type,tribu FROM users WHERE type IN (3,4,6) AND tribu != "" ORDER BY tribu,type');
                $donnees = $tribus->fetch();
                while (is_array($donnees)) {
                        $ex_famille = $donnees['tribu'];
                        echo '<tr>';
                        echo '<td class="cell_left">' . $donnees['tribu'] . '</td>';
                        /*echo '<td class="cell_left">' . $donnees['nom'] . '</td>';
                        echo '<td class="cell_right">' . $donnees['prenom'] . '</td>';
                        $donnees = $tribus->fetch();
                        //dans le cas d'une tribu avec un seul adherent.
                        if ($donnees['tribu'] == $ex_famille && $donnees['type'] == 3) {
                            echo '<td class="cell_left">' . $donnees['nom'] . '</td>';
                            echo '<td class="cell_right">' . $donnees['prenom'] . '</td>';
                            $donnees = $tribus->fetch();
                        }
                        else {
                            echo '<td class="cell_left"></td>';
                            echo '<td class="cell_right"></td>';
                        }*/
                        echo '<td class="cell_left" colspan=2>';
                        while ($donnees['tribu'] == $ex_famille && $donnees['type'] == 3) {
                            echo $donnees['nom'] . ' ' . $donnees['prenom'] . '<br/>';
                            $donnees = $tribus->fetch();
                        }
                        echo '</td>';
                        echo '<td class="cell_left" colspan=2>';
                        while ($donnees['tribu'] == $ex_famille && $donnees['type'] == 4) {
                            echo $donnees['nom'] . ' ' . $donnees['prenom'] . '<br/>';
                            $donnees = $tribus->fetch();
                        }
                        echo '</td>';
                        echo '<td class="cell_left" colspan=2>';
                        while ($donnees['tribu'] == $ex_famille && $donnees['type'] == 6) {
                            echo $donnees['nom'] . ' ' . $donnees['prenom'] . '<br/>';
                            $donnees = $tribus->fetch();
                        }
                        echo '</td>';
                        /*else {
                            echo '<td class="unique_case" colspan=2> - </td>';
                        }*/
                        echo '<td class="unique_case"><form name="suppr" method="post" onsubmit="return confirm(\'Es-tu sûr de vouloir supprimer ce truc ?\');">';
                        echo'<input type="hidden" name="a_suppr" value=' . $ex_famille .'>
                            <input type="submit" value="Supprimer">
                            </form></td>';
                }
                $tribus->closeCursor();

                echo '<tr><form method="post">';
                echo '<td class="cell_left">Nom de tribu : </br><input type="text" name="nom_famille" required="required"></td>';
                //case adhérent avec sélecteur
                echo '<td class="cell_left" colspan=2><select name="adh[]" id="adh" multiple size=4>';
                $reponse2 = $bdd->query('SELECT name,nom,prenom FROM users WHERE type = 3 ORDER BY nom,prenom');
                // echo '<option value="Aucun">Aucun</option>';
                while ($dubtribu = $reponse2->fetch()) {
                    echo '<option value="' . $dubtribu['name'] .'"">' . $dubtribu['nom'] . ' ' . $dubtribu['prenom'] . '</option>';
                }
                $reponse2->closeCursor();
                //case etudiant avec sélecteur
                echo '<td class="cell_left" colspan=2><select name="etu[]" id="etu" multiple size=4>';
                $reponse3 = $bdd->query('SELECT name,nom,prenom FROM users WHERE type = 4 ORDER BY nom,prenom');
                // echo '<option value="Aucun">Aucun</option>';
                while ($dubtribu = $reponse3->fetch()) {
                    echo '<option value="' . $dubtribu['name'] .'"">' . $dubtribu['nom'] . ' ' . $dubtribu['prenom'] . '</option>';
                }
                $reponse3->closeCursor();
                //case enfant avec sélecteur
                echo '<td class="cell_left" colspan=2><select name="enf[]" id="enf" multiple size=4>';
                $reponse4 = $bdd->query('SELECT name,nom,prenom FROM users WHERE type = 6 ORDER BY nom,prenom');
                // echo '<option value="Aucun">Aucun</option>';
                while ($dubtribu = $reponse4->fetch()) {
                    echo '<option value="' . $dubtribu['name'] .'"">' . $dubtribu['nom'] . ' ' . $dubtribu['prenom'] . '</option>';
                }
                $reponse4->closeCursor();

                echo '</select>';
                echo '<td class="unique_case"><input type="submit" value="Créer tribu"></td>';
                echo '</form></tr>';
                echo '<tr><td colspan=5 class="cell_left"></td><td class="cell_none" colspan=2><a class="small_ita">sélect avec CTRL</a></td><td class="cell_right"></td></tr>';
                ?>
            </table>
            </section>
    <?php break; 
    } ?>
    </section>

    <?php include("../include/pieddepage.php"); ?>

</body>

</html>