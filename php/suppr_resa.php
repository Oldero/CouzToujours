<?php
    // On définit un login et un mot de passe de base pour tester notre exemple. Cependant, vous pouvez très bien interroger votre base de données afin de savoir si le visiteur qui se connecte est bien membre de votre site
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
        header ('location: ../monCompte.php');
    
 }
else {
        echo 'Les variables du formulaire ne sont pas déclarées.';
    }
?>