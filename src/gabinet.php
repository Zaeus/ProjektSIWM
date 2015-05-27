<?
	session_start();
?>
<?
	include("naglowek.php");	
	include("polaczenieSQL.php");
	include("kwerenda_log.php");
    include("functions/CheckTime.php");
    include("functions/GenerateDate.php");
    include("functions/drawTable.php");
?>
<?
    if(isset($_SESSION['login']) && ($_SESSION['haslo'] == $hasloSql)){
        if($_SESSION['uprawnienia'] == "lekarz" || $_SESSION['uprawnienia'] == "admin") {
            echo "Posiadasz uprawnienia lekarza<br>";
            if(isset($_POST['Wstecz'])){
                date_modify($_SESSION['data'], '-1 week');
                unset($_POST['Wstecz']);
            }else if(isset($_POST['Dalej'])) {
                date_modify($_SESSION['data'], '+1 week');
                unset($_POST['Dalej']);
            }elseif(!isset($_POST)||isset($_POST['initGabinet'])){
                $_SESSION['data'] = new DateTime();
            }
            while((date_format($_SESSION['data'],'l'))!="Monday"){
                date_modify($_SESSION['data'], '-1 day');
            }
            $dataKoniec = clone $_SESSION['data'];
            date_modify($dataKoniec, '+4 day');
            echo "Pocz±tek tygodnia:" . date_format($_SESSION['data'], 'Y-m-d') . "<br>Koniec tygodnia:" . date_format($dataKoniec, 'Y-m-d') . "<br>";
            ?>
            <form action="gabinet.php" method="POST">
			    <input type="submit" value="Tydzieñ wstecz" name="Wstecz" />
                <input type="submit" value="Tydzieñ w przód" name="Dalej" />
            </form>
            <br> Zajmij gabinet <br>
            <?
            echo "<form action=\"gabinet.php\" method=\"POST\">";
            if(isset($_POST['Dzien'])){
                $_SESSION['Dzien'] = $_POST['Dzien'];
                echo "Godzina rozpoczêcia "."<select name=\"GodzinaRozpoczecia\">";
                generateDate(date_create('7:00'), date_create('19:00'));
                echo "<br><input type=\"submit\" value=\"Dalej\" /></form>";
            }elseif(isset($_POST['GodzinaRozpoczecia'])){
                $_SESSION['GodzinaRozpoczecia'] = $_POST['GodzinaRozpoczecia'];
                $poczatek = date_create($_SESSION['GodzinaRozpoczecia']);
                echo "</select>"."Godzina zakoñczenia "."<select name=\"GodzinaZakonczenia\">";
                generateDate(date_modify($poczatek,'+2 hours'), date_create('21:00'));
                echo "<br><input type=\"submit\" value=\"Zajmij\" /></form>";
            }else{
                if(isset($_POST['GodzinaZakonczenia'])){
                    echo $_SESSION['Dzien']."<br>";
                    echo $_SESSION['GodzinaRozpoczecia']."<br>";
                    echo $_POST['GodzinaZakonczenia'];
                    //TODO Tutaj ma byæ obs³u¿enie formularza, bo wychodz±c z tego ifa niszczymy dane
                    // $kwerenda_wpisu terminu pasuj±cego do bazy danych
                    // przed wpisaniem data/godzina powinna byæ sprawdzona z terminem w bazie danych
                    unset ($_SESSION['Dzien']);
                    unset ($_SESSION['GodzinaRozpoczecia']);
                    unset ($_POST['GodzinaZakonczenia']);
                }
                echo "</select><br>";
                echo "<table align=\"center\" cellpadding=\"5\" border=\"1\">";
                echo "<tr>";
                echo "<td><input type=\"radio\" name=\"Dzien\" value=\"Pon\">Poniedzia³ek</td>";
                echo "<td><input type=\"radio\" name=\"Dzien\" value=\"Wto\">Wtorek</td>";
                echo "<td><input type=\"radio\" name=\"Dzien\" value=\"Sro\">¦roda</td>";
                echo "<td><input type=\"radio\" name=\"Dzien\" value=\"Czw\">Czwartek</td>";
                echo "<td><input type=\"radio\" name=\"Dzien\" value=\"Pia\">Pi±tek<br></td>";
                echo "</tr></table><input type=\"submit\" value=\"Dalej\" /><br><br></form>";
            }

            // TODO Dorobiæ zapisy/modyfikacjê zajmowania gabinetów
            // TODO Kwerenda pobieraj±ca dane oparte o aktualny dzieñ tygodnia
            // TODO Nastêpnie pobrana data jest porównywana z aktualn± (czy siê mie¶ci - jak siê mie¶ci to sprawdzanie dalszej zajêto¶ci godziny)
            // TODO sprawdzana jest godzina czy nie jest zajêta
            // TODO jak po tym wszystkim nie jest zajêta to mo¿na dodaæ nowy rekord rezerwacji gabinetu
            // TODO zapytanie o wszystkie gabinety od dni do dnia z wy¶wietleniem jego specjalizacji - je¿eli jest wolny siê wy¶wietli, w przeciwnym wypadku ignoruj
            // TODO zajêcie gabinetu w danym dniu nie krócej ni¿ 2h nie d³u¿ej ni¿ 8h
            $kwerenda_przegladania_gab = "SELECT ID_gabinetu FROM zajetosc";
            $wynik_gab = mysql_query($kwerenda_przegladania_gab) or die('B³±d zapytania');
            $forma_przegladania_gab = "<fieldset><legend>Przejrzyj zajêto¶æ gabinet:</legend><form action = \"gabinet.php\" method=\"POST\">";
            $forma_przegladania_gab .= "Gabinet: <select name=\"ID_przegladany_gabinet\">";
            if($wynik_gab) {
                $iterator = 0;
                while($wiersz_gab = mysql_fetch_assoc($wynik_gab)) {
                    $tablica_gabinetow[$iterator] = $wiersz_gab['ID_gabinetu'];
                    $iterator = $iterator + 1;
                }
                sort($tablica_gabinetow);
                $tablica_gabinetow = array_unique($tablica_gabinetow);
                foreach($tablica_gabinetow as $key => $val){
                    $forma_przegladania_gab .= "<option value=\"" . $val . "\">" . $val . "</option>";
                }
            }
            $forma_przegladania_gab .= "</select>";
            $forma_przegladania_gab .= "<input type=\"submit\" value=\"Przegl±daj gabinet\" >";
            $forma_przegladania_gab .= "</form>";
            echo $forma_przegladania_gab;

            if(isset($_POST['ID_przegladany_gabinet'])){
                $_SESSION['ID_przegladany_gabinet'] = $_POST['ID_przegladany_gabinet'];
            }

            echo "Przegl±dany gabinet: " . $_SESSION['ID_przegladany_gabinet'];
            drawTable(clone $_SESSION['data'], $_SESSION['ID_przegladany_gabinet']);

		}
		else {
			echo "Nie posiadasz uprawnieñ lekarza";
		}
	}
	else{
		echo "Brak uprawnieñ do tre¶ci.<br>";
	}
?>
<?
	include("stopka.php");
?>