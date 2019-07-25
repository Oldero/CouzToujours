<?php
	//page d'administration, reset de mdp, génération de nouvel user, etc.

    include("bdd.php");
    //include("../php/fonctions.php");
    //resultat du formulaire de génération d'utilisateur
    if(isset($_POST['gen_login']) && isset($_POST['gen_pass']) && isset($_POST['gen_prenom']) && isset($_POST['gen_nom'])){
        $nwlogin=htmlspecialchars($_POST['gen_login']);
        $nwpd=password_hash($_POST['gen_pass'], PASSWORD_DEFAULT);
        $nwprenom=htmlspecialchars($_POST['gen_prenom']);
        $nwnom=htmlspecialchars($_POST['gen_nom']);
        switch ($_POST['gen_bureau']) {
            case 'Oui':
                $nwbureau=1;
                break;
            case 'Non':
                $nwbureau=0;
                break;
            default:
                break;
        }
        switch ($_POST['gen_ca']) {
            case 'Oui':
                $nwca=1;
                break;
            case 'Non':
                $nwca=0;
                break;
            default:
                break;
        }
        $gener = $bdd->prepare('INSERT INTO users(name, password, prenom, nom, type, bureau, ca, cotiz) VALUES(:name, :password, :prenom, :nom, :type, :bureau, :ca, :cotiz)');
        $gener->execute(array(
            'name' => $nwlogin, 
            'password' => $nwpd, 
            'prenom' => $nwprenom, 
            'nom' => $nwnom, 
            'type' => $_POST['gen_type'], 
            'bureau' => $nwbureau, 
            'ca' => $nwca, 
            'cotiz' => 0));
        $gener->closeCursor();
        echo'Utilisateur ajouté ! <br />';
    }
    //générer nouvel user
    echo '<table class="gestion">
        <tr><td class="unique_case" colspan=4> Générer nouvel utilisateur </td></tr>';
    echo '<form name="new_user" method="post">';
    echo '<tr><td class= "cell_left"><label for id="gen_login">Nouveau login : <br /></label><input type="text" id="gen_login" name="gen_login" required="required"></td>';
    echo '<td class= "cell_left"><label for id="gen_pass">password : <br /></label><input type="text" id="gen_pass" name="gen_pass" required="required"></td>';
    echo '<td class= "cell_left"><label for id="gen_prenom">Prénom : <br /></label><input type="text" id="gen_prenom" name="gen_prenom" required="required"></td>';
    echo '<td class= "unique_case"><label for id="gen_nom">Nom : <br /></label><input type="text" id="gen_nom" name="gen_nom" required="required"></td></tr>';
    echo '<tr><td class= "cell_left"><label for id="gen_type">Type* : <br /></label><input type="number" id="gen_type" name="gen_type" value=0 min=0 max=5></td>';
    echo '<td class= "cell_left">Bureau : <br /><input type="radio" name="gen_bureau" value="Non" id="Non" checked /><label for="Non">Non </label><input type="radio" name="gen_bureau" value="Oui" id="Oui" /><label for="Oui">Oui</label></td>';
    echo '<td class= "cell_left">CA : <br /><input type="radio" name="gen_ca" value="Non" id="Non" checked /><label for="Non">Non </label><input type="radio" name="gen_ca" value="Oui" id="Oui" /><label for="Oui">Oui</label></td>';
    echo '<td class="unique_case"><input type="submit" value="Créer nouvel utilisateur"></td></tr>';
    echo '<tr><td class = "unique_case" colspan=4><a class="sign_news">* 0 = visiteur, 1 = P\'tit Dub, 2 = Gros Dub solo, 3 = Gros Dub tribu, 4 = Étudiant-parasite, 5 = Membre d\'honneur, 6 = Enfant 10-18</a></td></tr>';
    echo '</form></table>';
    //tableau de génération de password
 	echo '<table class="gestion">';
	echo '<tr><td class="unique_case" colspan=7><form method="post" action="../php/mails.php">Générer tableau de mails : <input type="hidden" name="tab_date" value=' . date("Y-m-d") . '><input type="submit" value="générer"></form></td></tr>';
    $reponse = $bdd->query('SELECT COUNT(*) FROM users WHERE last_co != "NULL"');
    $nb = $reponse->fetch();
    $reponse->closeCursor();
    echo '<tr><td class="unique_case" colspan=7>Reset password : total : ' . $nb[0] . '/95</td></tr>';    
    echo '<tr class ="line">
            <th>Numéro</th>
    		<th>Login</th>
            <th colspan=2>Nom</th>
            <th colspan=2>Reset pwd</th>
            <th>co ?</th>
        </tr>';
    $reponse = $bdd->query('SELECT * FROM users ORDER BY numero'); //WHERE name != "admin"');
    while($donnees = $reponse->fetch()){
        echo '<tr>';
        echo '<td class="cell_left">' . $donnees['numero'] . '</td>';
        echo '<td class="cell_left">' . $donnees['name'] . '</td>';            
        echo '<td class="cell_left">' . $donnees['nom'] . '</td>';
        echo '<td class="cell_none">' . $donnees['prenom'] . '</td>';
        echo '<td class="cell_left"><form name="generate" method="post" action="../doctor/reset_pwd.php">
            <input name="num" type="hidden" value=' . $donnees['numero'] .'></input>
            <input type="text" name="new_pwd"></td>';
        echo '<td class="unique_case"><input type="submit" value="Générer" /></form></td>';
        if($donnees['last_co']==NULL){
            echo '<td class="unique_case">jamais co</td></tr>';
        }
        else{
            echo '<td class="unique_case">yep</td></tr>';
        }
    }
    $reponse->closeCursor();
    echo '</table>';
?>