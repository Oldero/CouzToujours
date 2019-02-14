<?php
    // résultat du login
    include("../doctor/bdd.php");
    // on teste si nos variables sont définies
if (isset($_POST['login']) && isset($_POST['pwd'])) {
    //Je choisis le champ login
    $reponse = $bdd->query('SELECT * FROM users'); // Je choisis de la base de données users le champ name
    $login = htmlspecialchars($_POST['login']);
    $password = htmlspecialchars($_POST['pwd']);
    //Je vérifie tout mes champs logins avec password_verify
    while ($donnees = $reponse->fetch()) 
    {
        if ($login == $donnees['name'] AND password_verify($password,$donnees['password'])) 
        {// dans ce cas, tout est ok, on peut démarrer notre session
            //on met à jour la date de dernière connexion
            $req=$bdd->prepare('UPDATE users SET last_co=? WHERE numero=?');
            $req->execute(array(date("Y-m-d H:i:s"),$donnees['numero']));
            $req->closeCursor();
            // on démarre la session
            session_start();
            // on enregistre les paramètres de notre visiteur comme variables de session
            $_SESSION['login'] = $login;
            $_SESSION['pwd'] = $password;
            $_SESSION['type'] = $donnees['type'];
            $_SESSION['ca'] = $donnees['ca'];
            $_SESSION['bureau'] = $donnees['bureau'];
            $_SESSION['admin'] = $donnees['admin'];
            $_SESSION['cotiz'] = $donnees['cotiz'];
            $_SESSION['prenom'] = $donnees['prenom'];
            $_SESSION['nom'] = $donnees['nom'];
            $_SESSION['tribu'] = $donnees['tribu'];
            $_SESSION['we_offert'] = $donnees['we_offert'];
            // on redirige notre visiteur vers l'accueil
            header('location: ../body/Accueil.php');
        }
    }
        //Login inexistant
        // Le visiteur n'a pas été reconnu comme étant membre de notre site. On utilise alors un petit javascript lui signalant ce fait
        echo '<body onLoad="alert(\'Membre non reconnu...\')">';
        // puis on le redirige vers la page d'accueil
        echo '<meta http-equiv="refresh" content="0;URL=../index.html">';
     //   echo 'erreur';
    $reponse->closeCursor(); // Termine le traitement de la requête
 }
else {
        echo 'Les variables du formulaire ne sont pas déclarées.';
    }
?>