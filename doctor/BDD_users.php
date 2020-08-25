<?php
	//page d'administration, reset de mdp, génération de nouvel user, etc.

    include("bdd.php");
    //include("../php/fonctions.php");
    echo 'Changement des infos des utilisateurs - ACCES ADMIN (pas encore au point)';

    echo '<table class="gestion">';
	echo '<tr><td class="unique_case" colspan=10>Table de users :</td></tr>';
    echo '<tr class ="line">
            <th>Numéro</th>
    		<th>Login</th>
            <th colspan=2>Nom</th>
            <th>type</th>
            <th>tribu</th>
            <th>bureau</th>
            <th>ca</th>
            <th>cotiz</th>
            <th>WE_off</th>
        </tr>';
    $reponse = $bdd->query('SELECT * FROM users ORDER BY numero'); //WHERE name != "admin"');
    while($donnees = $reponse->fetch()){
        echo '<tr>';
        echo '<td class="cell_left">' . $donnees['numero'] . '</td>';
        echo '<td class="cell_left">' . $donnees['name'] . '</td>';            
        echo '<td class="cell_left">' . $donnees['nom'] . '</td>';
        echo '<td class="cell_right">' . $donnees['prenom'] . '</td>';
        echo '<td class="cell_right">' . $donnees['type'] . '</td>';
        echo '<td class="cell_right">' . $donnees['tribu'] . '</td>';
        echo '<td class="cell_right">' . $donnees['bureau'] . '</td>';
        echo '<td class="cell_right">' . $donnees['ca'] . '</td>';
        echo '<td class="cell_right">' . $donnees['cotiz'] . '</td>';
        echo '<td class="cell_right">' . $donnees['we_offert'] . '</td>';
        echo '</tr>';
    }
    $reponse->closeCursor();
    echo '</table>';
?>