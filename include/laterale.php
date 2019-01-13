<?php 
session_start();
echo'<nav id="menu">        
    <div class="element_menu">
        <h3>Couz\'Toujours</h3>';
        if(isset($_SESSION['pwd'])){
        echo '<div class="navigation">
            <dl><dt><a class="lien" href="Accueil.php" title="Retour à l\'accueil"> Accueil</a></dt></dl>
            <dl><dt><a class="lien" href="LaFamilha.php" title="Généalogie et carnet d\'adresses"> La Familha</a></dt>
                <dd><ul>
                    <li>Généalogie</li>
                    <li>Carnet d\'adresses</li>
                </ul></dd></dl>
            <dl><dt><a class="lien" href="CampCousins.php" title="Les camps cousins"> Camp cousins</a></dt>
                <dd><ul>
                    <li>À venir</li>
                    <li>Archives</li>
                </ul></dd></dl>
            <dl><dt><a class="lien" href="LesMargots.php">Les Margots</a></dt>
                <dd><ul>
                    <li>L\'actualité de la maison</li>';
            if ($_SESSION['type'] > 0) {
                    echo'<li><a class="lien" href="resa_Margots.php" title="Réserver les Margots"> Réserver les Margots</a></li>';
            }
                echo '</ul></dd></dl>
            <dl><dt><a class="lien" href="Lassociation.php" title="L\'association"> L\'association</a></dt>
                <dd><ul>
                    <li>Les statuts</li>
                    <li>Le bureau</li>';
            if ($_SESSION['ca'] == 1 || $_SESSION['admin'] == 1) {
                echo '<li><a class="lien" href="gestion.php">Gestion de l\'association</a></li>';
            }
                echo '</ul></dd></dl>
            <dl><dt><a class="lien" href="LeForum.php" title="Pour discuter bien sûr !"> Le Forum</a></dt></dl>
        </div>';
        }
    echo '</div>
</nav>'; ?>