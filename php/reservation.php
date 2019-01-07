<?php
  
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
if (isset($_POST['nom']) && isset($_POST['debut']) && isset($_POST['fin']) && isset($_POST['prive']) && isset($_POST['package']) && isset($_POST['ptitdub']) && isset($_POST['grosdub']) && isset($_POST['pleintarif']) && isset($_POST['tarifreduit']) && isset($_POST['enfants']) && isset($_POST['login']) && isset($_POST['official'])) {
/*  echappement des caractères html*/
    $nom = htmlspecialchars($_POST['nom']);

    echo "Résumé de la réservation :  ";
    echo $nom . "<br />";
    echo "Du " . convertdate($_POST['debut']) . "au " .  convertdate($_POST['fin']) . " " ;
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
        if($_POST['official'] == 0){
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
            echo "Le prix total du séjour est de $prix euros. ";
        }
        else {
            $prive = 0;
            $package = 1;
            $prix = 0;
        }
        
        //enregistrement dans la base de données
        $req = $bdd->prepare('INSERT INTO reservation(username, nom, debut, fin, nbptitdub, nbgrosdub, nbvis_pt, nbvis_tr, nbvis_enf, prive, officiel, package, prix, date_resa) VALUES(:username, :nom, :debut, :fin, :nbptitdub, :nbgrosdub, :nbvis_pt, :nbvis_tr, :nbvis_enf, :prive, :officiel, :package, :prix, :date_resa)');

        $req->execute(array(
            'username' => $_POST['login'],
            'nom' => $nom,
            'debut' => $_POST['debut'],
            'fin' => $_POST['fin'],
            'nbptitdub' => $_POST['ptitdub'],
            'nbgrosdub' => $_POST['grosdub'],
            'nbvis_pt' => $_POST['pleintarif'],
            'nbvis_tr' => $_POST['tarifreduit'],
            'nbvis_enf' => $_POST['enfants'],
            'prive' => $prive,
            'officiel' => $_POST['official'],
            'package' => $package,
            'prix' => $prix,
            'date_resa' => date("Y-m-d H:i:s")
        ));

        echo '<br /> <a href="../LesMargots.php" title="Retour"> Ok !</a><br />';
    }
}
else {
        echo 'Les variables du formulaire ne sont pas déclarées.';
    }
?>