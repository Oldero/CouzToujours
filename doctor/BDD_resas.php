<?php
	//page d'administration, reset de mdp, génération de nouvel user, etc.

    include("bdd.php");
//    include("../php/fonctions.php");
    
//---------------------------------------------------------------------------RÉPONSES FORMULAIRES
      // résultat du formulaire modifier prix
    if (isset($_POST['new_price']) && isset ($_POST['num_resa']) && isset($_POST['user'])) {
        //update avec tag.
        $req = $bdd->prepare('UPDATE reservation SET prix = ? WHERE numero = ?');
        $req->execute(array($_POST['new_price'], $_POST['num_resa']));
        $req->closeCursor();
        //termine le traitement de la requête        
        header ('location: ../body/gestion.php?page=8'); //on recharge la page gestion
        echo'Modifié ! <br />';
    }    
    //réponse du formulaire suppression de résa
    if (isset($_POST['num_suppr'])) {
        //delete avec tag tribu.
        $req = $bdd->prepare('DELETE FROM reservation WHERE numero = ?');
        $req->execute(array($_POST['num_suppr']));
        $req->closeCursor();
        //termine le traitement de la requête
        header ('location: ../body/gestion.php?page=8'); //on recharge la page gestion
        echo'Supprimé ! <br />';
    }
    //réponse du formulaire modifier nb de visiteurs et type de résa (update dans toutes les lignes de la BDD SAUF we_off pour l'instant (lien avec trubus))
    if (isset($_POST['num_mod_nb'])) {
        //calcul du nouveau prix du séjour, premier cas : si nuitée, second cas si WE ou semaine, privé ou non.
        $nbnuitee = NbJours($_POST['deb'], $_POST['fina']);
        if($_POST['pack'] == 1) {$nouveau_cout = $nbnuitee * (6 * $_POST['nbpd'] + 10 * $_POST['nbvp'] + 7 * $_POST['nbvr'] + 5 * $_POST['nbve']);}
        //else nouveau prix vs we_off ou pas, package, privé ou pas
        else {$nouveau_cout = (1-$_POST['off'])*((1-$_POST['private'])*(140*(3-$_POST['pack'])+330*($_POST['pack']-2))+($_POST['private'])*(200*(3-$_POST['pack'])+450*($_POST['pack']-2)));}
        if ($nouveau_cout == 0) {$regle = 1;}
        else{$regle = 0;}
        $req = $bdd->prepare('UPDATE reservation SET nbptitdub = ?, nbgrosdub = ?, nb_adh_plus7 = ?, nb_adh_toddler = ?, nbvis_pt = ?, nbvis_tr = ?, nbvis_enf = ?, nbvis_toddler = ?, prive = ?, prix = ?, paye = ?, package = ? WHERE numero = ?');
        $req->execute(array($_POST['nbpd'], $_POST['nbgd'], $_POST['nbps'], $_POST['nbms'], $_POST['nbvp'], $_POST['nbvr'], $_POST['nbve'], $_POST['nbvt'], $_POST['private'], $nouveau_cout, $regle, $_POST['pack'], $_POST['num_mod_nb']));
        $req->closeCursor();
        header ('location: ../body/gestion.php?page=8'); //on recharge la page gestion
        echo'Modifié ! <br />';
    }

    echo 'Modification des réservations - ACCES ADMIN';
//---------------------------------------------------------------------------RESAS À VENIR
 	echo '<table class="gestion">';

    echo '<tr>'; 
