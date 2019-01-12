<?php
	//page d'administration, reset de mdp, etc.

    session_start ();
    include("bdd.php");
    include("../php/fonctions.php");

	echo'<!--Création du tableau : -->
        <table class="gestion">
            <caption class="titre_tableau">Gestion de la BDD :</caption>
                <tr class ="line">
                <th>Numéro</th>
				<th>Login</th>
                <th colspan=2>Nom</th>
                <th colspan=2>Reset pwd</th>
            </tr>';
    $reponse = $bdd->query('SELECT * FROM users ORDER BY nom, prenom'); //WHERE name != "admin"');
    while($donnees = $reponse->fetch()){
            //On n'affiche pas l'admin
            if ($donnees['name'] != "admin") {
            echo '<tr>';
            echo '<td class= "cell_left">' . $donnees['numero'] . '</td>';
            echo '<td class= "cell_left">' . $donnees['name'] . '</td>';            
            echo '<td class="cell_left">' . $donnees['nom'] . '</td>';
            echo '<td class="cell_none">' . $donnees['prenom'] . '</td>';
            echo '<td class="cell_left"><form name="formul" method="post" action="doctor/reset_pwd.php">
                <input name="num" type="hidden" value=' . $donnees['numero'] .'></input>
                <input type="text" name="new_pwd"></td>';
            echo '<td class="cell_right"><input type="submit" value="Générer" /></form></td>';
            }
        }
        $reponse->closeCursor();
    echo '</table>';
?>