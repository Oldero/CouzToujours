<?php
	//page d'administration, reset de mdp, génération de nouvel user, etc.

    include("bdd.php");
    //include("../php/fonctions.php");
    //Entrer nouvel anniv
    echo '<table class="gestion">
        <tr><td class="unique_case" colspan=5> Anniversaire à rentrer : </td></tr>';
    echo '<form name="new_anniv" method="post">';
    echo '<tr>';
    echo '<td class = "unique_case"><label for="new_qui">Qui ?</br></label><input type="text" name="new_qui" id="new_qui"></td>';
    echo '<td class = "unique_case"><label for="new_quand">Quand ?</br></label><input type="date" name="new_quand" id="new_quand" placeholder="AAAA-MM-JJ"></td>';
    echo '<td class = "unique_case"><label for="new_ou">Où ?</br></label><input type="text" name="new_ou" id="new_ou"></td>';
    echo '<td class = "unique_case"><label for="new_quoi">Quoi ?</br></label><select name="new_quoi" id="new_quoi">
    		<option value=1>Anniversaire</option>
    		<option value=4>Mariage</option>
    	</select></td>';
    echo '</tr>';
    echo '<tr><td class = "unique_case" colspan=5><a class="sign_news">Rentrer le "qui ?" comme il apparaîtra sur la page d\'accueil ou sur le calendrier - Date sous format AAAA-MM-JJ</a></td></tr>';
    echo '</form></table>';
    //Table d'anniversaires
    echo '<table class="gestion">';
	echo '<tr><td class="unique_case" colspan=4>Table des anniversaires :</td></tr>';
    echo '<tr class ="line">
            <th>Quoi</th>
    		<th>Qui</th>
            <th>Quand</th>
            <th>Où</th>
        </tr>';
    $reponse = $bdd->query('SELECT * FROM anniversaires ORDER BY numero'); //WHERE name != "admin"');
    while($donnees = $reponse->fetch()){
        echo '<tr>';
        switch ($donnees['quoi']) {
        	case 1:
        		$quoi = "Anniv'";
        		break;
       		case 4:
        		$quoi = "Mariage";
        		break;
        	default:
        		$quoi = "???";
        		break;
        }
        echo '<td class="cell_left">' . $quoi . '</td>';
        echo '<td class="cell_left">' . $donnees['qui'] . '</td>';            
        echo '<td class="cell_left">' . $donnees['quand'] . '</td>';
        echo '<td class="unique_case">' . $donnees['ou'] . '</td>';
    }
    $reponse->closeCursor();
    echo '</table>';

?>