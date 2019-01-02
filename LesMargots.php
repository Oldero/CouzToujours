<!-- Les Margots : histoire, réservation, photos, projets...
-->

<?php
    session_start ();
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

    <section class="calendar">
        <iframe name="InlineFrame1" id="InlineFrame1" style="width:690px;height:515px;" src="https://www.mathieuweb.fr/calendrier/calendrier-des-semaines.php?nb_mois=12&nb_mois_ligne=4&mois=0&an=0&langue=fr&texte_color=DDDDDD&week_color=F8F8F8&week_end_color=EDEDED&police_color=2F4544&sel=false" scrolling="no" frameborder="0" allowtransparency="true"></iframe>
    </section>
            


    <section class="formulaire">
        <p>Réservation des Margots</p>
        <form method="post" action="php/reservation.php">
            <p>
                <label for="nom">À quel nom ?</label> <input type="text" name="nom" id="nom" value= <?php echo $_SESSION['login']; ?> required />
            </p>
            <p>
                <label for="debut">Date de début :</label> <input type="date" name="fin" id="debut" required />
                <label for="fin"> &nbsp &nbsp Date de fin  :</label> <input type="date" name="debut" id="fin" required />
            </p>
            <p>
                Est-ce un séjour privé ? 
                <input type="radio" name="prive" value="Non" id="Non" checked /> <label for="Non">Non</label> &nbsp &nbsp
                <input type="radio" name="prive" value="Oui" id="Oui" /> <label for="Oui">Oui</label><br />
            </p>
            <p>
                Pour quel package ?
                <input type="radio" name="package" value="nuitee" id="nuitee" checked /> <label for="nuitee">À la nuitée</label> &nbsp &nbsp
                <input type="radio" name="package" value="week-end" id="week-end" /> <label for="week-end">Week-end entier</label> &nbsp &nbsp
                <input type="radio" name="package" value="semaine" id="semaine" /> <label for="semaine">Toute la semaine !</label><br />
            </p>
            <p>
                Si à la nuitée, pour combien de personnes ? <br />
                <label for="ptitdub">Nombre de P'tit Dub :</label> <input type="number" name="ptitdub" id="ptitdub" value=0 /><br />
                <label for="grosdub">Nombre de Gros Dub :</label> <input type="number" name="grosdub" id="grosdub" value=0 /><br />
                <label for="pleintarif">Nombre de visiteurs de plus de 18 ans :</label> <input type="number" name="pleintarif" id="pleintarif" value=0 /><br />
                <label for="tarifreduit">Nombre de visiteurs tarif réduit (chômeurs, étudiants) :</label> <input type="number" name="tarifreduit" id="tarifreduit" value=0 /><br />
                <label for="enfants">Nombre de visiteurs de plus de 7 ans et de moins de 18 ans :</label> <input type="number" name="enfants" id="enfants" value=0 />

            </p>
            <input type="submit" value="Envoyer" />
        </form>
    </section>

    <?php include("include/pieddepage.php"); ?>
</body>

</html>