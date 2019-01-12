<header id="tete">        
  <?php
    // On démarre la session (ceci est indispensable dans toutes les pages de notre section membre)
    session_start ();

    // On récupère nos variables de session
    if (isset($_SESSION['login']) && isset($_SESSION['pwd']) && isset ($_SESSION['cotiz'])) {

        // On teste pour voir si nos variables ont bien été enregistrées
        echo '<body>';
        echo 'Bienvenue, '.$_SESSION['prenom'].' !';
        //affichage du popup cotiz non payée sauf si c'est un non-adhérent
        if ($_SESSION['cotiz'] == 0 && $_SESSION['type'] != 0) {
            echo "<strong> Tu n'as pas encore payé ta cotiz !</strong>";
        }
        echo '<br />';

        echo '<div class = "flextete">';
            // On affiche un lien pour fermer notre session
            echo '<a href="monCompte.php">Mon compte</a> &nbsp &nbsp';
            if ($_SESSION['ca'] == 1 || $_SESSION['admin'] == 1) {
                echo '<a href="gestion.php">Gestion de l\'association</a> &nbsp &nbsp';
            }
            echo '<a href="php/logout.php">Déconnection</a>';
        echo '</div>';
        echo '</body>';
    }
    else {
        echo '<div class="reconnexion">Session expirée. Tu dois te <a href="index.html">reconnecter</a> !</div>';
        echo '<meta http-equiv="refresh" content="0;URL=index.html">';
    }
    ?>    
</header>