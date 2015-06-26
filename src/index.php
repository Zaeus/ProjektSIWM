<?
	session_start();
	if(isset($_SESSION['login'])){		
			$_SESSION = array();
			session_unset();
			session_destroy();
	}	
?>
<?
    include("includes/header.php");
?>
    <h2>Witaj w projektowej bazie danych SIWM - Zdrowexerex.</h2><br>
	Możesz się zalogować lub zarejestrować:<br><br>
<?	
	$forma = "<form action = \"log.php\" method=\"POST\"> ";
	$forma .= "<input type=\"submit\" value=\"Loguj\" >";
	$forma .= "</form>";
	echo $forma;
	$forma = "<form action = \"reg.php\" method=\"POST\"> ";
	$forma .= "<input type=\"submit\" value=\"Rejestruj\" >";
	$forma .= "</form><br/>";	
	echo $forma;
?>
	<div style="text-align: center;"><img src="images/UseCase.png" alt="Use Case Projektu" /><br>
	Rys. 1 - Zamysł projektu</div><br>
    <div style="text-align: center;"><img src="images/mysqlDbProject.png" alt="Use Case Projektu" /><br>
    Rys. 2 - Poglądowy schemat bazy danych</div><br>
    <div style="text-align: center;"><img src="images/MedycynaSpoleczna.jpg" alt="Motto" /><br>
    Rys. 3 - Motto zakładowe</div><br>
<?
	include("includes/footer.php");
?>
