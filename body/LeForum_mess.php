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
    //réponse du formulaire de réponse de message.
    if (isset($_POST['name']) && isset($_POST['msg'])) {
        //update avec tag.
        $msg = $_POST['msg'];
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
    //Réponse du formulaire de suppression de message
    if(isset($_POST['msg_a_suppr'])){
        $id_msg=$_POST['msg_a_suppr'];
        $req = $bdd->prepare('DELETE FROM z_messages WHERE num=?');
        $req->execute(array($id_msg));
        $req->closeCursor();
        header ('location: ../body/LeForum_mess.php?theme=' . $theme . '&topic=' . $topic ); //on recharge la page
    }
    //Nom d'auteur du message
    $nom_auth = $_SESSION['prenom'] . ' ' . $_SESSION['nom'];
?>
  
 
<!DOCTYPE html>

<html>

<head>
    <?php include("../include/style.php"); ?>
    <title>Forum Couz'Toujours</title>

    <!-- Script pour la zone de texte du message -->
    <script src="http://cdn.tinymce.com/4/tinymce.min.js"></script>
    <script src="../include/fr_FR.js"></script>
    <style> @import url("../include/skin.min.css"); </style>
    <script>tinymce.init({
        selector: 'textarea',
        height: 200,
        theme: 'modern',
        plugins: [
            'advlist autolink lists link image charmap print preview hr anchor pagebreak',
            'searchreplace wordcount visualblocks visualchars code fullscreen',
            'insertdatetime media nonbreaking save table contextmenu directionality',
            'emoticons template paste textcolor colorpicker textpattern imagetools codesample'
          ],
        toolbar1: 'undo redo | fontsizeselect forecolor | bold italic underline strikethrough',
        toolbar2: 'alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link unlink emoticons',
        statusbar: false,
        menubar: false,
        resizehandle: true});
    </script>-->
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
			echo '<form method="post">';
    	    echo '<input type="hidden" name="answer" value=1>
    	    <input type="submit" value="Répondre"></form>';
    	}
    	echo '</td></tr>';
    	$request=$bdd->prepare('SELECT * FROM z_messages WHERE id_topic=? ORDER BY post_date DESC');
    	$request->execute(array($topic));
    	while ($mess=$request->fetch()) {
    		echo '<tr><td class="f_message" colspan=2>' . $mess['message'] . '</td></tr>';
    		echo '<tr><td class="f_signature" colspan=2> signé : ' . $mess['author'] . ' le ' . convertdate($mess['post_date']);
            if ($nom_auth == $mess['author']){
                echo '<form method="post" onsubmit="return confirm(\'Es-tu sûr de vouloir supprimer ce truc ?\');"><input type="hidden" name="msg_a_suppr" value="' . $mess['num'] . '"><input type="submit" value="Supprimer"></form>';
                /*echo '<form method="post">';
                echo '<input type="hidden" name="answer" value=1>
                    <input type="hidden" name="modif" value="' . $mess['message'] . '">
                    <input type="submit" value="Modifier"></form>';*/
            }
            echo '</td></tr>';
    		echo'<tr><td class="f_separator" colspan=2></td></tr>';
    	}
    	$request->closeCursor();
    	?>
    	</table>
    	</section>
    	<section class="colonne_droite">
    		<?php
    		if($ans){ 
	    		echo '<table class="formulaire_idees">
	            <tr><td></td><td class="underlined">';
                echo 'Répondre à la discussion';
                echo '</td><td></td></tr>';
                echo '<form method="post">';
		        echo'<input type="hidden" name="name" value="' . $nom_auth . '">';
		        echo '<tr><td colspan=3><textarea name="msg" id="msg"></textarea></td></tr>
		            <input type="hidden" name="answer" value=0>
		            <tr><td></td><td class="justify_center"><input type="submit" value="Poster"></form><form method="post"><input type="submit" value="Annuler"></form></td><td></td></tr>
		        </form>
		        </table>';
    		 }
    		?>
    	</section>
    	</section>
    	</section>
    </section>

    <?php include("../include/pieddepage.php"); ?>

</body>

</html>