//Premier tableau : résas à venir
    echo '<td class="cell_none" colspan=15>Réservations à venir</td>';
    //Aller chercher le nb de réservations
    $reponse = $bdd->query("SELECT COUNT(*) FROM reservation WHERE fin >= '" . date("Y-m-d") . "' ORDER BY fin");
    $nb = $reponse->fetch();
    $reponse->closeCursor();
    echo '<td class="cell_none" colspan=5>Total réservations : ' . $nb[0] . '</td></tr>';    
    echo '<tr class ="line">
            <th>Adhérent</th>
    		<th>Nom de réservation</th>
            <th>Du</th>
            <th>Au</th>
            <th colspan=2>Prix</th>
        	<th colspan=2></th>
            <th>Pack</th>
            <th>WEoff</th>
            <th>PtiDub</th>
            <th>GroDub</th>
            <th>Adh+7</th>
            <th>Adh-7</th>
            <th>VisPT</th>
            <th>VisTR</th>
            <th>Vis+7</th>
            <th>Vis-7</th>
            <th>Privé</th>
            <th>Modif ?</th>
        </tr>';
    $reponse = $bdd->query("SELECT * FROM reservation WHERE fin >= '" . date("Y-m-d") . "' ORDER BY fin");
    while($donnees = $reponse->fetch()){
        echo '<tr>';
        echo '<td class="cell_left">' . $donnees['username'] . '</td>';
        echo '<td class="cell_left">' . $donnees['nom'] . '</td>';            
        echo '<td class="cell_left">' . $donnees['debut'] . '</td>';
        echo '<td class="cell_none">' . $donnees['fin'] . '</td>';
        //Premier formulaire : éditer prix et/ou supprimer
        echo '<td class="cell_left"><form name="modifier_prix" method="post" onsubmit="return confirm(\'Es-tu sûr(e) de vouloir modifier ce truc ?\');">
            <input name="num_resa" type="hidden" value=' . $donnees['numero'] .'></input>
            <input type="hidden" name="user" value="' . $_SESSION['login'] . '">
            <input type="number" name="new_price" value="' . $donnees['prix'] . '" style ="width: 90%" min=0>
            <td class="unique_case"><input type="submit" value="Modifier" /></form></td>';
        echo '<td class="cell_left"><form name="suppr_resa" method="post" onsubmit="return confirm(\'Es-tu VRAIMENT sûr(e) de vouloir SUPPRIMER ce truc ? (AUCUN RETOUR POSSIBLE !)\');">
            <input name="num_suppr" type="hidden" value=' . $donnees['numero'] .'></input>
            <input type="hidden" name="user" value="' . $_SESSION['login'] . '">';
        echo '<input type="submit" value="Supprimer" /></form></td>';
        echo '<td class="cell_left"></td>'; //case vide séparation
                //Second formulaire : changer le nb d'occupants et recalculer
        echo '<td class="cell_left"><form name="modifier_nb" method="post" onsubmit="return confirm(\'Es-tu sûr(e) de vouloir modifier ce truc ?\');">';
        echo '<input type="hidden" name="num_mod_nb" value=' . $donnees['numero'] . '></input>';
        echo '<input type="hidden" name="deb" value=' . $donnees['debut'] . '></input>';
        echo '<input type="hidden" name="fina" value=' . $donnees['fin'] . '></input>';
        echo '<input type="hidden" name="price" value=' . $donnees['prix'] . '></input>';
        echo '<select type="hidden" name="pack" id=pack' . $donnees['numero'] . '>';
            echo'<option value=1';
                if($donnees['package'] == 1) {echo ' selected="selected"';}
                   echo '>N</option>
                <option value=2';
                if($donnees['package'] == 2) {echo ' selected="selected"';}
                    echo '>W</option>
                <option value=3';
                if($donnees['package'] == 3) {echo ' selected="selected"';}
                    echo '>S</option>';
        echo '</select></td><td class="cell_left">';
        echo '<input type="hidden" name="off" value=' . $donnees['we_gratuit'] . '></input>';
            switch ($donnees['we_gratuit']) {
            case 1:
                echo 'x';
                break;
            case 0:
                echo ' ';
                break;
            default:
                # code...
                break;
        }
        echo '</td>';
        echo '<td class="cell_left"><input name = "nbpd" type="number" value=' . $donnees['nbptitdub'] . ' style ="width: 90%" min=0></td>';
        echo '<td class="cell_left"><input name = "nbgd" type="number" value=' . $donnees['nbgrosdub'] . ' style ="width: 90%" min=0></td>';
        echo '<td class="cell_left"><input name = "nbps" type="number" value=' . $donnees['nb_adh_plus7'] . ' style ="width: 90%" min=0></td>';
        echo '<td class="cell_left"><input name = "nbms" type="number" value=' . $donnees['nb_adh_toddler'] . ' style ="width: 90%" min=0></td>';
        echo '<td class="cell_left"><input name = "nbvp" type="number" value=' . $donnees['nbvis_pt'] . ' style ="width: 90%" min=0></td>';
        echo '<td class="cell_left"><input name = "nbvr" type="number" value=' . $donnees['nbvis_tr'] . ' style ="width: 90%" min=0></td>';
        echo '<td class="cell_left"><input name = "nbve" type="number" value=' . $donnees['nbvis_enf'] . ' style ="width: 90%" min=0></td>';
        echo '<td class="cell_left"><input name = "nbvt" type="number" value=' . $donnees['nbvis_toddler'] . ' style ="width: 90%" min=0></td>';
        echo '<td class="cell_left"><select name="private" id="private'. $donnees['numero'] . '">
                <option value=1';
                if($donnees['prive'] == 1) {echo ' selected="selected"';}
                   echo '>Oui</option>
                <option value=0';
                if($donnees['prive'] == 0) {echo ' selected="selected"';}
                    echo '>Non</option>
            </select></td>';
        echo '<td class="cell_left"><input type="submit" value="Recalculer"></td></form>';
    }
    $reponse->closeCursor();
    echo '</table>';

