	<?
	// Kwerenda wykorzystywana do otrzymania z bazy danych zakodowanego has³a w celu poprawnej identyfikacji osoby loguj¹cej siê
	
	$kwerenda = "SELECT email, haslo, nazwisko FROM nazwiska WHERE email = \"" . $_SESSION['login'] . "\"";
	$logowanie = mysql_query($kwerenda)	or die('B³¹d logowania');
	if($logowanie){
		$wiersz = mysql_fetch_assoc($logowanie);
		$hasloSql = $wiersz['haslo'];
	}
	?>