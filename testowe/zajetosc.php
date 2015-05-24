<?php header('Content-Type: text/html; charset=ISO-8859-2'); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
  <head>
    <meta http-equiv="content-type" content="text/html; charset=ISO-8859-2">
  </head>
  <body>
    <b>Projekt bazy danych SIWM</b><br><br>	
			<?
            include("Projekt/polaczenieSQL.php");
            $kwerenda_PON = "SELECT ID_nazwiska_Lek, od_dnia, do_dnia, od_godziny, do_godziny FROM zajetosc WHERE dzien_tyg='Pon' AND ID_gabinetu='1'";
            echo $kwerenda_PON;
            $wynik_PON = mysql_query($kwerenda_PON) or die('Błąd zapytania');
            if($wynik_PON) {
                while($wiersz_PON = mysql_fetch_assoc($wynik_PON)) {
                    echo $wiersz_PON['ID_nazwiska_Lek'] . "<br>" . $wiersz_PON['od_dnia'] . "<br>" . $wiersz_PON['do_dnia'] . "<br>" . $wiersz_PON['od_godziny'] . "<br>" . $wiersz_PON['do_godziny'];
                }
            }
            ?>
            <br />
	<hr />
	<div id="Stopka">
		<form action="index.php" method="post">
			<input type="submit" value="Powr�t do strony g��wnej" />
		</form>
		<form action="log2.php" method="post">
			<input type="submit" value="Powr�t do strony logowania" />
		</form>
	</div>
	<h5>
		Projekt bazy danych pacjent�w: Wojciech Buczek wbuczek@student.agh.edu.pl <br>
		@AGH EAIIB
	</h5>
</body>
</html>
