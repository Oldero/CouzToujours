<?php
// Forum à construire 

    session_start ();
    include("../doctor/bdd.php");
    include("../php/fonctions.php");

    //si topic pas déclaré
    if(!isset($_GET['theme'])) {
    	header ('location: ../body/LeForum.php');
    }
    else {
    	$theme = $_GET['theme'];
    }
    //Réponse du bouton poster un message
    if(isset($_POST['answer'])) {
    	$new_tpc = $_POST['answer'];
    }
    else {
    	$new_tpc = 0;
    }
    //récup du dernier message pour le header de création de topic
    $last_id = 1;
    //réponse du formulaire créer topic
    if(isset($_POST['titre_topic']) && isset($_POST['name'])) {
    	$titre_tpc = htmlspecialchars($_POST['titre_topic']);
    	$post_date=date("Y-m-d H:i:s");
    	$request=$bdd->prepare('INSERT INTO z_topics(titre,theme,last_modif,last_name) VALUES(?,?,?,?)');
    	$request->execute(array($titre_tpc,$theme,$post_date,$_POST['name']));
    	$request->closeCursor();
    	header('location: ../body/LeForum_mess.php?theme=' . $theme . '&topic=' . $_POST['id_topic']);
    }
?>
  
 
<!DOCTYPE html>

<html>

<head>
    <?php include("../include/style.php"); ?>
    <title>Forum Couz'Toujours</title>
</head>


<body>

    <?php include("../include/entete.php"); ?>
    <?php include("../include/laterale.php"); ?>

    <section class="corps">
    <section class="page_forum">
    <section class="page_deuxcolonnes">
    	<section class="ensemble_gauche">
    	<table class="f_list">
    	<?php 
    		switch ($theme) {
    			case 1:
    				echo '<tr><td class="big_theme">La Famille</td>';
    				break;
    			case 2:
    				echo '<tr><td class="big_theme">Les Margots</td>';
    				break;
    			case 3:
    				echo '<tr><td class="big_theme">Les Camps Couz\'</td>';
    				break;
    			case 4:
    				echo '<tr><td class="big_theme">L\'association</td>';
    				break;
    			case 5:
    				echo '<tr><td class="big_theme">Le site web</td>';
    				break;
    			case 6:
    				echo '<tr><td class="big_theme">Miscellanées</td>';
    				break;
    			default:
    				echo '<a>Quoi ?!?</a>';
    				break;
    		}
    	echo '<td class="cell_return"><a href="LeForum.php" class="return">Retour à la liste des thèmes</a></td></tr>';
    	echo '<tr><td class="separator"></td></tr>'; //Important :</td></tr>';
    	$request=$bdd->prepare('SELECT * FROM z_topics WHERE pin=1 AND theme=? ORDER BY titre');
    	$request->execute(array($theme));
    	while ($top=$request->fetch()) {
    		echo '<tr><td class="pin_topic" rowspan=2><a href="LeForum_mess.php?theme=' . $theme . '&topic=' . $top['id_topic'] . '">' . $top['titre'] . '</a></td><td class="last">Dernier message par ' . $top['last_name'] . '</td></tr><tr><td class="last">Le ' . convertdate($top['last_modif']) . '</td></tr>
    		<tr><td class="f_separator" colspan=2></td></tr>';
    	}
    	$request->closeCursor();
    	echo '<tr><td class="f_separator"></td></tr>'; //Autres sujets :</td></tr>';
    	echo '<tr><td class="f_separator"></td></tr>';
    	echo '<tr><td class="f_separator"></td></tr>';
    	echo '<tr><td class="f_separator"></td></tr>';
    	$request=$bdd->prepare('SELECT * FROM z_topics WHERE pin=0 AND theme=? ORDER BY last_modif DESC');
    	$request->execute(array($theme));
    	while ($top=$request->fetch()) {
    		echo '<tr><td class="notpin_topic" rowspan=2><a href="LeForum_mess.php?theme=' . $theme . '&topic=' . $top['id_topic'] . '">' . $top['titre'] . '</a></td><td class="last">Dernier message par ' . $top['last_name'] . '</td></tr><tr><td class="last">Le ' . convertdate($top['last_modif']) . '</td></tr>
    		<tr><td class="f_separator" colspan=2></td></tr>';
    		$last_id = $top['id_topic'];
    	}
    	//last_id pour formulaire de cration de topic
    	$last_id ++;
    	$request->closeCursor();
    	?>
    	</table>
    	</section>
    	<section class="colonne_droite">
    		<?php
    	//if $ans form avec text et envoyer puis $ans=0 avec écriture dans bdd, else bouton répondre puis $ans=1
	    	if(!$new_tpc){
				echo '<form method="post">';/* action="' . $_SERVER['PHP_SELF'] .'?theme=' . $theme . '&topic=' . $topic . '" method="post"">*/
	    	    echo '<input type="hidden" name="answer" value=1>
	    	    <input type="submit" value="Créer un nouveau sujet"></form>';
	    	} 
	    	else{
    			echo '<table class="formulaire_idees"><tr><td class="justify_center">';
	    		echo '<form method="post">';/* action="' . $_SERVER['PHP_SELF'] .'?theme=' . $theme . '&topic=' . $topic . '" method="post"">*/
	    		echo '<label for="titre_topic">Titre du nouveau sujet : </label><input type="textarea" name="titre_topic" id="titre_topic"></td></tr><tr><td class="justify_center">';
	    	    echo '<input type="hidden" name="answer" value=0>';
	    	    echo '<input type="hidden" name="id_topic" value=' . $last_id . '>';
	    	    echo'<input type="hidden" name="name" value="' . $_SESSION['prenom'] . ' ' . $_SESSION['nom'] . '">';
	    	    echo '<input type="submit" value="Créer un nouveau sujet"></form>';
	    	    echo '</td></tr></table>';
	    	}
    		?>
    	</section>
    </section>
	</section>
    </section>

    <?php include("../include/pieddepage.php"); ?>

</body>

</html>