<?php
// Forum à construire 

    session_start ();
    include("../doctor/bdd.php");
    include("../php/fonctions.php");

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
    	<p>Espace de discussion : tout le monde y est le bienvenu !</p>
    	<table class="f_list_theme">
    		<tr><td class="f_theme"><a href="LeForum_topics.php?theme=1">La Famille</a><p class="f_subtitle">Actualité de la famille, le carnet du jour...</p></td></tr>
    		<tr><td class="f_separator"></td></tr>
    		<tr><td class="f_theme"><a href="LeForum_topics.php?theme=2">Les Margots</a><p class="f_subtitle">Projets en cours, réservations...</p></td></tr>
    		<tr><td class="f_separator"></td></tr>
    		<tr><td class="f_theme"><a href="LeForum_topics.php?theme=3">Les Camps Couz'</a><p class="f_subtitle">Préparation du prochain Camp Cousins, les souvenirs...</p></td></tr>
    		<tr><td class="f_separator"></td></tr>
    		<tr><td class="f_theme"><a href="LeForum_topics.php?theme=4">L'association</a><p class="f_subtitle">L'actualité, les infos...</p></td></tr>
    		<tr><td class="f_separator"></td></tr>
    		<tr><td class="f_theme"><a href="LeForum_topics.php?theme=5">Le site web</a><p class="f_subtitle">Boîte à idées, demandes, report de bugs...</p></td></tr>
    		<tr><td class="f_separator"></td></tr>
    		<tr><td class="f_theme"><a href="LeForum_topics.php?theme=6">Miscellanées</a><p class="f_subtitle">Conversations sans lendemain, petits trucs...</p></td></tr>
    	</table>
    </section>
	</section>

    <?php include("../include/pieddepage.php"); ?>

</body>

</html>