<?php header('Content-Type: text/html; charset=ISO-8859-2'); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
  <head>
    <meta http-equiv="content-type" content="text/html; charset=ISO-8859-2">
  </head>
  <body>
    <b>Projekt bazy danych SIWM</b><br><br>	
			<?
			echo date_format(new DateTime(), 'Y-m-d');
            ?>
            <br />
	<hr />
	<div id="Stopka">
		<form action="index.php" method="post">
			<input type="submit" value="Powrót do strony głównej" />
		</form>
		<form action="log2.php" method="post">
			<input type="submit" value="Powrót do strony logowania" />
		</form>
	</div>
	<h5>
		Projekt bazy danych pacjentów: Wojciech Buczek wbuczek@student.agh.edu.pl <br>
		@AGH EAIIB
	</h5>
</body>
</html>
