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
    session_start ();

if (isset($_POST['ancien']) && isset($_POST['nouveau']) && isset($_POST['nouveau_test'])) {
    if ($_POST['nouveau'] != $_POST['nouveau_test']) {
        // mauvaise répétition, à reconfirmer
//        echo '<body onLoad="alert(\'Tu n\'as pas tapé le même mot de passe.\')">';
        // puis on le redirige vers la page d'accueil
//        echo '<meta http-equiv="refresh" content="0;URL=monCompte.php">';
        echo 'nope !';
    }
//    else {
//        if 
//    }
}
else {
        echo 'Les variables du formulaire ne sont pas déclarées.';
    }
?>