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
//affichage de l'aide
    if (isset($_GET['help'])) {$help = $_GET['help'];}
    else {$help = 0;}
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
    <section class="page_deuxcolonnes">
    <section class="colonne_droite">
        <table class="formulaire_resa">
            <tr><td class="underlined" colspan=4>Réservation des Margots</td></tr>
            <form method="post" action="../php/reservation.php">
                <tr><td colspan=4>
                    <?php if ($help) {echo '<span class="span_help" span_label="Renseigne ici le but du séjour."><img src="../img/help.ico" height=22px width=22px></span>';}?>
                    <label for="nom">Nom de la réservation : </label> <input type="text" name="nom" id="nom" value= "Séjour pépère" required /></td></tr>
                <tr><td colspan=4>
                    <!-- min (date de fin) = today -->
                    <?php if ($help) {echo '<span class="span_help" span_label="Attention, la date de fin doit être ultérieure à la date de début. Pour Safari : s\'assurer du format AAAA-MM-JJ, par exemple : 2019-02-29"><img src="../img/help.ico" height=22px width=22px></span>';} ?>
                    <label for="debut">Date de début :</label> <?php echo '<input type="date" name="debut" id="debut" placeholder="AAAA-MM-JJ" value="' . $date . '" required />
                    <label for="fin"> &nbsp &nbsp Date de fin : </label><input type="date" name="fin" id="fin" placeholder="AAAA-MM-JJ" value="' . $fin_defaut . '" min="' . date("Y-m-d") . '"required />'; ?>
                </td></tr>

                <tr><td colspan=4>
                    <?php if ($help) {echo '<span class="span_help" span_label="Le pack WE correspond à  un maximum de 2 nuitées, le pack semaine à un maximum de 7 nuitées, au tarif fixé ci-dessous qui ne dépend pas du nombre de personnes."><img src="../img/help.ico" height=22px width=22px></span>';}?>
                    Pour quel package ?
                    <input type="radio" name="package" value="nuitee" id="nuitee" checked /> <label for="nuitee">À la nuitée</label> &nbsp &nbsp
                    <input type="radio" name="package" value="weekend" id="weekend" /> <label for="weekend">Week-end entier</label> &nbsp &nbsp
                    <input type="radio" name="package" value="semaine" id="semaine" /> <label for="semaine">Toute la semaine !</label>
                </td></tr>
                <tr><td colspan=4>
                    <?php if ($help) {echo '<span class="span_help" span_label="Privatiser le séjour pour un nombre illimité de personnes. Seules les réservations au WE ou à la semaine peuvent être privatisées. Voir tarifs ci-dessous."><img src="../img/help.ico" height=22px width=22px></span>';}?>
                    Est-ce un séjour privatisé ? 
                    <input type="radio" name="prive" value="Non" id="Non" checked /> <label for="Non">Non</label> &nbsp &nbsp
                    <input type="radio" name="prive" value="Oui" id="Oui" /><label for="Oui">Oui</label>
                </td></tr>
                <?php 
                    if ($_SESSION['type'] >= 2 && $_SESSION['type'] <= 4 && $_SESSION['we_offert'] == 1){
                        echo '<tr><td colspan=4>';
                        if ($help) {echo '<span class="span_help" span_label="Avec ton adhésion Gros Dub, un séjour d\'un week-end est offert. Coche ici si tu veux l\'utiliser."><img src="../img/help.ico" height=22px width=22px></span>';}
                        echo 'Utiliser le bon WE offert : 
                            <input type="radio" name="we" value="Non" id="we_Non" checked /> <label for="we_Non">Non</label> &nbsp &nbsp
                            <input type="radio" name="we" value="Oui" id="we_Oui" /><label for="we_Oui">Oui</label></td></tr>';
                    }
                    else {
                        echo '<input type="hidden" name="we" value="Non">';
                    }
                ?>
                <tr><td colspan=4 class="seconde_partie"></td></tr>
                <tr><td colspan=4>
                    <?php if ($help) {echo '<span class="span_help" span_label="Si le séjour est non privatisé, renseigne ici le nombre de personnes qui seront présentes, afin de calculer le prix du séjour, de prévoir l\'occupation et de tenir des statistiques à jour. Essaie d\'être exhaustif."><img src="../img/help.ico" height=22px width=22px></span>';}?>
                    Si non privatisé, pour combien de personnes ? </td></tr>
                    <tr><td colspan=2>
                        <?php if ($help) {echo '<span class="span_help" span_label="Dans cette colonne, renseigne le nombre d\'occupants adhérents de l\'association ou d\'enfants de tribu."><img src="../img/help.ico" height=22px width=22px></span>';}?>
                        <a class="underlined">Adhérents</a> :</td><td colspan=2>
                            <?php if ($help) {echo '<span class="span_help" span_label="Dans cette colonne, renseigne le nombre de non adhérents de l\'association. Le tarif réduit s\'applique aux titulaires du RSA ou aux étudiants sans revenus."><img src="../img/help.ico" height=22px width=22px></span>';}?>
                            <a class="underlined">Visiteurs</a> :</td></tr>
                    <tr><td class="justify_right"><label for="ptitdub">Nombre de P'tit Dub :</label></td><td><input type="number" name="ptitdub" id="ptitdub" value=0 min="0" max="64"/></td>
                    <td class="justify_right"><label for="pleintarif">Plein tarif :</label></td><td><input type="number" name="pleintarif" id="pleintarif" value=0 min="0" max="64"/></td></tr>
                    <tr><td class="justify_right"><label for="grosdub">Nombre de Gros Dub :</label></td><td><input type="number" name="grosdub" id="grosdub" value=0 min="0" max="64"/></td>
                        <td class="justify_right"><label for="tarifreduit">Tarif réduit :</label></td><td><input type="number" name="tarifreduit" id="tarifreduit" value=0 min="0" max="64"/></td></tr>
                    <tr></tr>
                    <tr><td class="justify_right"><label for="adh_enfants">Enfants de plus de 7 ans :</label></td><td><input type="number" name="adh_enfants" id="adh_enfants" value=0 min="0" max="64"/></td>
                    <td class="justify_right"><label for="enfants"> Enfants de plus de 7 ans :</label></td><td><input type="number" name="enfants" id="enfants" value=0 min="0" max="64"/></td></tr>
                    <tr><td class="justify_right"><label for="adh_moins7">Enfants de moins de 7 ans :</label></td><td><input type="number" name="adh_moins7" id="adh_moins7" value=0 min="0" max="64"/></td>
                    <td class="justify_right"><label for="vis_moins7">&nbsp Enfants de moins de 7 ans :</label></td><td><input type="number" name="vis_moins7" id="vis_moins7" value=0 min="0" max="64"/></td></tr>
                <?php echo '<input type="hidden" name="login" value="' . $_SESSION['prenom'] . ' ' . $_SESSION['nom'] . '">'; ?>
                    <input type="hidden" name="official" value=0>
                <tr><td colspan=4><input type="submit" value="Réserver les Margots" /></td></tr>
            </form>
        </table>
        <div class=tableau_noir>
        <table class="tarifs">
            <tr><th colspan=2>Tarifs des nuitées 
                <?php if ($help) {echo '<span class="span_help" span_label="Tarifs en vigueur."><img src="../img/help.ico" height=22px width=22px></span>';}?>
                </th></tr>
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
    <section class="colonne_gauche">
        <?php if ($help){echo '<a href="resa_Margots.php" class="aide">Masquer l\'aide</a>';}
            else {echo '<a href="resa_Margots.php?help=1" class="aide">Afficher l\'aide</a>';}
        ?>
        <!-- Tableau de résumé-->
        <table class="resume_resa">
            <?php
                echo "<tr><td class=\"cell_none\" colspan=2><a class=\"underlined\">Réservations à venir :";
                if ($help) {echo '<span class="span_help" span_label="Résumé des prochaines réservations."><img src="../img/help.ico" height=22px width=22px></span>';}
                if ($help) {echo '<span class="span_help" span_label="Pour supprimer une réservation, rends-toi sur la page &#34mon Compte&quot."><img src="../img/help.ico" height=22px width=22px></span>';}
                if ($help) {echo '<span class="span_help" span_label="Il est encore impossible de modifier une réservation autrement qu\'en la supprimant et en la recréant."><img src="../img/help.ico" height=22px width=22px></span>';}
                echo "</a></tr>";
                // On récupère tout le contenu de la table reservation
                $reponse = $bdd->query('SELECT * FROM reservation ORDER BY debut');

                // On affiche chaque entrée une à une
                while ($donnees = $reponse->fetch()){
                        $nb_adultes = $donnees['nbptitdub'] + $donnees['nbgrosdub'] + $donnees['nbvis_pt'] + $donnees['nbvis_tr'];
                        $nb_enfants = $donnees['nbvis_enf'] + $donnees['nbvis_toddler'] + $donnees['nb_adh_plus7'] + $donnees['nb_adh_toddler'];
                        $nb_total = $nb_adultes + $nb_enfants;
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
                                echo "- pour " . $nb_total . " personne";
                                if ($nb_total > 1) {echo "s";}
                                echo " ";
                            }
                        echo "<br />(par " . $donnees['username'] . " le " . convertdate($donnees['date_resa']) . ")" ;
                        echo'</td>';
                        echo "</tr> " ;
                        //mise dans les tableaux highlighted et infobulle 
                        //le date(strotime) est peut-être évitable mais j'ai pas trouvé mieux.)
                        if($donnees['prive'] == 1){
                            $date_to_add = $donnees['debut'];
                            while ($date_to_add <= $donnees['fin']) {
                                $evenement_prive[] = array($date_to_add, $nb_total, $donnees['nom'] . " (" . $donnees['username'] . ")", $nb_enfants);
                                $date_to_add = date("Y-m-d",strtotime($date_to_add . " +1 day"));
                             } 
                        }
                        elseif($donnees['officiel'] == 1){
                            $date_to_add = $donnees['debut'];
                            while ($date_to_add <= $donnees['fin']) {
                                $evenement_officiel[] = array($date_to_add, $nb_total, $donnees['nom'], $nb_enfants);
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
                                    $evenement_normal[] = array($date_to_add, $nb_total, $donnees['nom'] . " (" . $donnees['username'] . ")", $nb_enfants);
                                }
                                else{
                                    $key = array_search($date_to_add, array_column($evenement_normal, 0));
                                    $evenement_normal[$key][1] += $nb_total;
                                    $evenement_normal[$key][2] .= "\n" . $donnees['nom']. " (" . $donnees['username'] . ")";
                                    $evenement_normal[$key][3] += $nb_enfants;
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
    echo '<div class="almanach">';
    if ($help) {echo '<span class="span_help" span_label="Calendrier des réservations à venir. La couleur indique le type de réservation ou le taux d\'occupation."><img src="../img/help.ico" height=22px width=22px></span>';}
    if ($help) {echo '<span class="span_help" span_label="En pointant sur une case, tu peux voir quelles sont les réservations en cours."><img src="../img/help.ico" height=22px width=22px></span>';}
    if ($help) {echo '<span class="span_help" span_label="En cliquant sur une case, tu peux sélectionner une date pour commencer ta réservation."><img src="../img/help.ico" height=22px width=22px></span>';}
    if ($help) {echo '<span class="span_help" span_label="Pour info, les réservations sont à la nuitée. Aussi, tu peux sélectionner la date de début ou de fin d\'une réservation privatisée."><img src="../img/help.ico" height=22px width=22px></span>';}
    if ($help) {echo '<span class="span_help" span_label="Pour supprimer une réservation, rends-toi sur la page &#34Mon compte&quot."><img src="../img/help.ico" height=22px width=22px></span>';}
    if ($help) {echo '<span class="span_help" span_label="Il est encore impossible de modifier une réservation autrement qu\'en la supprimant et en la recréant."><img src="../img/help.ico" height=22px width=22px></span>';}
    echo ("<ol id=\"year\">\n");
    for($i=(date("m"));$i<=12;$i++){
        echo ("<li class =\"month\">");
        echo ($calendar->output_calendar($calendar->year, $i));
        echo ("</li>\n");
    }
    //Complément avec l'année d'après
    for($i=1;$i<(date("m"));$i++){
        echo ("<li class =\"month\">");
        echo ($calendar->output_calendar($calendar->year+1, $i));
        echo ("</li>\n");
    }
    //Légende
    echo ("</ol>");
    ?> 
    <table class="legende">
        <tbody>
        <tr><td><a class="underlined">Légende</a> :</td>
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
    </section>
    </section>
    <?php include("../include/pieddepage.php"); ?>
</body>

</html>