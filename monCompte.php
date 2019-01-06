<!-- Page des infos du compte changement de mdp -->


<?php
    session_start ();
    include("php/fonctions.php");
    
    try
    {
        // On se connecte à MySQL
        $bdd = new PDO('mysql:host=localhost;dbname=couztoujours;charset=utf8', 'root', 'root');
    }
    catch(Exception $e)
    {
        // En cas d'erreur, on affiche un message et on arrête tout
            die('Erreur : '.$e->getMessage());
    }

?>
  

<!DOCTYPE html>

<html>

<head>

    <meta charset="utf-8" />

    <title>Mon compte</title>

    <link rel="stylesheet" href="style.css" />
    <SCRIPT LANGUAGE="JavaScript">
    function confirmation(param) {
        var msg = "Es-tu sûr(e) de vouloir supprimer ce truc ?";
        if (confirm(msg)){
            window.location.replace("php/suppr_resa.php?numero="+param);
        }
    }
    </SCRIPT> 

</head>


<body>
    <?php include("include/entete.php"); ?>
    <?php include("include/laterale.php"); ?>
    <section class ="corps">
        <?php echo "Informations : <br> "; 
        echo 'Nom :  '.$_SESSION['login'].'<br>' ; 
        echo 'Type d\'adhésion :  ' ;
        switch ($_SESSION['type']) {
            case 0:
                echo "Tu peux adhérer à l'association quand tu le souhaites !";
                break;
            case 1:
                echo "Tu es P'tit Dub. C'est grâce à toi que l'association vit, merci !";
                break;
            case 2:
                echo "Tu es Gros Dub. C'est grâce à toi que l'association est si géniale. Merci !";
                break;
            case 3:
                echo "Tu es Gros Dub et à la tête d'une tribu. Que ta tribu soit longue et prospère !";
                break;
            case 4:
                echo "Tu es un parasite. Non, pardon, tu fais partie d'une tribu. ";
                break;
            case 5:
                echo "Tu es membre honoraire de cette association, grâce à ton travail fantastique pour que l'association vive aussi longtemps que possible.";
                break;
            default:
                echo "Tu as des superpouvoirs ! Sans blague, je ne sais pas, tu devrais avoir un type d'adhésion.";
        } 
        echo '<br>';

        if ($_SESSION['ca'] == 1 && $_SESSION['admin'] == 0) {
            echo "Tu fais partie du CA";
            if ($_SESSION['bureau'] == 1) {
                echo " et même du bureau élu démocratiquement puisqu'il y a eu des votes contre";
            }
            echo ".<br>Tu as donc accès à <a href=\"gestion.php\" title=\"gestion\"> la page de gestion</a> de l'association.";
            echo '<br>';
        }

        if ($_SESSION['admin'] == 1) {
            echo "Tu es super-admin !";
            echo " Tu as donc accès à <a href=\"gestion.php\" title=\"gestion\"> la page de gestion</a> de l'association.";
            echo '<br>';
        }
        echo '<br>';
            ?>

        Changement de mot de passe : Attention ! Retiens-le bien !

        <form name="formulaire" action="php/change_pwd.php" method="post">
        	<input type="hidden" name="courant" value = <?php echo $_SESSION['pwd']; ?>>
        	Ton ancien mot de passe : <input type="password" name="ancien"><br />
        	Ton nouveau mot de passe : <input type="password" name="nouveau"><br />
        	Répète-le : <input type="password" name="nouveau_test"><br />
        	<input type="button" value="Changer de mot de passe" onclick="if (document.formulaire.ancien.value == document.formulaire.courant.value && document.formulaire.nouveau.value == document.formulaire.nouveau_test.value && confirm('sur ?') ) {document.formulaire.submit();} else {alert('Mauvais mot de passe !') }">
        </form>    
 
        <?php
            $date = date("Y-m-d");
//            echo $date;
            // Si tout va bien, on peut continuer
 
            // On récupère tout le contenu de la table réservations
            $reponse = $bdd->prepare('SELECT * FROM reservation WHERE username = ?');
            $reponse->execute(array($_SESSION['login']));

            // On affiche chaque entrée une à une
            echo '<table class="reserv">';
            echo "<tr><td class=\"cell_none\" colspan=2>Mes réservations :</td></tr>";
            while ($donnees = $reponse->fetch())
                {
                echo '<tr>';
                 if($donnees['fin'] > $date){ ?>
                    <td class="cell_none">
                    <?php echo $donnees['nom'] . " : du " . convertdate($donnees['debut']) . "au " . convertdate($donnees['fin']);
                        if ($donnees['prive'] == 1){ 
                            echo "- Séjour privatisé";
                        }
                        else { 
                            echo "- pour " . ($donnees['nbptitdub'] + $donnees['nbgrosdub'] + $donnees['nbvis_pt'] + $donnees['nbvis_tr'] + $donnees['nbvis_enf']) . " personnes";
                        } 
                        echo " - coût : " . ($donnees['prix']) . " euros" ?>
                    </td>
                        <!-- bouton supprimer lié au script confirm plus haut. Galère d'avoir fait passer un paramètre... -->
                    <td class="cell_none"><input type="Button" onClick="confirmation(<?php echo $donnees['numero'] ; ?> );" VALUE="Supprimer"> </td>

                    <?php echo "</tr> " ;
                    }
                
                }
            echo '</table>';

            $reponse->closeCursor(); // Termine le traitement de la requête
        ?>
        
    </section>
    <?php include("include/pieddepage.php"); ?>

</body>

</html>