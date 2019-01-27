<?php
// Forum à construire 

    session_start ();
    include("../doctor/bdd.php");
    include("../php/fonctions.php");
 
    //si topic pas déclaré
    if(!isset($_GET['theme']) || !isset($_GET['topic'])) {
    	header ('location: ../body/LeForum.php');
    }
    else {
    	$theme = $_GET['theme'];
		$topic = $_GET['topic'];
    	$request = $bdd->prepare('SELECT titre FROM z_topics WHERE id_topic = ?');
    	$request->execute(array($topic));
    	$top = $request->fetch();
    	$topic_title = $top['titre'];
    	$request->closeCursor();
    }
    //Réponse du bouton poster un message
    if(isset($_POST['answer'])) {
    	$ans = $_POST['answer'];
    }
    else {
    	$ans = 0;
    }
    //réponse du formulaire d'idée.
    if (isset($_POST['name']) && isset($_POST['msg'])) {
        //update avec tag.
        $msg = htmlspecialchars($_POST['msg']);
        $day_date = date("Y-m-d H:i:s");
        $req = $bdd->prepare('INSERT INTO z_messages(id_topic, author, message, post_date) VALUES(?,?,?,?)');
        $req->execute(array($topic, $_POST['name'], $msg, $day_date));
        $req->closeCursor();
        //update de bdd topics
        $req = $bdd->prepare('UPDATE z_topics SET last_modif=?, last_name=? WHERE id_topic=?');
        $req->execute(array($day_date, $_POST['name'], $topic));
        $req->closeCursor();
        //termine le traitement de la requête    
        header ('location: ../body/LeForum_mess.php?theme=' . $theme . '&topic=' . $topic ); //on recharge la page
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
    	echo '<td class="cell_return"><a href="LeForum_topics.php?theme=' . $theme . '" class="return">Retour à la liste des topics</a></td></tr>';
    	echo '<tr><td class="titre_topic">' . $topic_title . '</td><td>';
    	//if $ans form avec text et envoyer puis $ans=0 avec écriture dans bdd, else bouton répondre puis $ans=1
        //$topic = 4, non modifiable
    	if(!$ans && $topic != 4){
			echo '<form method="post">';/* action="' . $_SERVER['PHP_SELF'] .'?theme=' . $theme . '&topic=' . $topic . '" method="post"">*/
    	    echo '<input type="hidden" name="answer" value=1>
    	    <input type="submit" value="Répondre"></form>';
    	}
    	echo '</td></tr>';
    	$request=$bdd->prepare('SELECT * FROM z_messages WHERE id_topic=? ORDER BY post_date DESC');
    	$request->execute(array($topic));
    	while ($mess=$request->fetch()) {
    		echo '<tr><td class="f_message" colspan=2>' . $mess['message'] . '</td></tr>';
    		echo '<tr><td class="f_signature" colspan=2> signé : ' . $mess['author'] . ' le ' . convertdate($mess['post_date']) . '</td></tr>';
    		echo'<tr><td class="f_separator" colspan=2></td></tr>';
    	}
    	$request->closeCursor();
    	?>
    	</table>
    	</section>
    	<section class="colonne_droite">
    		<?php
    		if($ans){ ?>
	    		<table class="formulaire_idees">
	            <tr><td></td><td class="underlined">Répondre à la discussion</td><td></td></tr>
		        <form method="post">
		            <?php echo'<input type="hidden" name="name" value="' . $_SESSION['prenom'] . ' ' . $_SESSION['nom'] . '">'; ?>
		            <tr><td colspan=3><textarea name="msg" id="msg" required="required"></textarea></td></tr>
		            <input type="hidden" name="answer" value=0>
		            <tr><td></td><td class="justify_center"><input type="submit" value="Poster"></td><td></td></tr>
		        </form>
		        </table>
    		<?php }
    		?>
    	</section>
    	</section>
    	</section>
    </section>

    <?php include("../include/pieddepage.php"); ?>

</body>

</html>