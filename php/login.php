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
if (isset($_POST['login']) && isset($_POST['pwd'])) {

    //Je choisis le champ login
    $reponse = $bdd->query('SELECT * FROM users'); // Je choisis de la base de données users le champ name

    $login = htmlspecialchars($_POST['login']);
    $password = htmlspecialchars($_POST['pwd']);

    //Je vérifie tout mes champs logins
    while ($donnees = $reponse->fetch()) 
    {
        if ($login == $donnees['name'] AND $password == $donnees['password']) 
        {
            // dans ce cas, tout est ok, on peut démarrer notre session
            // on la démarre :)
            session_start ();
            // on enregistre les paramètres de notre visiteur comme variables de session
            $_SESSION['login'] = $login;
            $_SESSION['pwd'] = $password;
            $_SESSION['type'] = $donnees['type'];
            $_SESSION['ca'] = $donnees['ca'];
            $_SESSION['bureau'] = $donnees['bureau'];
            $_SESSION['admin'] = $donnees['admin'];
            $_SESSION['cotiz'] = $donnees['cotiz'];

            // on redirige notre visiteur vers l'accueil
            header ('location: ../Accueil.php');
        }
        else {
        //Login inexistant
        // Le visiteur n'a pas été reconnu comme étant membre de notre site. On utilise alors un petit javascript lui signalant ce fait
        echo '<body onLoad="alert(\'Membre non reconnu...\')">';
        // puis on le redirige vers la page d'accueil
        echo '<meta http-equiv="refresh" content="0;URL=../index.html">';
     //   echo 'erreur';

        }
    }
    $reponse->closeCursor(); // Termine le traitement de la requête
 }
else {
        echo 'Les variables du formulaire ne sont pas déclarées.';
    }
?>