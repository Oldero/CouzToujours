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
        //résultat formulaire virer du CA
    if (isset($_POST['num_suppr_ca'])) {
        $req = $req = $bdd->prepare('UPDATE users SET ca = ? WHERE numero = ?');
        $req->execute(array(0, $_POST['num_suppr_ca']));
        $req->closeCursor();
        //termine le traitement de la requête

        header ('location: ../body/gestion.php?page=6'); //on recharge la page gestion
    }
        //résultat formulaire ajout au CA
    if (isset($_POST['adh'])) {
        $req = $req = $bdd->prepare('UPDATE users SET ca = ? WHERE name = ?');
        $req->execute(array(1, $_POST['adh']));
        $req->closeCursor();
        //termine le traitement de la requête
        header ('location: ../body/gestion.php?page=6');
    }
        //résultat formulaire ajout au bureau
    if (isset($_POST['elus'])) {
        $req = $req = $bdd->prepare('UPDATE users SET bureau = ? WHERE name = ?');
        $req->execute(array(1, $_POST['elus']));
        $req->closeCursor();
        //termine le traitement de la requête
        header ('location: ../body/gestion.php?page=6');
    }
            //résultat formulaire virer du bureau
    if (isset($_POST['num_suppr_bu'])) {
        $req = $req = $bdd->prepare('UPDATE users SET bureau = ? WHERE numero = ?');
        $req->execute(array(0, $_POST['num_suppr_bu']));
        $req->closeCursor();
        //termine le traitement de la requête

        header ('location: ../body/gestion.php?page=6'); //on recharge la page gestion
    }
    
    include("bdd.php");
    //include("../php/fonctions.php");

echo 'Changement de bureau - ACCES ADMIN';

//    echo '<table class="gestion">';
//	echo '<tr><td class="cell_right"><form name="change_bureau_CA" method="post">';
 //   echo '<input type="submit" value="Nouvelles élections" /></form></td>';
//    echo '</tr>';
    //-----------------------------------------------------------------------------------------------tableau du CA
    echo '<table class="gestion">';
    echo '<tr><td class="unique_case" colspan=3>CA :</td></tr>';
    echo '<tr class ="line">
            <th colspan=2>Nom</th>
            <th>Virer ?</th>
        </tr>';
    $reponse = $bdd->query('SELECT * FROM users WHERE CA != 0 AND numero >= 2  ORDER BY nom'); //WHERE name != "admin"');
    while($donnees = $reponse->fetch()){
        echo '<tr>';        
        echo '<td class="cell_left">' . $donnees['nom'] . '</td>';
        echo '<td class="cell_right">' . $donnees['prenom'] . '</td>';
        echo '<td class="cell_right"><form name="suppr_CA" method="post" onsubmit="return confirm(\'Es-tu sûr(e) de vouloir supprimer ce truc ?\');">';
        echo '<input name="num_suppr_ca" type="hidden" value="' . $donnees['numero'] . '">';
        echo '<input type="submit" value="Virer du CA ! HA HA HA !" /></form></td>';
        echo '</tr>';
    }
    $reponse->closeCursor();

//Ajout au CA
    echo '<tr><form method="post">';
    echo '<td class="cell_left" colspan=2><select name="adh" id="adh" size=1>';
    $reponse = $bdd->query('SELECT name,nom,prenom FROM users WHERE CA !=1 ORDER BY nom,prenom');
    while ($dubtribu = $reponse->fetch()) {
        echo '<option value="' . $dubtribu['name'] .'"">' . $dubtribu['nom'] . ' ' . $dubtribu['prenom'] . '</option>';
    }
    $reponse->closeCursor();

    echo '</select>';
    echo '<td class="unique_case"><input type="submit" value="Rajouter au CA"></td>';
    echo '</form></tr>';
    echo '</table>';

    //---------------------------------------------------------tableau du bureau
    echo '<table class="gestion">';
	echo '<tr><td class="unique_case" colspan=4>Bureau :</td></tr>';
    echo '<tr class ="line">
            <th colspan=2>Nom</th>
            <th>Titre</th>
            <th>Virer ?</th>
        </tr>';
    $reponse = $bdd->query('SELECT * FROM users WHERE bureau != 0 AND numero >= 2  ORDER BY bureau'); //WHERE name != "admin"');
    while($donnees = $reponse->fetch()){
        echo '<tr>';         
        echo '<td class="cell_left">' . $donnees['nom'] . '</td>';
        echo '<td class="cell_right">' . $donnees['prenom'] . '</td>';
    	echo '<td class="cell_right"><form name="formulaire_bureau" method="post">
                <input type="hidden" name="user" value="' . $_SESSION['login'] . '">
                <input name="num" type="hidden" value=' . $donnees['numero'] .'>
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
// Virer du bureau
        echo '<td class="cell_left"><form name="suppr_bureau" method="post" onsubmit="return confirm(\'Es-tu sûr(e) de vouloir supprimer ce truc ?\');">';
        echo '<input name="num_suppr_bu" type="hidden" value="' . $donnees['numero'] . '">';
        echo '<input type="submit" value="Destituer (et vice et versa)" /></form></td>';
        echo '</tr>';
    }
    $reponse->closeCursor();
    //Ajout au bureau
    echo '<tr><form method="post">';
    echo '<td class="cell_left" colspan=2><select name="elus" id="elus" size=1>';
    $reponse = $bdd->query('SELECT name,nom,prenom FROM users WHERE CA =1 AND bureau < 1 ORDER BY nom,prenom');
    while ($dubtribu = $reponse->fetch()) {
        echo '<option value="' . $dubtribu['name'] .'"">' . $dubtribu['nom'] . ' ' . $dubtribu['prenom'] . '</option>';
    }
    $reponse->closeCursor();

    echo '</select>';
    echo '<td class="unique_case"><input type="submit" value="Rajouter au bureau"></td>';
    echo '</form>';
    echo '<td></td></tr>';
    echo '</table>';
?>