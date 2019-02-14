<?php
    //Page d'accueil 

    session_start ();
    include("../doctor/bdd.php");
    include("../php/fonctions.php");

//résultat du formulaire rediger_news
    if (isset($_POST['name']) && isset ($_POST['title']) && isset($_POST['msg'])) {
            //update avec tag.
        $title = htmlspecialchars($_POST['title']);
        $msg = htmlspecialchars($_POST['msg']);
        if($_POST['title'] != ""){
            $req = $bdd->prepare('INSERT INTO news(nom, date_du_jour, titre, message) VALUES(?,?,?,?)');
            $req->execute(array($_POST['name'], date("Y-m-d H:i:s"), $title, $msg));
        }
        else{
            $req = $bdd->prepare('INSERT INTO news(nom, date_du_jour, message) VALUES(?,?,?)');
            $req->execute(array($_POST['name'], date("Y-m-d H:i:s"), $msg));
        }
        $req->closeCursor();
        //termine le traitement de la requête    
        header ('location: ../body/Accueil.php'); //on recharge la page accueil
    }
?>


<!DOCTYPE html>

<html>

<head>
    <?php include("../include/style.php"); ?>
    <title>Couz'Toujours</title>
</head>


<body>

    <?php include("../include/entete.php"); ?>
    <?php include("../include/laterale.php"); ?>
    
    <div class="img_fixed"><img src="../img/styled_view.jpg"></div>

    <section class="corps">
    <!-- <section></section> -->
    <table class="carnet_du_jour">
	    <?php
	    	echo '<tr><td colspan=2 class="big">' . ucfirst(convertdate(date("Y-m-d"))) . '</td></tr>';
	    	//aniversaire(s) du jour
	    	$req=$bdd->prepare('SELECT * FROM anniversaires WHERE quoi=1 AND month(quand)=? AND day(quand)=?');
	    	$req->execute(array(date('m'),date('d')));
	    	if ($anniv=$req->fetch()){
	    		$naissance = explode("-", $anniv['quand']); 
	    		$age = date("Y") - $naissance[0];
	    		echo '<tr><td colspan=2 class="less_big">Hey ! Aujourd\'hui ce sont les ' . $age . ' ans de <strong>' . $anniv['qui'] . '</strong>';
		    	while ($anniv=$req->fetch()){
		    		$naissance = explode("-", $anniv['quand']);
		    		$age = date("Y") - $naissance[0];
		    		echo ' mais aussi les ' . $age . ' ans de <strong>' . $anniv['qui'] . '</strong>';
		    	}
		    	echo ' ! Bon anniversaire !</td></tr>';
	    	}
	    	//aniversaire(s) de mariage du jour
	    	$req=$bdd->prepare('SELECT * FROM anniversaires WHERE quoi=4 AND month(quand)=? AND day(quand)=?');
	    	$req->execute(array(date('m'),date('d')));
	    	if ($anniv=$req->fetch()){
	    		$naissance = explode("-", $anniv['quand']); 
	    		$age = date("Y") - $naissance[0];
	    		echo '<tr><td colspan=2 class="less_big">Bon anniv de mariage <strong>' . $anniv['qui'] . '</strong>, ' . $age . ' ans déjà !';
		    	while ($anniv=$req->fetch()){
		    		$naissance = explode("-", $anniv['quand']);
		    		$age = date("Y") - $naissance[0];
		    		echo 'Et <strong>' . $anniv['qui'] . '</strong> aussi, ' . $age . ' ans !';
		    	}
		    	echo ' ! Eh ben !</td></tr>';
	    	}
	    	$req->closeCursor();
	    	//anniveraire(s) de la prochaine semaine
	    	$req=$bdd->prepare('SELECT * FROM anniversaires WHERE quoi=1 AND ((month(quand)=? AND day(quand) BETWEEN ? AND ?) OR (month(quand)=? AND day(quand) BETWEEN ? AND ?)) ORDER BY month(quand),day(quand)');
	    	$req->execute(array(date('m'),date('d')+1,date('d')+7,date('m')+1,0,date('d')-23)); //date('m'),date('d')));
	    	if ($anniv=$req->fetch()){
	    		$naissance = explode("-", $anniv['quand']);
	    		$age = date("Y") - $naissance[0];
	    		$anniv_thisyear = date("Y") . '-' . $naissance[1] . '-' . $naissance[2];
	    		echo '<tr><td class="smaller">Bientôt, ce sera : </td><td class="smaller justify_left"> - ' . day($anniv_thisyear) . ' prochain (le ' . $naissance[2] . '), les ' . $age . ' ans de <strong>' . $anniv['qui'] . '</strong></td></tr>';
	    	}
	    	while ($anniv=$req->fetch()){
	    		$naissance = explode("-", $anniv['quand']);
	    		$age = date("Y") - $naissance[0];
	    		$anniv_thisyear = date("Y") . '-' . $naissance[1] . '-' . $naissance[2];
	    		echo '<tr><td></td><td class="justify_left smaller"> - ' . day($anniv_thisyear) . ' prochain (le ' . $naissance[2] . '), les ' . $age . ' ans de <strong>' . $anniv['qui'] . '</strong></td></tr>';
	    	}
	    ?>
	</table>
    <section class="page_deuxcolonnes">
        <section class="colonne_droite">
        <table class="formulaire_cote">
            <tr><td class="underlined case_titre" colspan=2>Alimenter le fil d'actus</td></tr>
        <form method="post">
            <?php echo'<input type="hidden" name="name" value="' . $_SESSION['prenom'] . ' ' . $_SESSION['nom'] . '">'; ?>
            <tr><td><label for="title">Titre du message : </label>
            </td><td><input type="text" name="title" id="title"/></td></tr>
            <tr><td colspan=2><label for="msg">Quelles nouvelles ?</label></td></tr>
            <tr><td colspan=2><textarea name="msg" id="msg" required="required"></textarea></td></tr>
            <tr><td colspan=2><input type="submit" value="Poster"></td></tr>
        </form>
            <tr><td class="small_ita" colspan=2>Pour les simples messages, va sur le <a href="LeForum.php">forum</a> !</td></tr>
        </table>
        </section>
        <section class="ensemble_gauche">
        <div class="section_news">
        	<h1 class="big">Les actus des cousins Dubus :</h1>
            <?php
                $reponse = $bdd->query('SELECT * FROM news ORDER BY date_du_jour DESC LIMIT 0, 10');
                echo '<table class="news">';
                // On affiche chaque entrée une à une
                while ($donnees = $reponse->fetch())
                    {
                        echo "<tr><td class=\"titre_news\"><strong>" . $donnees['titre'] . "</strong></td><td class=\"sign_news\"> Le " . convertdate($donnees['date_du_jour']) . " par " . $donnees['nom'] . " </td></tr>";
                        echo "<tr><td class=\"msg_news\" colspan=2>" . $donnees['message'] . "</td></tr>";
                    }
                echo '</table>';
                $reponse->closeCursor(); // Termine le traitement de la requête
            ?>
        </div>
        </section>
    </section>
    </section>

    <?php include("../include/pieddepage.php"); ?>

</body>

</html>