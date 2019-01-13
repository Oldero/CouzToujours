<?php
// Page des infos du compte changement de mdp

    session_start ();
    include("../doctor/bdd.php");
    include("../php/fonctions.php");
?>
  

<!DOCTYPE html>

<html>

<head>
    <?php include("../include/style.php"); ?>
    <title>Mon compte</title>

    <SCRIPT LANGUAGE="JavaScript">
    function confirmation(param) {
        var msg = "Es-tu sûr(e) de vouloir supprimer ce truc ?";
        if (confirm(msg)){
            window.location.replace("../php/suppr_resa.php?numero="+param);
        }
    }
    </SCRIPT> 

</head>


<body>
    <?php include("../include/entete.php"); ?>
    <?php include("../include/laterale.php"); ?>
    <section class ="corps">
        Laisser un <a class="lien" href="BoiteIdees.php" title="Livre d'or / Boîte à idées">message</a> pour améliorer le site.
    <section class ="flex_formulaire">
    <div class="infos_persos">
        <?php echo "<a class='bigtitle'>Informations : </a><br />"; 
        echo 'Tu t\'appelles ' .$_SESSION['prenom'] . ' ' . $_SESSION['nom'].'.<br>';
        echo 'Connecté en tant que : <a class="italique">'.$_SESSION['login'].'</a><br>' ; 
        echo 'Type d\'adhésion :  ' ;
        switch ($_SESSION['type']) {
            case 0:
                echo "Tu peux adhérer à l'association quand tu le souhaites !";
                break;
            case 1:
                echo "Tu es <strong>P'tit Dub</strong>. C'est grâce à toi que l'association vit, merci !";
                break;
            case 2:
                echo "Tu es <strong>Gros Dub</strong>. C'est grâce à toi que l'association est si géniale. Merci !";
                break;
            case 3:
                echo "Tu es <strong>Gros Dub</strong> et à la tête d'une <strong>tribu</strong>. Que ta tribu soit longue et prospère !";
                break;
            case 4:
                echo "Tu es un <strong>parasite</strong>. Non, pardon, tu fais partie d'une tribu. ";
                break;
            case 5:
                echo "Tu es <strong>membre honoraire</strong> de cette association, grâce à ton travail fantastique pour que l'association vive aussi longtemps que possible.";
                break;
            default:
                echo "Tu as des superpouvoirs ! Sans blague, je ne sais pas, tu devrais avoir un type d'adhésion.";
        } 
        if ($_SESSION['type'] > 0) {
            echo "<br>Tu peux donc <a class=\"lien\" href=\"resa_Margots.php\" title=\"réserver les Margots\"> réserver les Margots</a> pour un séjour.";
            echo '<br>';
        }
        if ($_SESSION['ca'] == 1 && $_SESSION['admin'] == 0) {
            echo "Tu fais partie du CA";
            if ($_SESSION['bureau'] == 1) {
                echo " et même du bureau élu démocratiquement puisqu'il y a eu des votes contre";
            }
            echo ".<br>Tu as donc accès à <a class=\"lien\" href=\"gestion.php\" title=\"gestion\"> la page de gestion</a> de l'association.";
            echo '<br>';
        }

        if ($_SESSION['admin'] == 1) {
            echo "Tu es super-admin !";
            echo " Tu as donc accès à <a class=\"lien\" href=\"gestion.php\" title=\"gestion\"> la page de gestion</a> de l'association.";
            echo '<br>';
        }
        echo '<br>';
            ?>
        <div class="pwd_block">
        <div><a class="bigtitle">Changement de mot de passe :</a>
         &nbsp Attention ! Retiens-le bien !</div>
        <table>
        <form name="formulaire" action="../php/change_pwd.php" method="post">
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
    <!--De là-->
    <?php
    if($_SESSION['type']>0){
        echo '<div class="resa_moncompte">';
        $date = date("Y-m-d");
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
                    echo '</td>'; 
                        //<!-- bouton supprimer lié au script confirm plus haut. Galère d'avoir fait passer un paramètre... -->
                    echo '<td class="cell_none"><input type="Button" onClick="confirmation(' . $donnees['numero'] . ');" VALUE="Supprimer"> </td>';
                    echo "</tr> " ;
                }
            }
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
    <?php include("../include/pieddepage.php"); ?>

</body>

</html>