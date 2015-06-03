<?
	session_start();
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
    include("functions/isLoggedDoctor.php");
    include("functions/dateButtons.php");

    if(isLoggedDoctor($hasloSql, $_SESSION)){
            echo "Posiadasz uprawnienia lekarza<br>";
            dateButtons($_POST);
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
                // TODO test czy pomi�dzy pocz�tkiem a ko�cem jest nie wi�kszy ni� 8h
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

            // Usuni�cie rezerwacja je�eli w tabeli powsta�ej w ViewMyReservationTable zostaje klikni�ty przycisk usu�
            if(isset($_POST['RemoveDay'])){
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
	include("includes/stopka.php");
?>
