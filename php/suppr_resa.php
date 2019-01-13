<?php
    //Résultat du formulaire supprimer réservation depuis la page monCompte

    include("../doctor/bdd.php");

    // on teste si nos variables sont définies
    if (isset($_GET['numero'])) {

            //delete avec tag numero.
            $req = $bdd->prepare('DELETE FROM reservation WHERE numero = ?');
            $req->execute(array($_GET['numero']));
            $req->closeCursor();
            //termine le traitement de la requête    
            header ('location: ../body/monCompte.php'); //on recharge la page moncompte
        
     }
    else {
            echo 'Les variables du formulaire ne sont pas déclarées.';
        }
?>