<?
	$host  = "mysql.agh.edu.pl";
	$baza  = "wbuczek";
	$user  = "wbuczek";
	$passwd  = "20fLxc5K";
	
	//polaczenie z serwerem bazy
	@mysql_connect($host, $user, $passwd) or die('Brak polaczenia z serwerem MySQL.');
				
	// Otwieramy baze danych
	@mysql_select_db($baza) or die('Blad wyboru bazy danych.');		
	mysql_query("SET CHARSET utf8");
	mysql_query("SET NAMES `utf8` COLLATE `utf8_polish_ci`"); 	
?>