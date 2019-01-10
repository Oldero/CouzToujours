<?php
//résultat du formulaire de changement de mot de passe
//lancement de la session pour actualiser le changement de mot de passe.
    session_start ();
    include("../doctor/bdd.php");

    // on teste si nos variables sont définies
if (isset($_POST['user']) && isset($_POST['nouveau'])) {
    //ici crypter.
        $new_pwd = htmlspecialchars($_POST['nouveau']);
        //changement du pwd correspondant.
        $req = $bdd->prepare('UPDATE users SET password = ? WHERE name = ?');
        $req->execute(array($new_pwd, $_POST['user']));
        $req->closeCursor();
        //changement de valeur de variable de session
        $_SESSION['pwd'] = $_POST['nouveau'];
        //termine le traitement de la requête    
        header ('location: ../monCompte.php'); //on recharge la page moncompte
    
 }
else {
        echo 'Les variables du formulaire ne sont pas déclarées.';
    }
?>