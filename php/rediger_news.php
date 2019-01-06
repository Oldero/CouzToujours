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
if (isset($_POST['name']) && isset ($_POST['title']) && isset($_POST['msg'])) {
        //update avec tag.
    if($_POST['title'] != ""){
        $req = $bdd->prepare('INSERT INTO news(nom, date_du_jour, titre, message) VALUES(?,?,?,?)');
        $req->execute(array($_POST['name'], date("Y-m-d"), $_POST['title'], $_POST['msg']));
    }
    else{
        $req = $bdd->prepare('INSERT INTO news(nom, date_du_jour, message) VALUES(?,?,?)');
        $req->execute(array($_POST['name'], date("Y-m-d"), $_POST['msg']));
    }
    $req->closeCursor();
    //termine le traitement de la requête    
    header ('location: ../Accueil.php'); //on recharge la page moncompte
 }
else {
        echo 'Les variables du formulaire ne sont pas déclarées.';
    }
?>