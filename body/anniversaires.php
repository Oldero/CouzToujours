<?php
// Les Margots : histoire, réservation, photos, projets...
    
    session_start ();
    include("../doctor/bdd.php");
    include("../php/fonctions.php");
//--------------------------------------------------- include calendar.class.php
    require_once('../calendar/calendar.class.php');
//--------------------------------------- check $_GET for date passed from links
    $date = ( isset($_GET['date']) )? $_GET['date'] : date("Y-m-d");

//--------------------------------------------------- initialize calendar object
/*
Dynamic Date
*/
    $calendar = new Calendar($date);
    if(!isset($_GET['date'])){
        $date = date("Y-m-d");
    }
//création de l'array anniv et null pour éviter l'erreur warning.
    $array_null = array(array('0000-00-00', 0, " ",0));
    $anniv = $array_null;
?>

<!DOCTYPE html>

<html>

<head>
    <?php include("../include/style.php"); ?>
    <title>Anniversaires</title>
 </head>


<body>

    <?php include("../include/entete.php"); ?>
    <?php include("../include/laterale.php"); ?>
    <section class="corps">
    <section class="page_deuxcolonnes">
    <section class="colonne_droite">

    </section>
    <section class="colonne_gauche">
        <!--<table class="carnet_du_jour">
            <tr><td colspan=2 class="big">Anniversaires</td></tr>
        </table>-->
        <?php
        // On récupère tout le contenu de la table anniversaires
        $reponse = $bdd->query('SELECT * FROM anniversaires');

        // On affiche chaque entrée une à une
        while ($donnees = $reponse->fetch()){
            //infobulle(date, rien, noms en chaîne, rien)
            if (!is_null($donnees['quand'])){
                $naissance = explode("-",$donnees['quand']);
                $age = date("Y") - $naissance[0];
                if ($naissance[1]<=date("m") && $naissance[2]<=date("d")) {
                    $age++;
                }
                $date_to_add = date("Y") . '-' . $naissance[1] . '-' . $naissance[2];
                //si pas déjà dans l'array en utilisant la double fonction, le rajouter, sinon ajouter nom de personne
                if(!in_array($date_to_add,array_column($anniv, 0))){
                    $anniv[] = array($date_to_add, 0, $donnees['qui'], 0);
                }
                else{
                    $key = array_search($date_to_add, array_column($anniv, 0));
                    $anniv[$key][2] .= "\n" . $donnees['qui'];
                }
            }
        }
        $reponse->closeCursor(); // Termine le traitement de la requête
    //tableaux d'événements du calendrier
    $calendar->info_official = $anniv;
    $calendar->info_normal = $array_null;
    $calendar->info_private = $array_null;
    $calendar->link_days = FALSE;
    $calendar->mark_passed = FALSE;
    //-------------------------------------------------------------- output calendar -> on peut rajouter la classe ?
    echo '<div class="almanach">';
    echo ("<ol id=\"year\">\n");
    for($i=1;$i<=12;$i++){
        echo ("<li class =\"month\">");
        echo ($calendar->output_calendar($calendar->year, $i));
        echo ("</li>\n");
    }
    //Légende
    echo ("</ol>");
    ?> 
    </div>
    </section>
    </section>
    <?php include("../include/pieddepage.php"); ?>
</body>

</html>