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
            }
            else if(isset($_POST['Dalej'])) {
                date_modify($_SESSION['data'], '+1 week');
                unset($_POST['Dalej']);
            }
            elseif(!isset($_POST)||isset($_POST['initGabinet'])){
                $_SESSION['data'] = new DateTime();
            }
            while((date_format($_SESSION['data'],'l'))!="Monday"){
                date_modify($_SESSION['data'], '-1 day');
            }
            $endDate = clone $_SESSION['data'];
            date_modify($endDate, '+4 day');
            echo "Pocz±tek tygodnia:" . date_format($_SESSION['data'], 'Y-m-d') . "<br>Koniec tygodnia:" . date_format($endDate, 'Y-m-d') . "<br>";
            ?>
            <form action="gabinet.php" method="POST">
			    <input type="submit" value="Tydzieñ wstecz" name="Wstecz" />
                <input type="submit" value="Tydzieñ w przód" name="Dalej" />
            </form>
            <?
            echo "<br><fieldset><legend>Zajmij gabinet:</legend><form action=\"gabinet.php\" method=\"POST\">";
            if(isset($_POST['Dzien'])){
                $_SESSION['Dzien'] = $_POST['Dzien'];
                echo "Godzina rozpoczêcia: " . "<select name=\"GodzinaRozpoczecia\">";
                generateDate(date_create('7:00'), date_create('19:00'));
                echo "</select><br><input type=\"submit\" value=\"Dalej\" /></form></fieldset>";
            }
            elseif(isset($_POST['GodzinaRozpoczecia'])){
                $_SESSION['GodzinaRozpoczecia'] = $_POST['GodzinaRozpoczecia'];
                $timeBegin = date_create($_SESSION['GodzinaRozpoczecia']);
                echo "Godzina zakoñczenia: " . "<select name=\"GodzinaZakonczenia\">";
                generateDate(date_modify($timeBegin,'+2 hours'), date_create('21:00'));
                echo "</select><br><input type=\"submit\" value=\"Dalej\" /></form></fieldset>";
            }
            elseif(isset($_POST['GodzinaZakonczenia'])){
                $_SESSION['GodzinaZakonczenia'] = $_POST['GodzinaZakonczenia'];
                echo "Data rozpoczêcia najmu gabinetu: <input type=\"date\" name=\"OdDnia\" placeholder=\"Data rozpoczêcia najmu gabinetu\" value=\"" . date_format(new DateTime(), 'Y-m-d') . "\">";
                echo "<br><input type=\"submit\" value=\"Dalej\" /></form></fieldset>";
            }
            elseif(isset($_POST['OdDnia'])){
                $_SESSION['OdDnia'] = $_POST['OdDnia'];
                echo "Data zakoñczenia najmu gabinetu: <input type=\"date\" name=\"DoDnia\" placeholder=\"Data zakoñczenia najmu gabinetu\" value=\"" . date_format(date_modify(new DateTime(), '+1 week'), 'Y-m-d') . "\">";
                echo "<br><input type=\"submit\" value=\"Zajmij\" /></form></fieldset>";
            }
            else{
                if(isset($_POST['DoDnia'])){
                    echo $_SESSION['Dzien'] . "<br>";
                    echo $_SESSION['GodzinaRozpoczecia'] . "<br>";
                    echo $_SESSION['GodzinaZakonczenia'] . "<br>";;
                    echo $_SESSION['OdDnia'] . "<br>";;
                    echo $_POST['DoDnia'] . "<br>";;
                    //TODO Tutaj ma byæ obs³u¿enie formularza, bo wychodz±c z tego ifa niszczymy dane
                    // $kwerenda_wpisu terminu pasuj±cego do bazy danych
                    // przed wpisaniem data/godzina powinna byæ sprawdzona z terminem w bazie danych
                    unset($_SESSION['Dzien']);
                    unset($_SESSION['GodzinaRozpoczecia']);
                    unset($_SESSION['GodzinaZakonczenia']);
                    unset($_SESSION['OdDnia']);
                    unset($_POST['DoDnia']);
                }
                echo "</select><br>";
                echo "<table align=\"center\" cellpadding=\"5\" border=\"1\">";
                echo "<tr>";
                echo "<td><input type=\"radio\" name=\"Dzien\" value=\"Pon\">Poniedzia³ek</td>";
                echo "<td><input type=\"radio\" name=\"Dzien\" value=\"Wto\">Wtorek</td>";
                echo "<td><input type=\"radio\" name=\"Dzien\" value=\"Sro\">¦roda</td>";
                echo "<td><input type=\"radio\" name=\"Dzien\" value=\"Czw\">Czwartek</td>";
                echo "<td><input type=\"radio\" name=\"Dzien\" value=\"Pia\">Pi±tek<br></td>";
                echo "</tr></table><input type=\"submit\" value=\"Dalej\" /><br><br></form></fieldset>";
            }

            // TODO Dorobiæ zapisy/modyfikacjê zajmowania gabinetów
            // TODO Kwerenda pobieraj±ca dane oparte o aktualny dzieñ tygodnia
            // TODO Nastêpnie pobrana data jest porównywana z aktualn± (czy siê mie¶ci - jak siê mie¶ci to sprawdzanie dalszej zajêto¶ci godziny)
            // TODO sprawdzana jest godzina czy nie jest zajêta
            // TODO jak po tym wszystkim nie jest zajêta to mo¿na dodaæ nowy rekord rezerwacji gabinetu
            // TODO zapytanie o wszystkie gabinety od dni do dnia z wy¶wietleniem jego specjalizacji - je¿eli jest wolny siê wy¶wietli, w przeciwnym wypadku ignoruj
            // TODO zajêcie gabinetu w danym dniu nie krócej ni¿ 2h nie d³u¿ej ni¿ 8h
            $docOfficeViewQuery = "SELECT ID_gabinetu FROM zajetosc";
            $viewOfficeResult = mysql_query($docOfficeViewQuery) or die('B³±d zapytania');
            $docOfficeViewFrom = "<br><fieldset><legend>Przejrzyj zajêto¶æ gabinet:</legend><form action = \"gabinet.php\" method=\"POST\">";
            $docOfficeViewFrom .= "Gabinet: <select name=\"ID_przegladany_gabinet\">";
            if($viewOfficeResult) {
                $iterator = 0;
                while($docOfficeViewLine = mysql_fetch_assoc($viewOfficeResult)) {
                    $docOfficeTable[$iterator] = $docOfficeViewLine['ID_gabinetu'];
                    $iterator = $iterator + 1;
                }
                sort($docOfficeTable);
                $docOfficeTable = array_unique($docOfficeTable);
                foreach($docOfficeTable as $key => $val){
                    $docOfficeViewFrom .= "<option value=\"" . $val . "\">" . $val . "</option>";
                }
            }
            $docOfficeViewFrom .= "</select>";
            $docOfficeViewFrom .= "<input type=\"submit\" value=\"Przegl±daj gabinet\" >";
            $docOfficeViewFrom .= "</form>";
            echo $docOfficeViewFrom;

            if(isset($_POST['ID_przegladany_gabinet'])){
                $_SESSION['ID_przegladany_gabinet'] = $_POST['ID_przegladany_gabinet'];
            }

            echo "Przegl±dany gabinet: " . $_SESSION['ID_przegladany_gabinet'];
            drawTable(clone $_SESSION['data'], $_SESSION['ID_przegladany_gabinet']);
            echo "</fieldset>";
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
