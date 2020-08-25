<header id="tete">        
  <?php
    // On récupère nos variables de session
    if (isset($_SESSION['login']) && isset($_SESSION['pwd']) && isset ($_SESSION['cotiz'])) {

        // On teste pour voir si nos variables ont bien été enregistrées
        echo '<body>';
        echo '<div class="bienvenue"><div> Bonjour, '.$_SESSION['prenom'].' ! </div>';
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
// -----------------------------SOUS-MENUS : quatre : La Famille, les margots, L'association, Les Camps cousins
        // Famille > généalogie, carnet d'adresse, Anniversaires, Documents familiaux
        // Camps cousins > Actualités, archives
        //Margots > L'actualité de la maison, Codex Margoticus (x4), Réserver les Margots
        //L'association > Les statuts, Le bureau, Adhérer à Couztoujours, Gestion
        //trouver de quoi appeler l'adresse ? avec variable de session ? (23 pages)
        //if($body>1 && $body<16){
            //echo '<div class = "nav_tete">';
            //switch ($body){
                //case 2:
                    //echo '<dl><dt><a class="lien" href="monCompte.php" title="Informations personnelles">Mon compte</a></dt></dl>';
                    //echo '<dl><dt><a class="lien" href="../php/logout.php" title="À bientôt ?">Déconnexion</a></dt></dl>';
                //break;
                //case 3:

                //break;
                //case 4:

                //break;
                //case 5:

                //break;
                //case 6:

                //break;
                //case 7:

                //break;
                //case 8:

                //break;
                //case 9:

                //break;
                //case 10:

                //break;
                //case 11:

                //break;
                //case 12:

                //break;
                //case 13:

                //break;
                //case 14:

                //break;
                //case 15:

                //break;
                //default:

                //break;
            //}
//    }
//        echo '<div class = "nav_tete">';
//            echo '<dl><dt><a class="lien" href="monCompte.php" title="Informations personnelles">Mon compte</a></dt></dl>';
//            echo '<dl><dt><a class="lien" href="../php/logout.php" title="À bientôt ?">Déconnexion</a></dt></dl>';
//        echo '</div>';
        echo '</body>';
    }
    else {
        echo '<div class="reconnexion">Session expirée. Tu dois te <a class="lien" href="../index.html">reconnecter</a> !</div>';
        echo '<meta http-equiv="refresh" content="0;URL=../index.html">';
    }
    ?>    
</header>