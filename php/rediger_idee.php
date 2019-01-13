<?php
//page php pour effectuer les modifs de bdd par gestion

    include("../doctor/bdd.php");

    // on teste si nos variables sont définies
if (isset($_POST['name']) && isset($_POST['msg']) && isset($_POST['direction'])) {
    //update avec tag.
    $msg = htmlspecialchars($_POST['msg']);
    switch($_POST['direction']) {
        case "livre":
        $direct = 1;
        break;
        case "idee":
        $direct = 2;
        break;
        default:
        $direct = 0;
        break;
    }

    $req = $bdd->prepare('INSERT INTO livredor(username, type, message) VALUES(?,?,?)');
    $req->execute(array($_POST['name'], $direct, $msg));

    $req->closeCursor();
    //termine le traitement de la requête    
    header ('location: ../body/monCompte.php'); //on recharge la page moncompte
 }
else {
        echo 'Les variables du formulaire ne sont pas déclarées.';
    }
?>