<?php
//page php pour effectuer les modifs de bdd par gestion
    
    include("../doctor/bdd.php");

    // on teste si nos variables sont définies
if (isset($_POST['name']) && isset ($_POST['title']) && isset($_POST['msg'])) {
        //update avec tag.
    $title = htmlspecialchars($_POST['title']);
    $msg = htmlspecialchars($_POST['msg']);
    if($_POST['title'] != ""){
        $req = $bdd->prepare('INSERT INTO news(nom, date_du_jour, titre, message) VALUES(?,?,?,?)');
        $req->execute(array($_POST['name'], date("Y-m-d"), $title, $msg));
    }
    else{
        $req = $bdd->prepare('INSERT INTO news(nom, date_du_jour, message) VALUES(?,?,?)');
        $req->execute(array($_POST['name'], date("Y-m-d"), $msg));
    }
    $req->closeCursor();
    //termine le traitement de la requête    
    header ('location: ../body/Accueil.php'); //on recharge la page moncompte
 }
else {
        echo 'Les variables du formulaire ne sont pas déclarées.';
    }
?>