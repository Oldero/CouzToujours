<?php
//page php pour reset le pwd depuis interface admin
    
    include("../doctor/bdd.php");

    // on teste si nos variables sont définies
if (isset ($_POST['num']) && isset($_POST['new_pwd'])) {
        //update avec tag.
        $nwpwd=htmlspecialchars($_POST['new_pwd']);
        $req = $bdd->prepare('UPDATE users SET password = ? WHERE numero = ?');
        $req->execute(array($nwpwd, $_POST['num']));
        $req->closeCursor();
        //termine le traitement de la requête
        header ('location: ../gestion.php'); //on recharge la page moncompte
 }
else {
        echo 'Les variables du formulaire ne sont pas déclarées.';
    }
?>