<?php header('Content-Type: text/html; charset=ISO-8859-2'); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
  <head>
    <meta http-equiv="content-type" content="text/html; charset=ISO-8859-2">
  </head>
  <body>
    <b>Projekt bazy danych SIWM</b><br><br>
    <div style="text-align: center;"><img src="Zdrowexerex.jpg" alt="Zdrowexerex" /><br><br>			
			
			<?
			$form = "<div id=\"RegForm\">";
            $form .= "<form action=\"reg.php\" method=\"post\">";
            $form .= "<label for=\"Imię\"></label><input type=\"text\" name=\"ImieReg\" placeholder=\"Imię\" id=\"ImieReg\" value=\"" . $_POST['ImieReg'] . "\"/><br>";
            $form .= "<label for=\"Nazwisko\"></label><input type=\"text\" name=\"NazwiskoReg\" placeholder=\"Nazwisko\" id=\"NazwiskoReg\" value=\"" . $_POST['NazwiskoReg'] . "\"/><br>";
            $form .= "<label for=\"Email\"></label><input type=\"email\" name=\"EmailReg\" placeholder=\"Email\" id=\"EmailReg\" value=\"" . $_POST['EmailReg'] . "\"/><br>";
            $form .= "<label for=\"Powtórz Email\"></label><input type=\"email\" name=\"EmailReg2\" placeholder=\"Email2\" id=\"EmailReg2\" value=\"" . $_POST['EmailReg2'] . "\"/><br>";
            $form .= "<label for=\"Hasło\"></label><input type=\"password\" name=\"HasloReg\" placeholder=\"Hasło\" id=\"HasloReg\" value=\"" . $_POST['HasloReg'] . "\"/><br>";
            $form .= "<label for=\"Powtórz hasło\"></label><input type=\"password\" name=\"HasloReg2\" placeholder=\"Hasło2\" id=\"HasloReg2\" value=\"" . $_POST['HasloReg2'] . "\"/><br>";
            $form .= "<input type=\"radio\" name=\"Radio\" id=\"Lekarz\" value=\"lekarz\"checked=\"checked\"/><label for=\"Lekarz\">Lekarz</label><br>";
            $form .= "<input type=\"radio\" name=\"Radio\" id=\"Pacjent\" value=\"pacjent\" disabled=\"disabled\"/><label for=\"Pacjent\">Pacjent</label><br><br>";
            echo $form;
            ?>
                    <input type="radio" name="Radio2" id="Spec" value="Interna" checked="checked" /><label for="Internista">Internista</label><br>
                    <input type="radio" name="Radio2" id="Spec" value="Ginekologia"/><label for="Ginekolog">Ginekologia</label><br>
                    <input type="radio" name="Radio2" id="Spec" value="USG"/><label for="USG">USG</label><br>
                    <input type="submit" value="Dokończ rejestrację" /><br><br>
                </form>
            </div>
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
