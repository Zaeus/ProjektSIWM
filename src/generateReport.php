<?
session_start();
?>
<?php
include("includes/header.php");
include("includes/SQLConnection.php");
include("includes/logQuery.php");
include("functions/LoginPowerFunctions.php");
?>
<?
if (isLoggedAdmin($hasloSql, $_SESSION['login'], $_SESSION['haslo'], $_SESSION['uprawnienia'])) {
    // Formularz zakresu dat z których ma zostać wygenerowany raport zajętości gabinetów
    $reportForm = "<br><fieldset><legend>Generuj raport z zajętości gabinetów w podanym zakresie dat</legend><form action = \"generateReport.php\" method=\"POST\"> ";
    $reportForm .= "Od: <input type=\"date\" name=\"lowDate\" \"><br>";
    $reportForm .= "Do: <input type=\"date\" name=\"highDate\" \"><br>";
    $reportForm .= "<input type=\"submit\" value=\"Generuj raport\" >";
    $reportForm .= "</form></fieldset>";
    echo $reportForm;
    if(isset($_POST['lowDate']) && isset($_POST['highDate']) && ($_POST['lowDate'] <= $_POST['highDate'])){
        echo "<br><fieldset><legend>Raport z zajętości gabinetów pomiędzy " . $_POST['lowDate'] . " a " . $_POST['highDate'] . "</legend>";
        echo "<div class=\"CSSTableGenerator\"><table align=\"center\" cellpadding=\"5\" border=\"1\">";
        echo "<tr>";
        echo "<td>Dane lekarza</td>";
        echo "<td>Dzień tygodnia</td>";
        echo "<td>Od</td>";
        echo "<td>Do</td>";
        echo "<td>Od godziny</td>";
        echo "<td>Do godziny</td>";
        echo "<td>Gabinet</td>";
        echo "</tr>";
        $reportQuery = "SELECT * FROM zajetosc WHERE od_dnia >= '" . $_POST['lowDate'] . "' AND do_dnia <= '" . $_POST['highDate'] . "'";
        $reportResult = mysql_query($reportQuery) or die('Błąd zapytania o dane do raportu');
        while($reportLine = mysql_fetch_assoc($reportResult)){
            $reportInfoQuery = "SELECT nazwisko, imie FROM nazwiska WHERE id_nazwiska = '" . $reportLine['ID_nazwiska_Lek'] . "'";
            $reportInfoResult = mysql_query($reportInfoQuery);
            $reportInfoLine = mysql_fetch_assoc($reportInfoResult);
            echo "<tr>";
            echo "<td> dr " . $reportInfoLine['imie'] . " " . $reportInfoLine['nazwisko'] . "</td>";
            echo "<td>" . $reportLine['dzien_tyg'] . "</td>";
            echo "<td>" . $reportLine['od_dnia'] . "</td>";
            echo "<td>" . $reportLine['do_dnia'] . "</td>";
            echo "<td>" . $reportLine['od_godziny'] . "</td>";
            echo "<td>" . $reportLine['do_godziny'] . "</td>";
            echo "<td>" . $reportLine['ID_gabinetu'] . "</td>";
            echo "</tr>";
        }
        echo "</table></div>";
        echo "</fieldset>";
    }
}
?>
<?
include("includes/footer.php");
?>
