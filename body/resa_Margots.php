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
    $fin_defaut = date("Y-m-d",strtotime($date . " +2 day"));
//création de l'array evenement normal pour éviter l'erreur warning.
    $evenement_normal = [];
//limiteur de personnes pour couleur de case des événements normaux
    $limiteur = 8;
?>

<!DOCTYPE html>

<html>

<head>
    <?php include("../include/style.php"); ?>
    <title>Réservation des Margots</title>
 </head>


<body>

    <?php include("../include/entete.php"); ?>
    <?php include("../include/laterale.php"); ?>
    <section class="corps">
    <section class="flex_formulaire">
        <table class="formulaire_resa">
            <tr><td class="underlined" colspan=2>Réservation des Margots</td></tr>
            <form method="post" action="../php/reservation.php">
                <tr><td colspan=2>
                    <label for="nom">Nom de la réservation : </label> <input type="text" name="nom" id="nom" value= "Séjour pépère" required />
                </td></tr>
                <tr><td colspan=2>
                    <!-- min (date de fin) = today -->
                    <label for="debut">Date de début :</label> <?php echo '<input type="date" name="debut" id="debut" placeholder="AAAA-MM-JJ" value="' . $date . '" required />
                    <label for="fin"> &nbsp &nbsp Date de fin : </label><input type="date" name="fin" id="fin" placeholder="AAAA-MM-JJ" value="' . $fin_defaut . '" min="' . date("Y-m-d") . '"required />'; ?>
                </td></tr>

                <tr><td colspan=2>
                    Pour quel package ?
                    <input type="radio" name="package" value="nuitee" id="nuitee" checked /> <label for="nuitee">À la nuitée</label> &nbsp &nbsp
                    <input type="radio" name="package" value="weekend" id="weekend" /> <label for="weekend">Week-end entier</label> &nbsp &nbsp
                    <input type="radio" name="package" value="semaine" id="semaine" /> <label for="semaine">Toute la semaine !</label>
                </td></tr>
                <tr><td colspan=2>
                    Est-ce un séjour privatisé ? 
                    <input type="radio" name="prive" value="Non" id="Non" checked /> <label for="Non">Non</label> &nbsp &nbsp
                    <input type="radio" name="prive" value="Oui" id="Oui" /><label for="Oui">Oui</label>
                </td></tr>
                <?php 
                    if ($_SESSION['type'] >= 2 && $_SESSION['type'] <= 4 && $_SESSION['we_offert'] == 1){
                        echo '<tr><td colspan=2>Utiliser le bon WE offert : 
                            <input type="radio" name="we" value="Non" id="we_Non" checked /> <label for="we_Non">Non</label> &nbsp &nbsp
                            <input type="radio" name="we" value="Oui" id="we_Oui" /><label for="we_Oui">Oui</label></td></tr>';
                    }
                    else {
                        echo '<input type="hidden" name="we" value="Non">';
                    }
                ?>
                <tr><td colspan=2>
                    Si non privatisé, pour combien de personnes ? </td></tr>
                    <tr><td class="justify_right"><label for="ptitdub">Nombre de P'tit Dub :</label></td><td><input type="number" name="ptitdub" id="ptitdub" value=0 min="0" max="64"/></td></tr>
                    <tr><td class="justify_right"><label for="grosdub">Nombre de Gros Dub :</label></td><td><input type="number" name="grosdub" id="grosdub" value=0 min="0" max="64"/></td></tr>
                    <tr><td class="justify_right"><label for="pleintarif">Nombre de visiteurs plein tarif :</label></td><td><input type="number" name="pleintarif" id="pleintarif" value=0 min="0" max="64"/></td></tr>
                    <tr><td class="justify_right"><label for="tarifreduit">Nombre de visiteurs tarif réduit (RSA, étudiants sans revenus) :</label></td><td><input type="number" name="tarifreduit" id="tarifreduit" value=0 min="0" max="64"/></td></tr>
                    <tr><td class="justify_right"><label for="enfants">Nombre de visiteurs de plus de 7 ans et de moins de 18 ans :</label></td><td><input type="number" name="enfants" id="enfants" value=0 min="0" max="64"/></td></tr>
                <?php echo '<input type="hidden" name="login" value="' . $_SESSION['prenom'] . ' ' . $_SESSION['nom'] . '">'; ?>
                    <input type="hidden" name="official" value=0>
                <tr><td colspan=2><input type="submit" value="Envoyer" /></td></tr>
            </form>
        </table>
        <!-- Tableau de résumé-->
        <table class="resume_resa">
            <?php
                echo "<tr><td class=\"cell_none\" colspan=2><a class=\"underlined\">Réservations à venir :</a></tr>";
                // On récupère tout le contenu de la table reservation
                $reponse = $bdd->query('SELECT * FROM reservation ORDER BY debut');

                // On affiche chaque entrée une à une
                while ($donnees = $reponse->fetch())
                    {
                        $nb_total = $donnees['nbptitdub'] + $donnees['nbgrosdub'] + $donnees['nbvis_pt'] + $donnees['nbvis_tr'] + $donnees['nbvis_enf'];
                    echo '<tr>';
                    //n'écrire que les séjours dont date de fin ultérieure à aujourd'hui
                    if($donnees['fin'] > date("Y-m-d")){ ?>
                        <td class="cell_none">
                        <?php echo $donnees['nom'] . " : du " . convertdate($donnees['debut']) . "au " . convertdate($donnees['fin']);
                            if ($donnees['prive'] == 1){ 
                                echo "- Séjour privatisé ";
                            }
                            elseif ($donnees['officiel'] == 1){
                                echo "- Événement Couz'Toujours ";
                            }
                            else { 
                                echo "- pour " . $nb_total . " personnes ";
                            }
                        echo "<br />(par " . $donnees['username'] . " le " . convertdate($donnees['date_resa']) . ")" ;
                        echo'</td>';
                        echo "</tr> " ;
                        //mise dans les tableaux highlighted et infobulle 
                        //le date(strotime) est peut-être évitable mais j'ai pas trouvé mieux.)
                        if($donnees['prive'] == 1){
                            $date_to_add = $donnees['debut'];
                            while ($date_to_add <= $donnees['fin']) {
                                $evenement_prive[] = array($date_to_add, $nb_total, $donnees['nom'] . " (" . $donnees['username'] . ")");
                                $date_to_add = date("Y-m-d",strtotime($date_to_add . " +1 day"));
                             } 
                        }
                        elseif($donnees['officiel'] == 1){
                            $date_to_add = $donnees['debut'];
                            while ($date_to_add <= $donnees['fin']) {
                                $evenement_officiel[] = array($date_to_add, $nb_total, $donnees['nom']);
                                $date_to_add = date("Y-m-d",strtotime($date_to_add . " +1 day"));
                             } 
                        }
                        //le plus dur : création des trois classes dépendant du nb de personnes -> passage par un tableau intermédiaire.
                        //création aussi du tableau d'infobulles.
                        //infobulle(date, nb_de_personnes, events en chaine)
                        else{
                            $date_to_add = $donnees['debut'];
                            while ($date_to_add <= $donnees['fin']) {
                                //si pas déjà dans l'array en utilisant la double fonction, le rajouter, sinon ajouter $nb_total de personnes 
                                if(!in_array($date_to_add,array_column($evenement_normal, '0'))){
                                    $evenement_normal[] = array($date_to_add, $nb_total, $donnees['nom'] . " (" . $donnees['username'] . ")");
                                }
                                else{
                                    $key = array_search($date_to_add, array_column($evenement_normal, 0));
                                    $evenement_normal[$key][1] += $nb_total;
                                    $evenement_normal[$key][2] .= "\n" . $donnees['nom']. " (" . $donnees['username'] . ")";
                                }
                                $date_to_add = date("Y-m-d",strtotime($date_to_add . " +1 day"));
                            } 
                        }
                        }
                    }

                $reponse->closeCursor(); // Termine le traitement de la requête
            ?>
            <tl><td class="separateur"></td></tl>
        </table>
    <?php

    //tableaux d'événements du calendrier
    $calendar->limit = $limiteur;
    $calendar->info_private = $evenement_prive;
    $calendar->info_official = $evenement_officiel;
    $calendar->info_normal = $evenement_normal;
    //-------------------------------------------------------------- output calendar -> on peut rajouter la classe ?
    echo ("<div class=\"almanach\"><ol id=\"year\">\n");
    for($i=1;$i<=12;$i++){
        echo ("<li class =\"month\">");
        echo ($calendar->output_calendar($calendar->year, $i));
        echo ("</li>\n");
    }
    //Légende
    echo ("</ol>");
    ?> 
    <table class="legende">
        <tbody>
        <tr><td class="underlined">Légende :</td>
        <td class ="official"></td>
            <td class ="legende_element">Événement Couz'Toujours</td>
        <td class ="privatised"></td>
            <td class ="legende_element">Séjour privatisé</td>
        </tr><tr><td></td><td class ="red_light"></td>
            <td class ="legende_element">Moins de <?php echo $limiteur; ?> personnes</td>
        <td class ="red_medium"></td>
            <td class ="legende_element">Entre <?php echo $limiteur; ?> et <?php echo 2*$limiteur; ?> personnes</td>
        <td class ="red_dark"></td>
            <td class ="legende_element">Plus de <?php echo 2*$limiteur; ?> personnes</td>
        </tr>
        </tbody>
    </table>
    </div>
    <div class=tableau_noir>
        <table class="tarifs">
            <tr><th colspan=2>Tarifs des nuitées</th></tr>
            <tr><td><br /></td></tr>
            <tr><td>P'tit Dub ..................................</td><td>6€ / nuitée</td></td></tr>
            <tr><td><br /></td></tr>
            <tr><td colspan=2>Visiteurs</td></tr>
            <tr><td>Plein tarif ..............................</td><td>10€ / nuitée</td></tr>
            <tr><td>Tarif réduit ............................</td><td>7€ / nuitée</td></tr>
            <tr><td colspan=2>&nbsp &nbsp (RSA, étudiant sans revenu)</td><td></td></tr>
            <tr><td>Enfants / + de 7 ans ..............</td><td>5€ / nuitée</td></tr>
            <tr><td><br /></td></tr>
            <tr><td>Package week-end ..................</td><td>140€</td></tr>
            <tr><td>&nbsp &nbsp &nbsp &nbsp &nbsp privatisé ........................</td><td>200€</td></tr>
            <tr><td><br /></td></tr>
            <tr><td>Package semaine ....................</td><td>330€</td></tr>
            <tr><td>&nbsp &nbsp &nbsp &nbsp &nbsp privatisé ........................</td><td>450€</td></tr>
        </table>
    </div>
    </section>

    <?php include("../include/pieddepage.php"); ?>
</body>

</html>