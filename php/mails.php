<?php
//page php pour effectuer l'édition de tableaux récapitulatifs

    include("../doctor/bdd.php");

    // on teste si nos variables sont définies
if (isset($_POST['tab_date'])) {
    $csvcsv = "Date d'édition :;" . $_POST['tab_date'] . "\n";
    $csvcsv.="Nom;Prénom;Email;message;Envoyé ?\n";
    //construction du sql_query qui va dépendre des options cochées
    $sql_query = "SELECT * FROM users WHERE numero > 9 ORDER BY numero";
    $req = $bdd->query($sql_query);
    //faut rajouter instructions : pas users test.
    while ($tab = $req->fetch()){
        $message = "[message automatique] Chère/Cher " . $tab['prenom'] . ", j'espère que tu vas bien. Tu trouveras ci-après tes identifiants : ton login est " . $tab['name'] . " et ton mot de passe temporaire est Dubus" . $tab['numero'] . " . N'oublie pas de changer ton mot de passe aussitôt connecté(e) dans l'espace \"mon compte\". Si tu rencontres le moindre souci, n'hésite pas à me faire signe ! Bisous. Tu peux enfin te connecter au site www.couztoujours.com. ";
        $csvcsv .=  $tab['nom'] . ";" . $tab['prenom'] . ";" . $tab['email'] . ";" . $message . ";\n";
    }
    $csv = utf8_decode($csvcsv);
    $req->closeCursor();
    //termine le traitement de la requête   
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Content-Length: " . strlen($csv));
    header("Content-type: text/x-csv");
    header("Content-Disposition: attachment; filename=" . "tableau.csv");
    echo $csv;
}
else{
    echo 'Les variables du formulaire ne sont pas déclarées.';
}
?>