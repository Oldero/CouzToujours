<?php
// Généalogie et coordonnées, photos ?
//NB : familha = famille en Auvergnat 
    session_start ();
?>
  

<!DOCTYPE html>

<html>

<head>
    <?php include("../include/style.php"); ?>
    <title>Œuvres et archives familiales</title>
</head>


<body>

    <?php include("../include/entete.php"); ?>
    <?php include("../include/laterale.php"); ?>

    <section class="corps">
    	<table class="table_docs">
    		<tr><td class="unique_case justify_center" rowspan=2 width=120px height=185px><img height="156px" width="108px" src="../img/oeuvres/contes.jpg" title="Les contes de Bon-Papa"></td>
    			<td width=600px class="unique_case">Pour les grandes et les petites têtes... Pour marquer un grand anniversaire ! Voici des histoires que notre bon-papa nous a toujours racontées. Des histoires à lire, à relire et à faire passer ! </td></tr>
    		<tr><td class="unique_case">Mahauld Dubus, Caroline Froissart, Mayeule des Robert</br>2009 - Autoédition</td></tr>
    	</table>
        <table class="table_docs">
            <tr><td class="unique_case justify_center" rowspan=2 width=120px height=185px></td>
                <td width=600px class="unique_case">Discours de Bon-Papa aux cent ans de mariage : 4 x 25 ans de Sylvie et Christophe, Béatrice et Christophe, Chantal et Benoît, Gérard et Myriam</td></tr>
            <tr><td class="unique_case"><a href="../docs/discours_original2005.pdf">Le discours original</a>&nbsp&nbsp&nbsp&nbsp<a href="../docs/discours2005.pdf">Le discours retranscrit</a></td></tr>
        </table>
        <table class="table_docs">
            <tr><td class="unique_case justify_center" rowspan=2 width=120px height=185px><img height="156px" width="108px" src="../img/oeuvres/memoires.jpg" title="Mémoires de Bon-Papa"></td>
                <td width=600px class="unique_case">Les mémoires de Bon-Papa</td></tr>
            <tr><td class="unique_case"><a href="https://drive.google.com/file/d/1gqOHDA1LUdl2ClsIfc3yK0Nrjt-EuFkv/view">Les mémoires</a></td></tr>
        </table>
    </section>
    </section>

    <?php include("../include/pieddepage.php"); ?>

</body>

</html>
