<?php
    session_start ();
try
    {
        $bdd = new PDO('mysql:host=localhost;dbname=couztoujours', 'root', 'root');
    }
catch (Exception $e) // Si erreur
    {
            die('Erreur : ' . $e->getMessage());
    }

    // on teste si nos variables sont définies
if (isset($_SESSION['login']) && isset($_POST['nouveau'])) {

        //changement du pwd correspondant.
        $req = $bdd->prepare('UPDATE users SET password = ? WHERE name = ?');
        $req->execute(array($_POST['nouveau'], $_SESSION['login']));
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