	<?
	// Kwerenda wykorzystywana do otrzymania z bazy danych zakodowanego has�a w celu poprawnej identyfikacji osoby loguj�cej si�
	
	$kwerenda = "SELECT email, haslo, nazwisko FROM nazwiska WHERE email = \"" . $_SESSION['login'] . "\"";
	$logowanie = mysql_query($kwerenda)	or die('Błąd logowania');
	if($logowanie){
		$wiersz = mysql_fetch_assoc($logowanie);
		$hasloSql = $wiersz['haslo'];
	}
	?>