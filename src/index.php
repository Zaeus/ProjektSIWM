<?
	session_start();
	if(isset($_SESSION['login'])){		
			$_SESSION = array();
			session_unset();
			session_destroy();
	}	
?>
<?
	include("naglowek.php");	
?>
    <div style="text-align: center;"><img src="Zdrowexerex.jpg" alt="Zdrowexerex" /><br><br>
	<h2>Witaj w projektowej bazie danych - Zdrowexerex.</h2><br>
	Mo¿esz siê zalogowaæ lub zarejestrowaæ:<br><br>
<?	
	$forma = "<form action = \"log2.php\" method=\"POST\"> ";
	$forma .= "<input type=\"submit\" value=\"Loguj\" >";
	$forma .= "</form>";
	echo $forma;
	$forma = "<form action = \"reg.php\" method=\"POST\"> ";
	$forma .= "<input type=\"submit\" value=\"Rejestruj\" >";
	$forma .= "</form><br/>";	
	echo $forma;
?>
	<div style="text-align: center;"><img src="usecase.png" alt="Use Case Projektu" /><br>
	Rys. 1 - Zamys³ projektu</div><br>
    <div style="text-align: center;"><img src="medycyna-spoleczna.jpg" alt="Motto" /><br>
    Rys. 2 - Motto zak³adowe</div><br>
<?
	include("stopka.php");
?>