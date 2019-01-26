<?php
// Page des infos du compte changement de mdp

    session_start ();
    include("../doctor/bdd.php");
    include("../php/fonctions.php");

//réponse du formulaire suppression de résa
    if (isset($_POST['param']) && isset($_POST['tribu']) && isset($_POST['we_off'])) {
        //delete avec tag numero.
        $req = $bdd->prepare('DELETE FROM reservation WHERE numero = ?');
        $req->execute(array($_POST['param']));
        $req->closeCursor();
        if ($_POST['we_off'] == 1) {
            //remise à 1 du WE offert de la tribu
            if ($_POST['tribu'] != NULL) {
                $req = $bdd->prepare('UPDATE users SET we_offert = 1 WHERE tribu = ?');
                $req->execute(array($_POST['tribu']));
                $req->closeCursor();
            }
            else{
                //remise à 1 du WE offert du GROS Dub solo
                $req = $bdd->prepare('UPDATE users SET we_offert = 1 WHERE name = ?');
                $req->execute(array($_SESSION['login']));
                $req->closeCursor();
            }
            $_SESSION['we_offert'] = 1;
        }
        //termine le traitement de la requête
        header ('location: ../body/monCompte.php'); //on recharge la page moncompte
    }
//réponse du formulaire changement de pwd
    if (isset($_POST['user']) && isset($_POST['nouveau'])) {
    //ici crypter.
        $new_pwd = password_hash($_POST['nouveau'], PASSWORD_DEFAULT);
        //changement du pwd correspondant.
        $req = $bdd->prepare('UPDATE users SET password = ? WHERE name = ?');
        $req->execute(array($new_pwd, $_POST['user']));
        $req->closeCursor();
        //changement de valeur de variable de session
        $_SESSION['pwd'] = $_POST['nouveau'];
        //termine le traitement de la requête    
        header ('location: ../body/monCompte.php'); //on recharge la page moncompte
    
    }
?>
  

<!DOCTYPE html>

<html>

<head>
    <?php include("../include/style.php"); ?>
    <title>Mon compte</title>
</head>


