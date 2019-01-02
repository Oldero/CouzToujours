<!-- Page des infos du compte changement de mdp -->


<?php
    session_start ();
?>
  

<!DOCTYPE html>

<html>

<head>

    <meta charset="utf-8" />

    <title>Mon compte</title>

    <link rel="stylesheet" href="style.css" />

</head>


<body>
    <?php include("include/entete.php"); ?>
    <?php include("include/laterale.php"); ?>

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

<!--    Changement de mot de passe : Attention ! Retiens-le bien !
    <form action="changement.php" method="post">
    Ton ancien mot de passe : <input type="password" name="ancien"><br />
    Ton nouveau mot de passe : <input type="password" name="nouveau"><br />
    Répète-le : <input type="password" name="nouveau_test"><br />
    <input type="submit" value="Changer de mot de passe">

!-->
 
    <?php include("include/pieddepage.php"); ?>

</body>

</html>