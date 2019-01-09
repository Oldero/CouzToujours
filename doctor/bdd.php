<?php
	$bdd_host = "localhost";
	$bdd_name = "couztoujours";
	$bdd_user = "root";
	$bdd_pwd = "root";
	try
    {
        // On se connecte à MySQL
        $bdd = new PDO('mysql:host=' . $bdd_host . ';dbname=' . $bdd_name . ';charset=utf8', $bdd_user, $bdd_pwd);
    }
    catch(Exception $e)
    {
        // En cas d'erreur, on affiche un message et on arrête tout
            die('Erreur : '.$e->getMessage());
    }
?>