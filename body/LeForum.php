<?php
// Forum à construire 

    session_start ();
?>
  
 
<!DOCTYPE html>

<html>

<head>
    <?php include("../include/style.php"); ?>
    <title>Forum Couz'Toujours</title>
</head>


<body>

    <?php include("../include/entete.php"); ?>
    <?php include("../include/laterale.php"); ?>

    <section class="corps">
        <?php include("../fluxbb-1.5.11/index.php"); ?>           
    </section>

    <?php include("../include/pieddepage.php"); ?>

</body>

</html>