<?php
	//page d'administration du bureau et du CA
        // résultat du formulaire edit-type
    if (isset($_POST['bureau']) && isset ($_POST['num']) && isset($_POST['user'])) {
        //update avec tag.
        $req = $bdd->prepare('UPDATE users SET bureau = ? WHERE numero = ?');
        $req->execute(array($_POST['bureau'], $_POST['num']));
        $req->closeCursor();
        //termine le traitement de la requête

        header ('location: ../body/gestion.php?page=6'); //on recharge la page gestion
       
    }
    include("bdd.php");
    //include("../php/fonctions.php");
    echo '<table class="gestion">';
	echo '<tr><td class="cell_right"><form name="change_bureau_CA" method="post">';
    echo '<input type="submit" value="Nouvelles élections" /></form></td>';
    echo '</tr>';

    //tableau du bureau
    echo '<table class="gestion">';
	echo '<tr><td class="unique_case" colspan=3>Bureau :</td></tr>';
    echo '<tr class ="line">
            <th colspan=2>Nom</th>
            <th>Titre</th>
        </tr>';
    $reponse = $bdd->query('SELECT * FROM users WHERE bureau != 0 AND numero >= 2  ORDER BY bureau'); //WHERE name != "admin"');
    while($donnees = $reponse->fetch()){
        echo '<tr>';         
        echo '<td class="cell_left">' . $donnees['nom'] . '</td>';
        echo '<td class="cell_right">' . $donnees['prenom'] . '</td>';
    	echo '<td class="cell_right"><form name="formulaire_bureau" method="post">
                            <input type="hidden" name="user" value="' . $_SESSION['login'] . '">
                            <input name="num" type="hidden" value=' . $donnees['numero'] .'></input>
                            <select name="bureau" id="bureau'. $donnees['numero'] . '">
                                <option value=1';
                                if($donnees['bureau'] == 1) {echo ' selected="selected"';}
                                echo '>Président(e)</option>
                                <option value=2';
                                if($donnees['bureau'] == 2) {echo ' selected="selected"';}
                                echo '>Vice-Président(e)</option>
                                <option value=3';
                                if($donnees['bureau'] == 3) {echo ' selected="selected"';}
                                echo '>Trésorier(e)</option>
                                <option value=4';
                                if($donnees['bureau'] == 4) {echo ' selected="selected"';}
                                echo '>Vice-Trésorier(e)</option>
                                <option value=5';
                                if($donnees['bureau'] == 5) {echo ' selected="selected"';}
                                echo '>Secrétaire</option>
                                <option value=6';
                                if($donnees['bureau'] == 6) {echo ' selected="selected"';}
                                echo '>Vice-Secrétaire</option>
                            </select>';
                        echo '<input type="submit" value="Modifier" /></form></td>';
        echo '</tr>';
    }
    $reponse->closeCursor();
    echo '</table>';

    //tableau du CA
    echo '<table class="gestion">';
	echo '<tr><td class="unique_case" colspan=3>CA :</td></tr>';
    echo '<tr class ="line">
            <th colspan=2>Nom</th>
        </tr>';
    $reponse = $bdd->query('SELECT * FROM users WHERE CA != 0 AND numero >= 2  ORDER BY nom'); //WHERE name != "admin"');
    while($donnees = $reponse->fetch()){
        echo '<tr>';        
        echo '<td class="cell_left">' . $donnees['nom'] . '</td>';
        echo '<td class="cell_right">' . $donnees['prenom'] . '</td>';
        echo '<td class="cell_right"><form name="formulaire_CA" method="post">';
        echo '<input type="submit" value="Virer du CA ! HA HA HA !" /></form></td>';
        echo '</tr>';
    }
    $reponse->closeCursor();
    echo '</table>';
?>