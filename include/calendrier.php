<?php


if (!isset($_GET['mois']) && !isset($_GET['annee']))
{
$calendrier_date_mois = date('n');
$calendrier_date_annee = date('Y');
}
else
{
$calendrier_date_mois = $_GET['mois'];
$calendrier_date_annee = $_GET['annee'];
}

if ($calendrier_date_mois == '1')
{
$calendrier_date_mois_precedent = '12';
$calendrier_date_annee_precedente = $calendrier_date_annee - 1;
}
else
{
$calendrier_date_mois_precedent = $calendrier_date_mois - 1;
$calendrier_date_annee_precedente = $calendrier_date_annee;
}

if ($calendrier_date_mois == '12')
{
$calendrier_date_mois_suivant = '1';
$calendrier_date_annee_suivante = $calendrier_date_annee + 1;
}
else
{
$calendrier_date_mois_suivant = $calendrier_date_mois + 1;
$calendrier_date_annee_suivante = $calendrier_date_annee;
}

$calendrier_dateDuJour = date('j_n_Y');

$calendrier_dates_importantes = array( '1_6_2006',

'5_6_2006',

'15_8_2006',
'14_7_2006',
'20_7_2006',

'1_1_2007');


$calendrier_mktime = mktime(0, 0, 0, $calendrier_date_mois, 1, $calendrier_date_annee);

$calendrier_date_mois_1erjour = date('w', $calendrier_mktime);

$calendrier_date_mois_nombrejour = date('t', $calendrier_mktime);

$calendrier_mois = array( '1' => 'Janvier', '2' => 'Février', '3' => 'Mars',
'4' => 'Avril', '5' => 'Mai', '6' => 'Juin',
'7' => 'Juillet', '8' => 'Août', '9' => 'Septembre',
'10' => 'Octobre', '11' => 'Novembre', '12' => 'Décembre');
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<STYLE type="text/css">
@IMPORT URL(css.css);
</STYLE>
<title>Calendrier - floptwo</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<body>
<?php

if ($calendrier_date_mois.'_'.$calendrier_date_annee == date('n_Y'))
{
$class_mois = 'calendrier_mois_encours';
}
else
{
$class_mois = 'calendrier_mois';
}
?>
<table width="200" border="0" align="center" cellpadding="1" cellspacing="1">
<tr align="center" valign="middle">
<td><?php echo '<a href="?mois=' , $calendrier_date_mois_precedent , '&annee=' , $calendrier_date_annee_precedente , '" class="calendrier_mois"><</a>'?></td>
<td colspan="5" class="<?php echo $class_mois ?>"><?php echo $calendrier_mois[$calendrier_date_mois],' ',$calendrier_date_annee ?></td>
<td><?php echo '<a href="?mois=' , $calendrier_date_mois_suivant , '&annee=' , $calendrier_date_annee_suivante , '" class="calendrier_mois">></a>'?></td>
</tr>
<tr>
<td class="calendrier_nom_des_jours">Lun</td>
<td class="calendrier_nom_des_jours">Mar</td>
<td class="calendrier_nom_des_jours">Mer</td>
<td class="calendrier_nom_des_jours">Jeu</td>
<td class="calendrier_nom_des_jours">Ven</td>
<td class="calendrier_nom_des_jours">Sam</td>
<td class="calendrier_nom_des_jours">Dim</td>
</tr>
<?php
$calendrier_compteur_jours = 0;
while ($calendrier_compteur_jours <= $calendrier_date_mois_nombrejour)

{
?>
<tr>
<?php

for ($i = 0 ; $i <= 6 ; $i++)
{
if ($i == date('w', mktime(0,0,0, $calendrier_date_mois, $calendrier_compteur_jours, $calendrier_date_annee)))
{
$calendrier_compteur_jours++;
}

if ($calendrier_compteur_jours.'_'.$calendrier_date_mois.'_'.$calendrier_date_annee == $calendrier_dateDuJour)
{
$class_jour = 'calendrier_dateDuJour';
}
else
{
if (in_array($calendrier_compteur_jours.'_'.$calendrier_date_mois.'_'.$calendrier_date_annee, $calendrier_dates_importantes))
{
$class_jour = 'calendrier_date_importante';
}
else
{
$class_jour = 'calendrier_date';
}
}

?>
<td class="<?php echo $class_jour ?>">
<?php


if ($calendrier_compteur_jours != 0 && $calendrier_compteur_jours <= $calendrier_date_mois_nombrejour)
{
echo $calendrier_compteur_jours;
}
else
{
echo ' ';
}
?>
</td>
<?php
}
?>
</tr>
<?php
}
?> 