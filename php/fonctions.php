<?php
// liste des fonctions utilisées ailleurs
function convertdate($date){
    $tdate = explode("-", $date);
    //$frenchdate = array($tdate[2], "mois", $tdate[0]);
    switch ($tdate[1]){
        case 1:
            $mois = "janvier";
            break;
        case 2:
            $mois = "février";
            break;    
        case 3:
            $mois = "mars";
            break;
        case 4:
            $mois = "avril";
            break;
        case 5:
            $mois = "mai";
            break;
        case 6:
            $mois = "juin";
            break;
        case 7:
            $mois = "juillet";
            break;
        case 8:
            $mois = "août";
            break;
        case 9:
            $mois = "septembre";
            break;
        case 10:
            $mois = "octobre";
            break;
        case 11:
            $mois = "novembre";
            break;
        case 12:
            $mois = "décembre";
            break;
    }

    return (" " . $tdate[2] . " " . $mois . " " . $tdate[0] . " " );
}

// NbJours("2000-10-20", "2000-10-21") retourne 1

function NbJours($debut, $fin) {

    $tDeb = explode("-", $debut);
    $tFin = explode("-", $fin);

    $diff = mktime(0, 0, 0, $tFin[1], $tFin[2], $tFin[0]) - mktime(0, 0, 0, $tDeb[1], $tDeb[2], $tDeb[0]);
  
    return(($diff / 86400));
}

function day($date){
    $tdate = explode("-", $date);
    $day = date("w", mktime(0, 0, 0, $tdate[1], $tdate[2], $tdate[0]));
  switch ($day){
        case 1:
            $jour = "lundi";
            break;
        case 2:
            $jour = "mardi";
            break;    
        case "3":
            $jour = "mercredi";
            break;
        case 4:
            $jour = "jeudi";
            break;
        case 5:
            $jour = "vendredi";
            break;
        case 6:
            $jour = "samedi";
            break;
        case 0:
            $jour = "dimanche";
            break;
        default:
            $jour = "vendremanche";
    }
    return ($jour); 
}


?>