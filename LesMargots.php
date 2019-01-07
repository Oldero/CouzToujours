<!-- Les Margots : histoire, réservation, photos, projets...
-->

<?php
    session_start ();

    include("php/fonctions.php");
//--------------------------------------------------- include calendar.class.php
    require_once('calendar/calendar.class.php');

//--------------------------------------- check $_GET for date passed from links
    $date = ( isset($_GET['date']) )? $_GET['date'] : date("Y-m-d");

//--------------------------------------------------- initialize calendar object
/*
Dynamic Date
*/
    $calendar = new Calendar($date);

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
    // Si tout va bien, on peut continuer
    //déclaration des arays de dates highlighted
    setlocale(LC_TIME, "fr_FR");

?>
  

<!DOCTYPE html>

<html>

<head>

    <meta charset="utf-8" />

    <title>Les Margots</title>

    <link rel="stylesheet" href="style.css" /> 

 </head>


<body>

    <?php include("include/entete.php"); ?>
    <?php include("include/laterale.php"); ?>
    <section class="corps">

<!--    <section class="calendar">
        <iframe name="InlineFrame1" id="InlineFrame1" style="width:690px;height:515px;" src="https://www.mathieuweb.fr/calendrier/calendrier-des-semaines.php?nb_mois=12&nb_mois_ligne=4&mois=0&an=0&langue=fr&texte_color=DDDDDD&week_color=F8F8F8&week_end_color=EDEDED&police_color=2F4544&sel=false" scrolling="no" frameborder="0" allowtransparency="true"></iframe>
    </section>
 -->           

