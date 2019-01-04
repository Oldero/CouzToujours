<?php
    
try
    {
        $bdd = new PDO('mysql:host=localhost;dbname=couztoujours', 'root', 'root');
    }
catch (Exception $e) // Si erreur
    {
            die('Erreur : ' . $e->getMessage());
    }

    // on teste si nos variables sont définies
if (isset($_GET['numero'])) {

        //delete avec tag numero.
        $req = $bdd->prepare('DELETE FROM reservation WHERE numero = ?');
        $req->execute(array($_GET['numero']));
        $req->closeCursor();
        //termine le traitement de la requête    
        header ('location: ../monCompte.php'); //on recharge la page moncompte
    
 }
else {
        echo 'Les variables du formulaire ne sont pas déclarées.';
    }
?>