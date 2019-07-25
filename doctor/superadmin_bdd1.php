<?php
	//page d'administration, reset de mdp, génération de nouvel user, etc.

    include("bdd.php");
    //include("../php/fonctions.php");
    echo '<table class="gestion">';
	echo '<tr><td class="unique_case" colspan=4>Table de users :</td></tr>';
    echo '<tr class ="line">
            <th>Numéro</th>
    		<th>Login</th>
            <th colspan=2>Nom</th>
        </tr>';
    $reponse = $bdd->query('SELECT * FROM users ORDER BY numero'); //WHERE name != "admin"');
    while($donnees = $reponse->fetch()){
        echo '<tr>';
        echo '<td class="cell_left">' . $donnees['numero'] . '</td>';
        echo '<td class="cell_left">' . $donnees['name'] . '</td>';            
        echo '<td class="cell_left">' . $donnees['nom'] . '</td>';
        echo '<td class="cell_right">' . $donnees['prenom'] . '</td>';
        echo '</tr>';
    }
    $reponse->closeCursor();
    echo '</table>';
?>