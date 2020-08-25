<?php
	//page d'administration, reset de mdp, génération de nouvel user, etc.

    include("bdd.php");
    //include("../php/fonctions.php");
    //Entrer nouvel anniv
    //réponse du formulaire ajout anniversaire
    if (isset($_POST['new_qui']) && isset($_POST['new_quand']) && isset($_POST['new_quoi'])) {
        $anniv_plus = $bdd->prepare('INSERT INTO anniversaires(quoi, qui, quand, ou) values(:quoi, :qui, :quand, :ou)');
        $anniv_plus->execute(array(
            'quoi' => $_POST['new_quoi'],
            'qui' => $_POST['new_qui'],
            'quand' => $_POST['new_quand'],
            'ou' => $_POST['new_ou']));
        $anniv_plus->closeCursor();
        header('location: ../body/gestion.php?page=9');
        echo'Ajouté ! <br />';
    }
    echo 'Rajout/suppr anniversaire - ACCES ADMIN';
    //réponse du formulaire suppression d'anniv
    if (isset($_POST['num_ann_suppr'])) {
        //delete avec tag tribu.
        $req = $bdd->prepare('DELETE FROM anniversaires WHERE numero = ?');
        $req->execute(array($_POST['num_ann_suppr']));
        $req->closeCursor();
        //termine le traitement de la requête
        header ('location: ../body/gestion.php?page=9'); //on recharge la page gestion
        echo'Supprimé ! <br />';
    }

    echo '<table class="gestion">';

    //Création nouvelle entrée
    echo '<tr><td class="unique_case" colspan=5> Anniversaire à rentrer : </td></tr>';
    echo '<form name="new_anniv" method="post">';
    echo '<tr>';
    echo '<td class = "unique_case"><label for="new_qui">Qui ?</br></label><input type="text" name="new_qui" id="new_qui" required></td>';
    echo '<td class = "unique_case"><label for="new_quand">Quand ?</br></label><input type="date" name="new_quand" id="new_quand" placeholder="AAAA-MM-JJ" required></td>';
    echo '<td class = "unique_case"><label for="new_ou">Où ?</br></label><input type="text" name="new_ou" id="new_ou"></td>';
    echo '<td class = "unique_case"><label for="new_quoi">Quoi ?</br></label><select name="new_quoi" id="new_quoi" required>
    		<option value=1>Anniversaire</option>
    	</select></td>';
    echo '<td><input type="submit" value="Ajouter"></form></td></tr>';
    echo '<tr><td class = "unique_case" colspan=5><a class="sign_news">Rentrer le "qui ?" comme il apparaîtra sur la page d\'accueil ou sur le calendrier - Date sous format AAAA-MM-JJ</a></td>';
    echo '</tr></table>';

    //Table d'anniversaires
    echo '<table class="gestion">';
	echo '<tr><td class="unique_case" colspan=4>Table des anniversaires :</td></tr>';
    echo '<tr class ="line">';
//            <th>Quoi</th>
    echo '<th>Qui</th>
            <th>Quand</th>
            <th>Où</th>
            <th>Suppr ?</th>
        </tr>';
    $reponse = $bdd->query('SELECT * FROM anniversaires ORDER BY qui'); //WHERE name != "admin"');
    while($donnees = $reponse->fetch()){
        echo '<tr>';
        switch ($donnees['quoi']) {
        	case 1:
        		$quoi = "Anniv'";
        		break;
        	default:
        		$quoi = "???";
        		break;
        }
        //echo '<td class="cell_left">' . $quoi . '</td>';
        echo '<td class="cell_left">' . $donnees['qui'] . '</td>';            
        echo '<td class="cell_left">' . $donnees['quand'] . '</td>';
        echo '<td class="unique_case">' . $donnees['ou'] . '</td>';
        echo '<td class="cell_left"><form name="suppr_anniv" method="post" onsubmit="return confirm(\'Es-tu VRAIMENT sûr(e) de vouloir SUPPRIMER ce truc ? (AUCUN RETOUR POSSIBLE !)\');">
            <input name="num_ann_suppr" type="hidden" value=' . $donnees['numero'] .'></input>
            <input type="hidden" name="user" value="' . $_SESSION['login'] . '">';
            echo '<input type="submit" value="Supprimer" /></form></td>';
    }
    $reponse->closeCursor();
    echo '</table>';

?>