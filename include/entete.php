<header id="tete">        
  <?php
    // On récupère nos variables de session
    if (isset($_SESSION['login']) && isset($_SESSION['pwd']) && isset ($_SESSION['cotiz'])) {

        // On teste pour voir si nos variables ont bien été enregistrées
        echo '<body>';
        echo '<div class="bienvenue"><div> Bienvenue, '.$_SESSION['prenom'].' ! </div>';
        //affichage du popup cotiz non payée sauf si c'est un non-adhérent
        if ($_SESSION['cotiz'] == 0 && $_SESSION['type'] != 0) {
            echo "<div class=\"bold\"> &nbsp Tu n'as pas encore payé ta cotiz !</div>";
        }
        echo '</div>';

        echo '<div class = "nav_tete">';
            // On affiche un lien pour fermer notre session
            echo '<dl><dt><a class="lien" href="monCompte.php" title="Informations personnelles">Mon compte</a></dt></dl>';
            echo '<dl><dt><a class="lien" href="../php/logout.php" title="À bientôt ?">Déconnexion</a></dt></dl>';
        echo '</div>';
        echo '</body>';
    }
    else {
        echo '<div class="reconnexion">Session expirée. Tu dois te <a class="lien" href="../index.html">reconnecter</a> !</div>';
        echo '<meta http-equiv="refresh" content="0;URL=../index.html">';
    }
    ?>    
</header>