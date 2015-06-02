<?
	session_start();
?>
<?
	include("includes/naglowek.php");
	include("includes/polaczenieSQL.php");
	include("includes/kwerenda_log.php");
    include("functions/CheckTime.php");
    include("functions/GenerateDate.php");
    include("functions/drawTable.php");
    include("functions/reservationTable.php");
    include("functions/reservationQuery.php");
    include("functions/viewMyReservationTable.php");
    include("functions/reservationRemoveQuery.php");
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
            echo "Pocz�tek tygodnia:" . date_format($_SESSION['data'], 'Y-m-d') . "<br>Koniec tygodnia:" . date_format($endDate, 'Y-m-d') . "<br>";
            ?>
            <form action="gabinet.php" method="POST">
			    <input type="submit" value="Tydzie� wstecz" name="Wstecz" />
                <input type="submit" value="Tydzie� w prz�d" name="Dalej" />
            </form>
            <?
            // IF przechwytuj�cy dane pochodz�ce z tabeli rezerwacji tworzonej przez funkcj� reservaionTable
            if(isset($_POST['Day']) && isset($_POST['SinceDate']) && isset($_POST['ToDate']) && isset($_POST['FromTime']) && isset($_POST['ToTime']) && isset($_POST['ID_Gabinetu'])) {
                reservationQuery($_SESSION['login'], $_POST['ID_Gabinetu'], $_POST['Day'], $_POST['SinceDate'], $_POST['ToDate'], $_POST['FromTime'], $_POST['ToTime']);
            }
            echo "<br><fieldset><legend>Zajmij gabinet:</legend><form action=\"gabinet.php\" method=\"POST\">";
            if(isset($_POST['Dzien'])) {
                $_SESSION['Dzien'] = $_POST['Dzien'];
                echo "Godzina rozpocz�cia: " . "<select name=\"GodzinaRozpoczecia\">";
                generateDate(date_create('7:00'), date_create('19:00'));
                echo "</select><br><input type=\"submit\" value=\"Dalej\" /></form></fieldset>";
            } elseif(isset($_POST['GodzinaRozpoczecia'])) {
                $_SESSION['GodzinaRozpoczecia'] = $_POST['GodzinaRozpoczecia'];
                $timeBegin = date_create($_SESSION['GodzinaRozpoczecia']);
                echo "Godzina zako�czenia: " . "<select name=\"GodzinaZakonczenia\">";
                generateDate(date_modify($timeBegin,'+2 hours'), date_create('21:00'));
                echo "</select><br><input type=\"submit\" value=\"Dalej\" /></form></fieldset>";
            } elseif(isset($_POST['GodzinaZakonczenia'])) {
                $_SESSION['GodzinaZakonczenia'] = $_POST['GodzinaZakonczenia'];
                echo "Data rozpocz�cia najmu gabinetu: <input type=\"date\" name=\"OdDnia\" placeholder=\"Data rozpocz�cia najmu gabinetu\" value=\"" . date_format(new DateTime(), 'Y-m-d') . "\">";
                echo "<br><input type=\"submit\" value=\"Dalej\" /></form></fieldset>";
            } elseif(isset($_POST['OdDnia'])) {
                $_SESSION['OdDnia'] = $_POST['OdDnia'];
                echo "Data zako�czenia najmu gabinetu: <input type=\"date\" name=\"DoDnia\" placeholder=\"Data zako�czenia najmu gabinetu\" value=\"" . date_format(date_modify(new DateTime(), '+1 week'), 'Y-m-d') . "\">";
                echo "<br><input type=\"submit\" value=\"Zajmij\" /></form></fieldset>";
            } else {
                if(isset($_POST['DoDnia'])){
                    reservationTable($_SESSION['Dzien'], $_SESSION['GodzinaRozpoczecia'], $_SESSION['GodzinaZakonczenia'], $_SESSION['OdDnia'], $_POST['DoDnia'], $_SESSION['login']);
                    unset($_SESSION['Dzien']);
                    unset($_SESSION['GodzinaRozpoczecia']);
                    unset($_SESSION['GodzinaZakonczenia']);
                    unset($_SESSION['OdDnia']);
                    unset($_POST['DoDnia']);
                }
                echo "</select><br>";
                echo "<table align=\"center\" cellpadding=\"5\" border=\"1\">";
                echo "<tr>";
                echo "<td><input type=\"radio\" name=\"Dzien\" value=\"Pon\">Poniedzia�ek</td>";
                echo "<td><input type=\"radio\" name=\"Dzien\" value=\"Wto\">Wtorek</td>";
                echo "<td><input type=\"radio\" name=\"Dzien\" value=\"Sro\">�roda</td>";
                echo "<td><input type=\"radio\" name=\"Dzien\" value=\"Czw\">Czwartek</td>";
                echo "<td><input type=\"radio\" name=\"Dzien\" value=\"Pia\">Pi�tek<br></td>";
                echo "</tr></table><input type=\"submit\" value=\"Dalej\" /><br><br></form></fieldset>";
            }
            // TODO Kwerenda pobieraj�ca dane oparte o aktualny dzie� tygodnia
            // TODO zapytanie o wszystkie gabinety od dni do dnia z wy�wietleniem jego specjalizacji - je�eli jest wolny si� wy�wietli, w przeciwnym wypadku ignoruj
            // TODO zaj�cie gabinetu w danym dniu nie kr�cej ni� 2h nie d�u�ej ni� 8h

            // Usuni�cie rezerwacja je�eli w tabeli powsta�ej w ViewMyReservationTable zostaje klikni�ty przycisk usu�
            if(isset($_POST['RemoveDay']) && isset($_POST['RemoveSinceDate']) && isset($_POST['RemoveToDate']) && isset($_POST['RemoveFromTime']) && isset($_POST['RemoveToTime']) && isset($_POST['RemoveID_Gabinetu'])){
                ReservationRemoveQuery($_SESSION['login'], $_POST['RemoveID_Gabinetu'], $_POST['RemoveDay'], $_POST['RemoveFromTime'], $_POST['RemoveToTime'], $_POST['RemoveSinceDate'], $_POST['RemoveToDate']);
            }
            ViewMyReservationTable($_SESSION['login']);
            $docOfficeViewQuery = "SELECT ID_gabinetu FROM zajetosc";
            $viewOfficeResult = mysql_query($docOfficeViewQuery) or die('B��d zapytania');
            $docOfficeViewFrom = "<br><fieldset><legend>Przejrzyj zaj�to�� gabinet:</legend><form action = \"gabinet.php\" method=\"POST\">";
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
            $docOfficeViewFrom .= "<input type=\"submit\" value=\"Przegl�daj gabinet\" >";
            $docOfficeViewFrom .= "</form>";
            echo $docOfficeViewFrom;

            if(isset($_POST['ID_przegladany_gabinet'])){
                $_SESSION['ID_przegladany_gabinet'] = $_POST['ID_przegladany_gabinet'];
            }

            echo "Przegl�dany gabinet: " . $_SESSION['ID_przegladany_gabinet'];
            drawTable(clone $_SESSION['data'], $_SESSION['ID_przegladany_gabinet']);
            echo "</fieldset>";
		}
		else {
			echo "Nie posiadasz uprawnie� lekarza";
		}
	}
	else{
		echo "Brak uprawnie� do tre�ci.<br>";
	}
?>
<?
	include("includes/stopka.php");
?>
