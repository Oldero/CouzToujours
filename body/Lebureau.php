<?php
//Asso Couz'Toujours. Le bureau, le CA, les AG 

    session_start ();
?>
  

<!DOCTYPE html>

<html>

<head>
    <?php include("../include/style.php"); 
    		include("../doctor/bdd.php"); ?>
    <title>L'association</title>
</head>

<body>

    <?php include("../include/entete.php"); ?>
    <?php include("../include/laterale.php"); ?>
    
    <div class="img_fixed"><img src="../img/styled_margots.jpg"></div>

    <section class="corps">
    	<table class="table_docs">

		<?php
			//Recherche dans la bdd des membres du bureau
			$bureau = $bdd->query('SELECT * FROM users WHERE bureau != 0');
			while ($membre = $bureau->fetch()) {
				switch ($membre['bureau']) {	
					case 1:
						$prez = $membre['prenom'] . " " . $membre['nom'];
						break;
					case 2:
						$vice_prez = $membre['prenom'] . " " . $membre['nom'];
						break;
					case 3:
						$trez = $membre['prenom'] . " " . $membre['nom'];
						break;
					case 4:
						$vice_trez = $membre['prenom'] . " " . $membre['nom'];
						break;
					case 5:
						$secr = $membre['prenom'] . " " . $membre['nom'];
						break;
					case 6:
						$vice_secr = $membre['prenom'] . " " . $membre['nom'];
						break;
					default:
						# code...
						break;
				}
			}
			$bureau -> closeCursor();
		?>
    	<tr><td class="justify_center">Le bureau est constitué des Couz' suivants :<br>
    	Au poste de <strong>Secrétaire</strong>, <?php echo $secr . "."; ?><br>
    	Au poste de <strong>Vice secrétaire</strong>, <?php echo $vice_secr . "."; ?><br>
    	Au poste de <strong>Trésorier(e)</strong>, <?php echo $trez . "."; ?><br>
    	Au poste de <strong>Vice-trésorier(e)</strong>, <?php echo $vice_trez . "."; ?><br>
    	Au poste de <strong>Président(e)</strong>, <?php echo $prez . "."; ?><br>
    	Au poste de <strong>Vice-président(e)</strong>, <?php echo $vice_prez . "."; ?></td></tr>
    	</table>
    </section>

    <?php include("../include/pieddepage.php"); ?>

</body>

</html>