//---------------------------------------------------------------------------RESAS PASSÉ
 	echo '<table class="gestion">';
    echo '<tr>';
 //Second tableau : résas passées
    echo '<td class="cell_none" colspan=15>Réservations passées</td>';
    //Aller chercher le nb de réservations
    $reponse = $bdd->query("SELECT COUNT(*) FROM reservation WHERE fin < '" . date("Y-m-d") . "' ORDER BY fin");
    $nb = $reponse->fetch();
    $reponse->closeCursor();
    echo '<td class="cell_none" colspan=5>Total réservations : ' . $nb[0] . '</td></tr>';    
    echo '<tr class ="line">
            <th>Adhérent</th>
    		<th>Nom de réservation</th>
            <th>Du</th>
            <th>Au</th>
            <th colspan=2>Prix</th>
            <th colspan=2></th>
            <th>Pack</th>
            <th>WEoff</th>
            <th>PtiDub</th>
            <th>GroDub</th>
            <th>Adh+7</th>
            <th>Adh-7</th>
            <th>VisPT</th>
            <th>VisTR</th>
            <th>Vis+7</th>
            <th>Vis-7</th>
            <th>Privé</th>
            <th>Modif ?</th>
        </tr>';
    $reponse = $bdd->query("SELECT * FROM reservation WHERE fin < '" . date("Y-m-d") . "' ORDER BY fin DESC");
    while($donnees = $reponse->fetch()){
        echo '<tr>';
        echo '<td class="cell_left">' . $donnees['username'] . '</td>';
        echo '<td class="cell_left">' . $donnees['nom'] . '</td>';            
        echo '<td class="cell_left">' . $donnees['debut'] . '</td>';
        echo '<td class="cell_none">' . $donnees['fin'] . '</td>';
        //Premier formulaire : éditer prix et/ou supprimer
        echo '<td class="cell_left"><form name="modifier_prix" method="post" onsubmit="return confirm(\'Es-tu sûr(e) de vouloir modifier ce truc ?\');">
            <input name="num_resa" type="hidden" value=' . $donnees['numero'] .'></input>
            <input type="hidden" name="user" value="' . $_SESSION['login'] . '">
            <input type="number" name="new_price" value="' . $donnees['prix'] . '" style ="width: 90%" min=0>
            <td class="unique_case"><input type="submit" value="Modifier" /></form></td>';
        echo '<td class="cell_left"><form name="suppr_resa" method="post" onsubmit="return confirm(\'Es-tu VRAIMENT sûr(e) de vouloir SUPPRIMER ce truc ? (AUCUN RETOUR POSSIBLE !)\');">
            <input name="num_suppr" type="hidden" value=' . $donnees['numero'] .'></input>
            <input type="hidden" name="user" value="' . $_SESSION['login'] . '">';
        echo '<input type="submit" value="Supprimer" /></form></td>';
        echo '<td class="cell_left"></td>'; //case vide séparation
                //Second formulaire : changer le nb d'occupants et recalculer
        echo '<td class="cell_left"><form name="modifier_nb" method="post" onsubmit="return confirm(\'Es-tu sûr(e) de vouloir modifier ce truc ?\');">';
        echo '<input type="hidden" name="num_mod_nb" value=' . $donnees['numero'] . '></input>';
        echo '<input type="hidden" name="deb" value=' . $donnees['debut'] . '></input>';
        echo '<input type="hidden" name="fina" value=' . $donnees['fin'] . '></input>';
        echo '<input type="hidden" name="price" value=' . $donnees['prix'] . '></input>';
        echo '<select type="hidden" name="pack" id=pack' . $donnees['numero'] . '>';
            echo'<option value=1';
                if($donnees['package'] == 1) {echo ' selected="selected"';}
                   echo '>N</option>
                <option value=2';
                if($donnees['package'] == 2) {echo ' selected="selected"';}
                    echo '>W</option>
                <option value=3';
                if($donnees['package'] == 3) {echo ' selected="selected"';}
                    echo '>S</option>';
        echo '</select></td><td class="cell_left">';
        echo '<input type="hidden" name="off" value=' . $donnees['we_gratuit'] . '></input>';
            switch ($donnees['we_gratuit']) {
            case 1:
                echo 'x';
                break;
            case 0:
                echo ' ';
                break;
            default:
                # code...
                break;
        }
        echo '</td>';
        echo '<td class="cell_left"><input name = "nbpd" type="number" value=' . $donnees['nbptitdub'] . ' style ="width: 90%" min=0></td>';
        echo '<td class="cell_left"><input name = "nbgd" type="number" value=' . $donnees['nbgrosdub'] . ' style ="width: 90%" min=0></td>';
        echo '<td class="cell_left"><input name = "nbps" type="number" value=' . $donnees['nb_adh_plus7'] . ' style ="width: 90%" min=0></td>';
        echo '<td class="cell_left"><input name = "nbms" type="number" value=' . $donnees['nb_adh_toddler'] . ' style ="width: 90%" min=0></td>';
        echo '<td class="cell_left"><input name = "nbvp" type="number" value=' . $donnees['nbvis_pt'] . ' style ="width: 90%" min=0></td>';
        echo '<td class="cell_left"><input name = "nbvr" type="number" value=' . $donnees['nbvis_tr'] . ' style ="width: 90%" min=0></td>';
        echo '<td class="cell_left"><input name = "nbve" type="number" value=' . $donnees['nbvis_enf'] . ' style ="width: 90%" min=0></td>';
        echo '<td class="cell_left"><input name = "nbvt" type="number" value=' . $donnees['nbvis_toddler'] . ' style ="width: 90%" min=0></td>';
        echo '<td class="cell_left"><select name="private" id="private'. $donnees['numero'] . '">
                <option value=1';
                if($donnees['prive'] == 1) {echo ' selected="selected"';}
                   echo '>Oui</option>
                <option value=0';
                if($donnees['prive'] == 0) {echo ' selected="selected"';}
                    echo '>Non</option>
            </select></td>';
        echo '<td class="cell_left"><input type="submit" value="Recalculer"></td></form>';
    }
    $reponse->closeCursor();
    echo '</table>';

?>