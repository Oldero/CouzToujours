<?php
  
session_start ();

    include("fonctions.php");

try
    {
        $bdd = new PDO('mysql:host=localhost;dbname=couztoujours', 'root', 'root');
    }
catch (Exception $e) // Si erreur
    {
            die('Erreur : ' . $e->getMessage());
    }

// on teste si nos variables sont définies
if (isset($_POST['nom']) && isset($_POST['debut']) && isset($_POST['fin']) && isset($_POST['prive']) && isset($_POST['package']) && isset($_POST['ptitdub']) && isset($_POST['grosdub']) && isset($_POST['pleintarif']) && isset($_POST['tarifreduit']) && isset($_POST['enfants'])) {
/*  echappement des caractères html*/
    $nom = htmlspecialchars($_POST['nom']);

/*    echo "$nom <br />";
    echo $_POST['nom'];
    echo $_POST['debut'];
    echo $_POST['fin'];
    echo $_POST['prive'];
    echo $_POST['package'];
    echo $_POST['ptitdub'];
    echo $_POST['grosdub'];
    echo $_POST['pleintarif'];
    echo $_POST['tarifreduit'];
    echo $_POST['enfants'];
    echo " <br />";
*/
    echo "Résumé de la réservation : <br />";
    echo "Du " . convertdate($_POST['debut']) . "au " .  convertdate($_POST['fin']) . "<br />" ;

    if (NbJours($_POST['debut'], $_POST['fin']) <= 0 ){
        //vérification date debut<date fin
        echo '<body onLoad="alert(\'Date incorrecte ! \nLa date de début doit être antérieure à la date de fin du séjour ! \')">';
        // puis on le redirige vers la page précédente
        echo '<meta http-equiv="refresh" content="0;URL=../lesMargots.php">';
    }
    elseif ($_POST['prive'] == "Oui" && $_POST['package'] == "nuitee"){
        //vérification privé pour we ou semaine
        echo "<body onLoad=\"alert('Ce n\'est pas possible ! Seul un séjour d\'un week-end ou d\'une semaine peut être privatisé. ')\">";
        // puis on le redirige vers la page précédente
        echo '<meta http-equiv="refresh" content="0;URL=../lesMargots.php">';
    }
    elseif ((day($_POST['debut']) != "vendredi" || day($_POST['fin']) != "dimanche") && $_POST['package'] == "weekend"){
        //AMELIORATION : vérifier que week-end comprend samedi dimanche et que semaine =7jours avec fonction date()
        echo "<body onLoad=\"alert('Un week-end va du vendredi au dimanche, c\'est mieux. ')\">";
        // puis on le redirige vers la page précédente
        echo '<meta http-equiv="refresh" content="0;URL=../lesMargots.php">';
    }
    elseif ( NbJours($_POST['debut'], $_POST['fin']) != 7 && $_POST['package'] == "semaine"){
        //AMELIORATION : vérifier que week-end comprend samedi dimanche et que semaine =7jours avec fonction date()
        echo "<body onLoad=\"alert('Une semaine dure 7 nuitées, c\'est mieux. ')\">";
        // puis on le redirige vers la page précédente
        echo '<meta http-equiv="refresh" content="0;URL=../lesMargots.php">';
    }
    else{

        if($_POST['prive'] == "Oui"){
            $prive = 1;
            echo "Séjour <strong>privatisé</strong> <br />";
        }
        else {
            $prive = 0;
        }
        switch($_POST['package']){ 
        //en fonction du package choisi, calcul du nb de nuitées et du prix puis direction la bdd
            case "nuitee":
                $package = 1;
                $nbnuitee = NbJours($_POST['debut'], $_POST['fin']);
                $prix =($_POST['ptitdub']*6 + $_POST['pleintarif']*10 + $_POST['tarifreduit']*7 + $_POST['enfants']*4.5)*$nbnuitee ;
                $nbpersonnes = $_POST['ptitdub'] + $_POST['grosdub'] + $_POST['pleintarif'] + $_POST['tarifreduit'] + $_POST['enfants'];
                echo "soit $nbnuitee nuitée(s) <br />";
                echo "Nombre de personnes : $nbpersonnes <br />";
                break;

            case "weekend":
                $package = 2;
                $prix = ($prive)*200 + (1-$prive)*140;
                $nbnuitee = 2;
                echo "Pour un week-end entier <br />";
                break;

            case "semaine":
                $package = 3;
                $prix = ($prive)*450 + (1-$prive)*330;
                $nbnuitee = 6;
                echo "Pour une semaine complète <br />";
                break;
        }
        
        //enregistrement dans la base de données
        $req = $bdd->prepare('INSERT INTO reservation(username, nom, debut, fin, nbptitdub, nbgrosdub, nbvis_pt, nbvis_tr, nbvis_enf, prive, package, prix) VALUES(:username, :nom, :debut, :fin, :nbptitdub, :nbgrosdub, :nbvis_pt, :nbvis_tr, :nbvis_enf, :prive, :package, :prix)');

        $req->execute(array(
            'username' => $_SESSION['login'],
            'nom' => $nom,
            'debut' => $_POST['debut'],
            'fin' => $_POST['fin'],
            'nbptitdub' => $_POST['ptitdub'],
            'nbgrosdub' => $_POST['grosdub'],
            'nbvis_pt' => $_POST['pleintarif'],
            'nbvis_tr' => $_POST['tarifreduit'],
            'nbvis_enf' => $_POST['enfants'],
            'prive' => $prive,
            'package' => $package,
            'prix' => $prix,
        ));

        echo "Le prix total du séjour est de $prix euros. <br />";
        echo '<a href="../LesMargots.php" title="Retour"> Ok !</a><br />';
    }
}
else {
        echo 'Les variables du formulaire ne sont pas déclarées.';
    }
?>