<body>
    <?php include("../include/entete.php"); ?>
    <?php include("../include/laterale.php"); ?>
    <section class ="corps">
    <section class ="page_deuxcolonnes">
    <section class="ensemble_gauche">
        Laisser un <a class="lien" href="BoiteIdees.php" title="Livre d'or / Boîte à idées">message</a> pour améliorer le site.
    <div class="infos_persos">
        <?php echo "<a class='bigtitle'>Informations : </a><br />"; 
        echo 'Tu t\'appelles ' .$_SESSION['prenom'] . ' ' . $_SESSION['nom'].'.<br>';
        echo 'Connecté en tant que : <a class="italique">'.$_SESSION['login'].'</a><br>' ; 
        echo 'Type d\'adhésion :  ' ;
        switch ($_SESSION['type']) {
            case 0:
                echo "Tu peux <a class=\"lien\" href=\"adhesion.php\" title=\"Adhérer ou faire un don\">adhérer ou faire un don</a> à l'association quand tu le souhaites !";
                break;
            case 1:
                echo "Tu es <strong>P'tit Dub</strong>. C'est grâce à toi que l'association vit, merci !";
                break;
            case 2:
                echo "Tu es <strong>Gros Dub</strong>. C'est grâce à toi que l'association est si géniale. Merci !";
                break;
            case 3:
            //case 3 and 4 : recherche des noms associés dans la tribu.
                echo "Tu es <strong>Gros Dub</strong>";
                $recherche = $bdd->prepare('SELECT nom,prenom FROM users WHERE (tribu = ? AND name != ?) ORDER BY type');
                $recherche->execute(array($_SESSION['tribu'], $_SESSION['login']));
                if($_SESSION['tribu'] != ""){
                    echo " et à la tête de la <strong>tribu</strong> " . $_SESSION['tribu'] . " avec " ;
                    $tribu = $recherche->fetch();
                    echo $tribu['prenom'] . " " . $tribu['nom'];
                    if($tribu = $recherche->fetch()){
                        echo " et dont fait partie " . $tribu['prenom'] . " " . $tribu['nom'];
                    }
                    echo ".";
                }
                else{echo " et à la tête d'une tribu.";}
                echo " Que ta tribu soit longue et prospère !";
                $recherche->closeCursor();
                break;
            case 4:
                $recherche = $bdd->prepare('SELECT nom,prenom FROM users WHERE (tribu = ? AND name != ?)');
                $recherche->execute(array($_SESSION['tribu'], $_SESSION['login']));
                echo "Tu es un <strong>parasite</strong>. Non, pardon, tu fais partie";
                if($_SESSION['tribu'] != ""){
                    echo" de la tribu " . $_SESSION['tribu'] . " chapeautée par ";
                    $tribu = $recherche->fetch();
                    echo $tribu['prenom'] . " " . $tribu['nom'] . " et ";
                    $tribu = $recherche->fetch();
                    echo $tribu['prenom'] . " " . $tribu['nom'] . ". ";}
                else{echo " d'une tribu.";}
                $recherche->closeCursor();
                break;
            case 5:
                echo "Tu es <strong>membre honoraire</strong> de cette association, grâce à ton travail fantastique pour que l'association vive aussi longtemps que possible.";
                break;
            default:
                echo "Tu as des superpouvoirs ! Sans blague, je ne sais pas, tu devrais avoir un type d'adhésion.";
        } 
        if ($_SESSION['type'] > 0) {
            echo "<br>Tu peux donc <a class=\"lien\" href=\"resa_Margots.php\" title=\"réserver les Margots\"> réserver les Margots</a> pour un séjour. ";
        }
        if ($_SESSION['type'] >=2 && $_SESSION['type'] <=4) {
            switch ($_SESSION['we_offert']) {
                case 0:
                    echo "Tu as déjà utilisé ton pack WE offert avec l'adhésion. ";
                    break;
                case 1:
                    echo "D'ailleurs, il te reste la réservation offerte d'un pack WE à utiliser. ";
                    break;
                default:
                    break;
            }
        }
        if ($_SESSION['cotiz'] == 0 && $_SESSION['type'] > 0) {
            echo "<br>Tu n'as pas encore payé ta cotiz ! <a class=\"lien\" href=\"adhesion.php\" title=\"Adhérer ou faire un don\">C'est par ici</a> ! ";
        }
        if ( $_SESSION['type'] > 0) {
            echo "<br>Si cela t'intéresse, tu peux aussi <a class=\"lien\" href=\"adhesion.php\" title=\"Adhérer ou faire un don\">faire un don</a> à l'association.";
        }
        if ($_SESSION['ca'] == 1 && $_SESSION['admin'] == 0) {
            echo "<br>Tu fais partie du CA";
            if ($_SESSION['bureau'] == 1) {
                echo " et même du bureau élu démocratiquement puisqu'il y a eu des votes contre";
            }
            echo ". Tu as donc accès à <a class=\"lien\" href=\"gestion.php\" title=\"gestion\"> la page de gestion</a> de l'association.";
            echo '<br>';
        }

        if ($_SESSION['admin'] == 1) {
            echo "<br>Tu es super-admin !";
            echo " Tu as donc accès à <a class=\"lien\" href=\"gestion.php\" title=\"gestion\"> la page de gestion</a> de l'association.";
            echo '<br>';
        }
        echo '<br>';
            ?>
        <div class="pwd_block">
        <div><a class="bigtitle">Changement de mot de passe :</a>
         &nbsp Attention ! Retiens-le bien !</div>
        <table>
        <form name="formulaire" method="post">
        	<input type="hidden" name="courant" value = <?php echo $_SESSION['pwd']; ?>>
            <input type="hidden" name="user" value = <?php echo $_SESSION['login']; ?>>
        	<tr><td>Ton ancien mot de passe : </td><td><input type="password" name="ancien"></td></tr>
        	<tr><td>Ton nouveau mot de passe : </td><td><input type="password" name="nouveau"></td></tr>
        	<tr><td>Répète-le : </td><td><input type="password" name="nouveau_test"></td></tr>
        	<tr><td colspan=2 class="justify_center"><input type="button" value="Changer de mot de passe" onclick="if (document.formulaire.ancien.value == document.formulaire.courant.value && document.formulaire.nouveau.value == document.formulaire.nouveau_test.value && confirm('Es-tu sûr(e) de vouloir changer de mot de passe ?') ) {document.formulaire.submit();} else {alert('Mauvais mot de passe !') }"></td></tr>
        </form>
        </table>    
        </div>
    </div>
    </section>
    <section class="colonne_droite">
    <!--De là-->
    <?php
    if($_SESSION['type']>0){
        echo '<div class="resa_moncompte">';
        $nom_de_resa = $_SESSION['prenom'] . ' ' . $_SESSION['nom'];
        // On récupère tout le contenu de la table réservations
        if ($_SESSION['login'] == "admin") {
        //Pour admin, juste, toutes.
        $reponse = $bdd->prepare('SELECT * FROM reservation WHERE username = ? OR username = ? ORDER BY debut');
//      echo $nom_de_resa;
        $reponse->execute(array($nom_de_resa,"admin"));}
        else{$reponse = $bdd->prepare('SELECT * FROM reservation WHERE username = ? ORDER BY debut');
        $reponse->execute(array($nom_de_resa));}
        // On affiche chaque entrée une à une
        echo '<table class="resume_resa">';
        echo "<tr><td class=\"cell_none\" colspan=2><a class=\"underlined\">Mes réservations à venir :</a></td></tr>";
        while ($donnees = $reponse->fetch())
            {
            echo '<tr>';
            if($donnees['fin'] > date("Y-m-d")){
                echo '<td class="cell_none">';
                    echo $donnees['nom'] . " : du " . convertdate($donnees['debut']) . "au " . convertdate($donnees['fin']);
                    if ($donnees['prive'] == 1){ 
                        echo "- Séjour privatisé";
                    }
                    else { 
                        echo "- pour " . ($donnees['nbptitdub'] + $donnees['nbgrosdub'] + $donnees['nbvis_pt'] + $donnees['nbvis_tr'] + $donnees['nbvis_enf']) . " personnes";
                    } 
                    echo " - coût : " . ($donnees['prix']) . " euros";
                    if($donnees['we_gratuit'] == 1){
                        echo ' (WE offert)';
                    }
                    echo '</td>'; 
                        //<!-- bouton supprimer lié au script confirm plus haut. Galère d'avoir fait passer un paramètre... -->
                    echo '<td class="cell_none">';
                    echo '<form name="suppr" method="post" onsubmit="return confirm(\'Es-tu sûr de vouloir supprimer ce truc ?\');">';
                    echo'<input type="hidden" name="param" value=' . $donnees['numero'] .'>
                        <input type="hidden" name="tribu" value=' . $_SESSION['tribu'] .'>
                        <input type="hidden" name="we_off" value=' . $donnees['we_gratuit'] .'>
                        <input type="submit" value="Supprimer">
                        </form>
                        </td>';
                    echo "</tr> " ;
                }
            }
        $reponse->closeCursor();
            // On recommence avec la table des résas passées.
        if ($_SESSION['login'] == "admin") {
            	//Pour admin, juste, toutes.
            $reponse = $bdd->prepare('SELECT * FROM reservation WHERE username = ? OR username = ? ORDER BY debut');
//                echo $nom_de_resa;
            $reponse->execute(array($nom_de_resa,"admin"));}
        else{$reponse = $bdd->prepare('SELECT * FROM reservation WHERE username = ? ORDER BY debut');
            $reponse->execute(array($nom_de_resa));}
            // On affiche chaque entrée une à une
        echo '</table>';
        echo '<table class="resume_resa">';
        echo "<tr><td class=\"cell_none\" colspan=2><a class=\"underlined\">Mes réservations passées :</a></td></tr>";
        while ($donnees = $reponse->fetch()){
            echo '<tr>';
            if($donnees['fin'] <= date("Y-m-d")){
                echo '<td class="cell_none">';
                echo $donnees['nom'] . " : du " . convertdate($donnees['debut']) . "au " . convertdate($donnees['fin']);
                if ($donnees['prive'] == 1){ 
                    echo "- Séjour privatisé";
                }
                else { 
                    echo "- pour " . ($donnees['nbptitdub'] + $donnees['nbgrosdub'] + $donnees['nbvis_pt'] + $donnees['nbvis_tr'] + $donnees['nbvis_enf']) . " personnes";
                } 
                echo " - coût : " . ($donnees['prix']) . " euros";
                echo '</td>';
                echo "</tr> " ;
            }       
        }
        echo '</table>';
        $reponse->closeCursor(); // Termine le traitement de la requête
        echo '</div>';
    }
    ?>
    <!--à de là-->
    </section>    
    </section>
    </section>
    <?php include("../include/pieddepage.php"); ?>

</body>

</html>