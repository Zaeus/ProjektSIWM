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
	Witaj w projektowej bazie danych.<br>
	Możesz się zalogować lub zarejestrować:<br><br>
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
	<center><img src="usecase.png" alt="Use Case Projektu" /><br>
	Rys. 1 - Zamysł projektu</center><br>
<?
	include("stopka.php");
?>