<!--    <section class="page_resa"> -->

        <table class="formulaire_resa">
            <tr><td class="underlined" colspan=2>Réservation des Margots</td></tr>
            <form method="post" action="php/reservation.php">
                <tr><td colspan=2>
                    <label for="nom">Nom de la réservation : </label> <input type="text" name="nom" id="nom" value= <?php echo $_SESSION['login']; ?> required />
                </td></tr>
                <tr><td colspan=2>
                    <label for="debut">Date de début :</label> <input type="date" name="debut" id="debut" required />
                    <label for="fin"> &nbsp &nbsp Date de fin  :</label> <input type="date" name="fin" id="fin" required />
                </td></tr>

                <tr><td colspan=2>
                    Pour quel package ?
                    <input type="radio" name="package" value="nuitee" id="nuitee" checked /> <label for="nuitee">À la nuitée</label> &nbsp &nbsp
                    <input type="radio" name="package" value="weekend" id="weekend" /> <label for="weekend">Week-end entier</label> &nbsp &nbsp
                    <input type="radio" name="package" value="semaine" id="semaine" /> <label for="semaine">Toute la semaine !</label><br />
                </td></tr>
                <tr><td colspan=2>
                    Est-ce un séjour privatisé ? 
                    <input type="radio" name="prive" value="Non" id="Non" checked /> <label for="Non">Non</label> &nbsp &nbsp
                    <input type="radio" name="prive" value="Oui" id="Oui" /> <label for="Oui">Oui</label><br />
                </td></tr>
                <tr><td colspan=2>
                    Si non privatisé, pour combien de personnes ? </td></tr>
                    <tr><td class="justify_right"><label for="ptitdub">Nombre de P'tit Dub :</label></td><td><input type="number" name="ptitdub" id="ptitdub" value=0 min="0" max="64"/></td></tr>
                    <tr><td class="justify_right"><label for="grosdub">Nombre de Gros Dub :</label></td><td><input type="number" name="grosdub" id="grosdub" value=0 min="0" max="64"/></td></tr>
                    <tr><td class="justify_right"><label for="pleintarif">Nombre de visiteurs plein tarif :</label></td><td><input type="number" name="pleintarif" id="pleintarif" value=0 min="0" max="64"/></td></tr>
                    <tr><td class="justify_right"><label for="tarifreduit">Nombre de visiteurs tarif réduit (chômeurs, étudiants) :</label></td><td><input type="number" name="tarifreduit" id="tarifreduit" value=0 min="0" max="64"/></td></tr>
                    <tr><td class="justify_right"><label for="enfants">Nombre de visiteurs de plus de 7 ans et de moins de 18 ans :</label></td><td><input type="number" name="enfants" id="enfants" value=0 min="0" max="64"/></td></tr>
                <?php echo '<input type="hidden" name="login" value="' . $_SESSION['prenom'] . ' ' . $_SESSION['nom'] . '">'; ?>
                    <input type="hidden" name="official" value=0>
                <tr><td colspan=2><input type="submit" value="Envoyer" /></td></tr>
            </form>
        </table>
        <table class="resume_resa">
            <?php
                echo "<tr><td class=\"cell_none\" colspan=2><a class=\"underlined\">Réservations à venir :</a></tr>";
                // On récupère tout le contenu de la table reservation
                $reponse = $bdd->query('SELECT * FROM reservation ORDER BY debut');

                // On affiche chaque entrée une à une
                while ($donnees = $reponse->fetch())
                    {
                    echo '<tr>';
                    //n'écrire que les séjours dont date de fin ultérieure à aujourd'hui
                    if($donnees['fin'] > date("Y-m-d")){ ?>
                        <td class="cell_none">
                        <?php echo utf8_decode($donnees['nom']) . " : du " . convertdate($donnees['debut']) . "au " . convertdate($donnees['fin']);
                            if ($donnees['prive'] == 1){ 
                                echo "- Séjour privatisé <br />";
                            }
                            elseif ($donnees['officiel'] == 1){
                                echo "- Évènement Couz'Toujours <br />";
                            }
                            else { 
                                echo "- pour " . ($donnees['nbptitdub'] + $donnees['nbgrosdub'] + $donnees['nbvis_pt'] + $donnees['nbvis_tr'] + $donnees['nbvis_enf']) . " personnes <br />";
                            }
                        echo "(par " . $donnees['username'] . " le " . convertdate($donnees['date_resa']) . ") <br /> " ;
                        echo'</td>';
                        echo "</tr> " ;
                        //mise dans les tableaux highlighted le date(strotime) est peut-être évitable mais j'ai pas trouvé mieux.)
                        if($donnees['prive'] == 1){
                            $date_to_add = $donnees['debut'];
                            while ($date_to_add <= $donnees['fin']) {
                                $evenement_prive[] = $date_to_add;
                                $date_to_add = date("Y-m-d",strtotime($date_to_add . " +1 day"));
                             } 
                        }
                        elseif($donnees['officiel'] == 1){
                            $date_to_add = $donnees['debut'];
                            while ($date_to_add <= $donnees['fin']) {
                                $evenement_officiel[] = $date_to_add;
                                $date_to_add = date("Y-m-d",strtotime($date_to_add . " +1 day"));
                             } 
                        }
                        //le plus dur : création des trois classes dépendant du nb de personnes -> passage par un tableau intermédiaire.
                        else{
                            $date_to_add = $donnees['debut'];
                            $nb_de_pers = ($donnees['nbptitdub'] + $donnees['nbgrosdub'] + $donnees['nbvis_pt'] + $donnees['nbvis_tr'] + $donnees['nbvis_enf']);
                            while ($date_to_add <= $donnees['fin']) {
                                //si pas déjà dans l'array en utilisant la double fonction
                                if(!in_array($date_to_add,array_column($reservations_personnes, '0'))){
                                    $reservations_personnes[] = array($date_to_add, $nb_de_pers);
                                }
                                else{
                                    $key = array_search($date_to_add, array_column($reservations_personnes, 0));
                                    $reservations_personnes[$key][1] += $nb_de_pers;

                                }
                                $date_to_add = date("Y-m-d",strtotime($date_to_add . " +1 day"));
                            } 
                        }
                        }
                    }

                $reponse->closeCursor(); // Termine le traitement de la requête
            ?>
        
        </table>

<!--    </section> -->
    <?php
    //construction des tableaux d'évènements normaux (on peut gérer ici les limites de personnes.)
    $top = sizeof($reservations_personnes);
    $bottom = 0;
    while($bottom <= $top){
        if ($reservations_personnes[$bottom][1] <=4) {
            $evenement_04[] = $reservations_personnes[$bottom][0];
        }
        elseif ($reservations_personnes[$bottom][1] <=8) {
            $evenement_48[] = $reservations_personnes[$bottom][0];
        }
        else {
            $evenement_8plus[] = $reservations_personnes[$bottom][0];
        }
        $bottom++;
    }
//    $evenement_prive[] = date("Y-m-d");
    //tableaux de dates highlighted
    $calendar->privatised_event = $evenement_prive;
    $calendar->official_event = $evenement_officiel;
    $calendar->event_04 = $evenement_04;
    $calendar->event_48 = $evenement_48;
    $calendar->event_8plus = $evenement_8plus;
    //-------------------------------------------------------------- output calendar -> on peut rajouter la classe ?
    echo ("<ol id=\"year\">\n");
    for($i=1;$i<=12;$i++){
        echo ("<li class =\"month\">");
        echo ($calendar->output_calendar($calendar->year, $i));
        echo ("</li>\n");
    }
    echo ("</ol>");
    ?> 

    </section>

    <?php include("include/pieddepage.php"); ?>
</body>

</html>