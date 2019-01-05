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
<?php
    //-------------------------------------------------------------- output calendar
    echo ("<ol id=\"year\">\n");
    for($i=1;$i<=12;$i++){
        echo ("<li class =\"month\">");
        echo ($calendar->output_calendar($calendar->year, $i));
        echo ("</li>\n");
    }
    echo ("</ol>");
    ?> 

<!--    <section class="calendar">
        <iframe name="InlineFrame1" id="InlineFrame1" style="width:690px;height:515px;" src="https://www.mathieuweb.fr/calendrier/calendrier-des-semaines.php?nb_mois=12&nb_mois_ligne=4&mois=0&an=0&langue=fr&texte_color=DDDDDD&week_color=F8F8F8&week_end_color=EDEDED&police_color=2F4544&sel=false" scrolling="no" frameborder="0" allowtransparency="true"></iframe>
    </section>
 -->           

    <section id="page_resa">
        <section id="formulaire_resa">
            <p>Réservation des Margots</p>
            <form method="post" action="php/reservation.php">
                <p>
                    <label for="nom">À quel nom ?</label> <input type="text" name="nom" id="nom" value= <?php echo $_SESSION['login']; ?> required />
                </p>
                <p>
                    <label for="debut">Date de début :</label> <input type="date" name="debut" id="debut" required />
                    <label for="fin"> &nbsp &nbsp Date de fin  :</label> <input type="date" name="fin" id="fin" required />
                </p>

                <p>
                    Pour quel package ?
                    <input type="radio" name="package" value="nuitee" id="nuitee" checked /> <label for="nuitee">À la nuitée</label> &nbsp &nbsp
                    <input type="radio" name="package" value="weekend" id="weekend" /> <label for="weekend">Week-end entier</label> &nbsp &nbsp
                    <input type="radio" name="package" value="semaine" id="semaine" /> <label for="semaine">Toute la semaine !</label><br />
                </p>
                <p>
                    Est-ce un séjour privé ? 
                    <input type="radio" name="prive" value="Non" id="Non" checked /> <label for="Non">Non</label> &nbsp &nbsp
                    <input type="radio" name="prive" value="Oui" id="Oui" /> <label for="Oui">Oui</label><br />
                </p>
                <p>
                    Si non privatisé, pour combien de personnes ? <br />
                    <label for="ptitdub">Nombre de P'tit Dub :</label> <input type="number" name="ptitdub" id="ptitdub" value=0 /><br />
                    <label for="grosdub">Nombre de Gros Dub :</label> <input type="number" name="grosdub" id="grosdub" value=0 /><br />
                    <label for="pleintarif">Nombre de visiteurs plein tarif :</label> <input type="number" name="pleintarif" id="pleintarif" value=0 /><br />
                    <label for="tarifreduit">Nombre de visiteurs tarif réduit (chômeurs, étudiants) :</label> <input type="number" name="tarifreduit" id="tarifreduit" value=0 /><br />
                    <label for="enfants">Nombre de visiteurs de plus de 7 ans et de moins de 18 ans :</label> <input type="number" name="enfants" id="enfants" value=0 />

                </p>
                <input type="submit" value="Envoyer" />
            </form>
        </section>
        <section id="resume_resa">
            <?php
                echo "Réservations à venir :<br />";
                // On récupère tout le contenu de la table jeux_video
                $reponse = $bdd->query('SELECT * FROM reservation');

                // On affiche chaque entrée une à une
                while ($donnees = $reponse->fetch())
                    {
                     if($donnees['fin'] > $date){
                        echo $donnees['nom'] . " : du " . convertdate($donnees['debut']) . "au " . convertdate($donnees['fin']);
                            if ($donnees['prive'] == 1){ 
                                echo "- Séjour privatisé";
                            }
                            else { 
                                echo "- pour " . ($donnees['nbptitdub'] + $donnees['nbgrosdub'] + $donnees['nbvis_pt'] + $donnees['nbvis_tr'] + $donnees['nbvis_enf']) . " personnes";
                            }
                        echo " (par " . $donnees['username'] . ") <br /> " ;
                        }
                    }

                $reponse->closeCursor(); // Termine le traitement de la requête
            ?>
        </section>
    </section>
    </section>

    <?php include("include/pieddepage.php"); ?>
</body>

</html>