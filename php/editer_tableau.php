<?php
//page php pour effectuer l'édition de tableaux récapitulatifs

    include("../doctor/bdd.php");

    // on teste si nos variables sont définies
if (isset($_POST['tri']) && isset($_POST['cotiz']) && isset($_POST['adh']) && isset($_POST['select'])) {
        //query à distinquer selon les critères de choix.
        $csvcsv = "Date d'édition :;" . date("Y-m-d") . "\n";
        $csvcsv .="Tribu;Nom;Prénom;Type;Cotiz\n";
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
                $sql_query .= " WHERE cotiz IN (0,1)";
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
//réponse du formulaire édition réservation
elseif(isset($_POST['resa_tri']) && isset($_POST['passe']) && isset($_POST['reglement']) && isset($_POST['annee_resa'])){
    $date = date("Y-m-d");
    $csvcsv = "Date d'édition :;" . date("Y-m-d") . "\n";
    $csvcsv .= "Du;Au;Nom;Par;;Privatisé;WE offert;;Réglé ?";
    if($_POST['show_occ'] == "Oui") {$csvcsv .= ";;grosdub;ptitdub;7-18ans;0-7ans;;visi PR;visi TR;7-18ans;0-7ans;;total";}
    $csvcsv .= "\n";
    $sql_query = "SELECT * FROM reservation";
    switch ($_POST['reglement']) {
        case "resa_non_payee":
            $sql_query .= " WHERE paye = 0";
            break;
        case "resa_payee":
            $sql_query .= " WHERE paye = 1";
            break;
        case "resa_les_deux":
            $sql_query .= " WHERE paye IN (0,1)";
            break;
        default:
            break;
    }
    switch ($_POST['passe']) {
        case "past":
            $sql_query .= " AND fin < CURDATE()";
            break;
        case "future":
            $sql_query .= " AND fin >= CURDATE()";
            break;
        case "toutes":
            break;
        default:
            break;
    }
    switch ($_POST['annee_resa']) {
        case "0000":
            $sql_query .= " AND fin >= '" . $_POST['cur_year'] . "-09-01'";
            break;
        case '9999':
            break;
        default:
            $sql_query .= " AND fin BETWEEN '" . $_POST['annee_resa'] . "-09-01' AND '" . ($_POST['annee_resa']+1) . "-08-31'";
            break;
    }
    switch ($_POST['resa_tri']) {
        case "tri_login":
            $sql_query .= " ORDER BY username,debut,fin,paye ";
            break;
        case "tri_resa_regle":
            $sql_query .= " ORDER BY paye,debut,fin,username";
            break;
        case "tri_resa_date":
            $sql_query_past = $sql_query . " AND fin < CURDATE()";
            $sql_query_future = $sql_query . " AND fin >= CURDATE()";
            $sql_query_past .= " ORDER BY debut,fin,username,paye";
            $sql_query_future .= " ORDER BY debut,fin,username,paye";
            break;
        default:
            break;
    }
/*    echo $sql_query . ";";
    echo $sql_query_past . ";";
    echo $sql_query_future . ";"; */
    if ($_POST['resa_tri'] != "tri_resa_date" || $_POST['annee_resa'] != "0000"){
        $reponse = $bdd->query($sql_query);
        while ($tab = $reponse->fetch()){
            switch ($tab['prive']) {
                case 0:
                    $privatise = "Non";
                    break;
                case 1:
                    $privatise = "Oui";
                    break;
                default:
                    break;
            }
            switch ($tab['we_gratuit']) {
                case 0:
                    $we_off = "Non";
                    break;
                case 1:
                    $we_off = "Oui";
                    break;
                default:
                    break;
            }
            switch ($tab['paye']) {
                case 0:
                    $reglement = "Non";
                    break;
                case 1:
                    $reglement = "Oui";
                    break;
                default:
                    break;
            }
            $total_occ = $tab['nbgrosdub'] + $tab['nbptitdub'] + $tab['nb_adh_plus7'] + $tab['nb_adh_toddler'] + $tab['nbvis_pt'] + $tab['nbvis_tr'] + $tab['nbvis_enf'] + $tab['nbvis_toddler'];
            $csvcsv .= $tab['debut'] . ";" . $tab['fin'] . ";" . $tab['nom'] . ";" . $tab['username'] . ";;" . $privatise . ";" . $we_off . ";;" . $reglement;
            if($_POST['show_occ'] == "Oui") {$csvcsv .= ";;" . $tab['nbgrosdub'] . ";" . $tab['nbptitdub'] . ";" . $tab['nb_adh_plus7'] . ";" . $tab['nb_adh_toddler'] . ";;" . $tab['nbvis_pt'] . ";" . $tab['nbvis_tr'] . ";" . $tab['nbvis_enf'] . ";" . $tab['nbvis_toddler'] . ";;" . $total_occ;}
            $csvcsv .= "\n";
        }
        $reponse->closeCursor();
    }
    else{
        $csvcsv .= "Réservations à venir : \n";
        $reponse = $bdd->query($sql_query_future);
        while ($tab = $reponse->fetch()){
            switch ($tab['prive']) {
                case 0:
                    $privatise = "Non";
                    break;
                case 1:
                    $privatise = "Oui";
                    break;
                default:
                    break;
            }
            switch ($tab['we_gratuit']) {
                case 0:
                    $we_off = "Non";
                    break;
                case 1:
                    $we_off = "Oui";
                    break;
                default:
                    break;
            }
            switch ($tab['paye']) {
                case 0:
                    $reglement = "Non";
                    break;
                case 1:
                    $reglement = "Oui";
                    break;
                default:
                    break;
            }
            $total_occ = $tab['nbgrosdub'] + $tab['nbptitdub'] + $tab['nb_adh_plus7'] + $tab['nb_adh_toddler'] + $tab['nbvis_pt'] + $tab['nbvis_tr'] + $tab['nbvis_enf'] + $tab['nbvis_toddler'];
            $csvcsv .= $tab['debut'] . ";" . $tab['fin'] . ";" . $tab['nom'] . ";" . $tab['username'] . ";;" . $privatise . ";" . $we_off . ";;" . $reglement;
            if($_POST['show_occ'] == "Oui") {$csvcsv .= ";;" . $tab['nbgrosdub'] . ";" . $tab['nbptitdub'] . ";" . $tab['nb_adh_plus7'] . ";" . $tab['nb_adh_toddler'] . ";;" . $tab['nbvis_pt'] . ";" . $tab['nbvis_tr'] . ";" . $tab['nbvis_enf'] . ";" . $tab['nbvis_toddler'] . ";;" . $total_occ;}
            $csvcsv .= "\n";
        }
        $reponse->closeCursor();
        $csvcsv .= "Réservations passées : \n";
        $reponse = $bdd->query($sql_query_past);
        while ($tab = $reponse->fetch()){
            switch ($tab['prive']) {
                case 0:
                    $privatise = "Non";
                    break;
                case 1:
                    $privatise = "Oui";
                    break;
                default:
                    break;
            }
            switch ($tab['we_gratuit']) {
                case 0:
                    $we_off = "Non";
                    break;
                case 1:
                    $we_off = "Oui";
                    break;
                default:
                    break;
            }
            switch ($tab['paye']) {
                case 0:
                    $reglement = "Non";
                    break;
                case 1:
                    $reglement = "Oui";
                    break;
                default:
                    break;
            }
            $total_occ = $tab['nbgrosdub'] + $tab['nbptitdub'] + $tab['nb_adh_plus7'] + $tab['nb_adh_toddler'] + $tab['nbvis_pt'] + $tab['nbvis_tr'] + $tab['nbvis_enf'] + $tab['nbvis_toddler'];
            $csvcsv .= $tab['debut'] . ";" . $tab['fin'] . ";" . $tab['nom'] . ";" . $tab['username'] . ";;" . $privatise . ";" . $we_off . ";;" . $reglement;
            if($_POST['show_occ'] == "Oui") {$csvcsv .= ";;" . $tab['nbgrosdub'] . ";" . $tab['nbptitdub'] . ";" . $tab['nb_adh_plus7'] . ";" . $tab['nb_adh_toddler'] . ";;" . $tab['nbvis_pt'] . ";" . $tab['nbvis_tr'] . ";" . $tab['nbvis_enf'] . ";" . $tab['nbvis_toddler'] . ";;" . $total_occ;}
            $csvcsv .= "\n";
        }
        $reponse->closeCursor();
    }
    $csv = utf8_decode($csvcsv);
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