<?php 
session_start();
echo'<nav id="menu">        
    <div class="element_menu">
        <h3>Couz\'Toujours</h3>';
        if(isset($_SESSION['pwd'])){
        echo '<div class="navigation">
            <dl><dt><a href="Accueil.php" title="Retour à l\'accueil"> Accueil</a></dt></dl>
            <dl><dt><a href="LaFamilha.php" title="Généalogie et carnet d\'adresses"> La Familha</a></dt>
                <dd><ul>
                    <li>Généalogie</li>
                    <li>Carnet d\'adresses</li>
                </ul></dd></dl>
            <dl><dt><a href="CampCousins.php" title="Les camps cousins"> Camp cousins</a></dt>
                <dd><ul>
                    <li>À venir</li>
                    <li>Archives</li>
                </ul></dd></dl>
            <dl><dt><a href="">Les Margots</a></dt>
                <dd><ul>
                    <li>L\'actualité de la maison</li>';
            if ($_SESSION['ca'] == 1 || $_SESSION['admin'] == 1) {
                    echo'<li><a href="LesMargots.php" title="Réserver les Margots"> Réserver les Margots</a></li>';
            }
                echo '</ul></dd></dl>
            <dl><dt><a href="Lassociation.php" title="L\'association"> L\'association</a></dt>
                <dd><ul>
                    <li>Les statuts</li>
                    <li>Le bureau</li>';
            if ($_SESSION['ca'] == 1 || $_SESSION['admin'] == 1) {
                echo '<li><a href="gestion.php">Gestion de l\'association</a></li>';
            }
                echo '</ul></dd></dl>
            <dl><dt><a href="LeForum.php" title="Pour discuter bien sûr !"> Le Forum</a></dt></dl>
        </div>';
        } ?>
    </div>
</nav>