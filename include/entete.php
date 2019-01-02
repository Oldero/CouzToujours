<header id="tete">        
  <?php
    // On démarre la session (ceci est indispensable dans toutes les pages de notre section membre)
    session_start ();

    // On récupère nos variables de session
    if (isset($_SESSION['login']) && isset($_SESSION['pwd']) && isset ($_SESSION['cotiz'])) {

        // On teste pour voir si nos variables ont bien été enregistrées
        echo '<html>';
        echo '<head>';
        echo '<title>Page de notre section membre</title>';
        echo '</head>';

        echo '<body>';
        echo 'Bienvenue, '.$_SESSION['login'].' !';
        if ($_SESSION['cotiz'] == 0) {
            echo "Tu n'as pas payé ta cotiz !";
        }
        echo '<br />';

        echo '<div class = "flextete">';
            // On affiche un lien pour fermer notre session
            echo '<a href="monCompte.php">Mon compte     </a>';
            if ($_SESSION['ca'] == 1 || $_SESSION['admin'] == 1) {
                echo '<a href="gestion.php">Gestion des adhérents     </a>';
            }
            echo '<a href="php/logout.php">Déconnection</a>';
        echo '</div>';
        echo '</body>';
    }
    else {
        echo 'Les variables ne sont pas déclarées.';
    }
    ?>    
</header>