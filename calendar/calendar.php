<?php
/*
version:	2.0
date:		2007-12-22

author:		Jim Mayes
email:		jim.mayes@gmail.com
web:		style-vs-substance.com

note:		Example 8 - Full Year-at-a-time Calendar

copyright:	Use of this script is goverened by the terms of the 
			Creative Commons Attribution-Share Alike 3.0 License
			http://creativecommons.org/licenses/by-sa/3.0/
			
			Author reserves the right to grant licenses for commercial use.
*/

//--------------------------------------------------- include calendar.class.php
require_once('calendar.class.php');

//--------------------------------------- check $_GET for date passed from links
$date = ( isset($_GET['date']) )? $_GET['date'] : date("Y-m-d");

//--------------------------------------------------- initialize calendar object
/*
Dynamic Date
*/
$calendar = new Calendar($date);

?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>calendar.class.php - Example 8 - Full Year-at-a-time Calendar</title>
<style type="text/css">
<!--
@import url("base_calendar_style.css");
-->
</style>
</head>

<body>
<?php
//-------------------------------------------------------------- output calendar
echo ("<ol id=\"year\">\n");
for($i=1;$i<=12;$i++){
	echo ("<li>");
	echo ($calendar->output_calendar($calendar->year, $i));
	echo ("</li>\n");
}
echo ("</ol>");
?>

<p><a href="index.html">back to examples</a></p>
</body>
</html>
<!--
calendar.class.php v2.0
copyright © 2007 Jim Mayes
licensed under: Creative Commons Attribution-Share Alike 3.0 License (http://creativecommons.org/licenses/by-sa/3.0/)
This class may not be used for commercial purposes without written consent.
Visit style-vs-substance.com for information and updates
-->