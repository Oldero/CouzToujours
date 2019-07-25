<?php 
echo'<nav id="menu">        
    <div class="element_menu">
        <img src="../favicon.ico">
        <h3>Couz\'Toujours</h3>';
        if(isset($_SESSION['pwd'])){
        echo '<div class="navigation">
            <dl><dt><a class="lien" href="Accueil.php" title="Retour à l\'accueil">Accueil</a></dt></dl>
            <dl><dt><a class="lien" href="LaFamilha.php" title="Généalogie et carnet d\'adresses">La Familha</a></dt>
                <dd><ul>
                    <li>Généalogie</li>
                    <li>Carnet d\'adresses</li>
                    <li><a class="lien" href="anniversaires.php" title="Calendrier">Anniversaires</a></li>
                    <li><a class="lien" href="oeuvres.php" title="Art familial">Œuvres familiales</a></li>';
        echo '</ul></dd></dl>
            <dl><dt><a class="lien" href="CampCousins.php" title="Les camps cousins">Camp cousins</a></dt>
                <dd><ul>
                    <li>À venir</li>
                    <li><a class="lien" href="CCarchives.php" title="Les archives">Archives</a></li>
                </ul></dd></dl>
            <dl><dt><a class="lien" href="LesMargots.php">Les Margots</a></dt>
                <dd><ul>
                    <li>L\'actualité de la maison</li>
                    <li><a class="lien" href="Codex_Margoticus.php" title="Codex">Codex Margoticus</a></li>';
            if ($_SESSION['type'] > 0 && $_SESSION['type'] < 6) {
                    echo'<li><a class="lien" href="resa_Margots.php" title="Réserver les Margots">Réserver les Margots</a></li>';
            }
                echo '</ul></dd></dl>
            <dl><dt><a class="lien" href="Lassociation.php" title="L\'association"> L\'association</a></dt>
                <dd><ul>
                    <li><a class="lien" href="statuts.php" title="Statuts et CR d\'AG">Les statuts</a></li>
                    <li>Le bureau</li>
                    <li><a class="lien" href="adhesion.php" title="Adhérer ou faire un don">Adhérer à Couz\'Toujours</a></li>';
            if ($_SESSION['ca'] == 1 || $_SESSION['admin'] == 1) {
                echo '<li><a class="lien" href="gestion.php">Gestion de l\'association</a></li>';
            }
                echo '</ul></dd></dl>
            <dl><dt><a class="lien" href="LeForum.php" title="Pour discuter bien sûr !">Le Forum</a></dt></dl>
        </div>';
        }
        echo '<div class="contacts justify_left"></br></br></br>
        	Contact :</br>
        	-> Une adresse mail : </br>&nbsp&nbsp&nbsp cousinsdubus@yahoogroupes.fr</br>
        	-> Un WhatsApp : </br>&nbsp&nbsp&nbsp Couz\'toujours
        </div>';
    echo '</div>
</nav>'; ?>