<?php
//page php pour effectuer les modifs de bdd par gestion
    
try
    {
        $bdd = new PDO('mysql:host=localhost;dbname=couztoujours', 'root', 'root');
    }
catch (Exception $e) // Si erreur
    {
            die('Erreur : ' . $e->getMessage());
    }

    // on teste si nos variables sont définies
if (isset($_POST['typ']) && isset ($_POST['num'])) {
        //update avec tag.
        $req = $bdd->prepare('UPDATE users SET type = ? WHERE numero = ?');
        $req->execute(array($_POST['typ'], $_POST['num']));
        $req->closeCursor();
        //termine le traitement de la requête    
        header ('location: ../gestion.php'); //on recharge la page moncompte
    
 }
else {
        echo 'Les variables du formulaire ne sont pas déclarées.';
    }
?>