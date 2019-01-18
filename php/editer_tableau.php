<?php
//page php pour effectuer l'édition de tableaux récapitulatifs

    include("../doctor/bdd.php");

    // on teste si nos variables sont définies
if (isset($_POST['tri']) && isset($_POST['cotiz']) && isset($_POST['adh']) && isset($_POST['select'])) {
        //query à distinquer selon les critères de choix.
        $csvcsv="Tribu;Nom;Prénom;Type;Cotiz\n";
        //construction du sql_query qui va dépendre des options cochées
        $sql_query = "SELECT * FROM users";
        switch ($_POST['cotiz']) {
            case "non_payee":
                $sql_query .= " WHERE cotiz = 0";
                break;
            case "payee":
                $sql_query .= " WHERE cotiz = 1";
                break;
            case "les_deux":
                $sql_query .= " WHERE (cotiz = 1 OR cotiz = 0)";
                break;
            default:
                break;
        }
        switch ($_POST['adh']) {
            case "adherents":
                $sql_query .= " AND type > 0";
                break;
            case "non_adherents":
                $sql_query .= " AND type = 0";
                break;
            case "tous":
                break;
            default:
                break;
        }
        if ($_POST['select'] == "selection") {
            $sql_query .= " AND (";
            if ($_POST['visiteurs'] == "on") {
                $sql_query .= " type = 0 OR";
            }
            else{
                $sql_query .= " type > 0 AND";
            }
            if ($_POST['ptitsdub'] == "on") {
                $sql_query .= " type = 1 OR";
            }
            else{
                $sql_query .= " type > 1 AND";
            }
            if ($_POST['grossolo'] == "on") {
                $sql_query .= " type = 2 OR";
            }
            else{
                $sql_query .= " type > 2 AND";
            }
            if ($_POST['grostribu'] == "on") {
                $sql_query .= " type = 3 OR";
            }
            else{
                $sql_query .= " type > 3 AND";
            }
            if ($_POST['etudiants'] == "on") {
                $sql_query .= " type = 4 OR";
            }
            else{
                $sql_query .= " type > 4 AND";
            }
            if ($_POST['honneur'] == "on") {
                $sql_query .= " type = 5";
            }
            else{
                $sql_query .= " type > 5";
            }
            $sql_query .= ")";                
        }
        switch ($_POST['tri']) {
            case "nom":
                $sql_query .= " ORDER BY tribu IS NULL,tribu,nom, prenom, type, cotiz";
                break;
            case "type":
                $sql_query .= " ORDER BY tribu IS NULL,tribu,type, nom, prenom, cotiz";
                break;
            case "cotiz":
                $sql_query .= " ORDER BY tribu IS NULL,tribu,cotiz DESC, nom, prenom, type";
                break;
            default:
                break;
        }
        $req = $bdd->query($sql_query);
        //faut rajouter instructions : pas users test.
        while ($tab = $req->fetch()){
            //pas l'admin :
            if ($tab['name'] != "admin") {
            switch ($tab['type']) {
                case 0:
                    $type = "non-adhérent";
                    break;
                case 1:
                    $type = "P'tit Dub";
                    break;
                case 2:
                    $type = "Gros Dub solo";
                    break;
                case 3:
                    $type = "Gros Dub tribu";
                    break;
                case 4:
                    $type = "Étudiant de tribu";
                    break;
                case 5:
                    $type = "Membre d'honneur";
                    break;
                default:
                    $type = "";
                    break;
            }
            switch ($tab['cotiz']) {
                case 0:
                    $cotiz = "non payée";
                    break;
                case 1:
                    $cotiz = "payée";
                    break;
                default:
                    $cotiz = "";
                    break;
            } 
            $csvcsv .=  $tab['tribu'] . ";" . $tab['nom'] . ";" . $tab['prenom'] . ";" . $type . ";" . $cotiz . "\n";
            }
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