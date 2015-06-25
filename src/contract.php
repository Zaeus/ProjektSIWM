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
    if(isset($_POST['newContract'])){
        $contractQuery = "UPDATE nazwiska SET kontrakt='" . $_POST['newContract'] . "', pozostalo_kontraktu = '" . $_POST['newContract'] . "' WHERE id_nazwiska = '" . $_POST['docID'] . "'";
        mysql_query($contractQuery) or die('Błąd zapytania nowej wartości kontraktu');
    }
    echo "<br><fieldset><legend>Tabela lekarzy znajdujących się w bazie danych</legend>";
    echo "<div class=\"CSSTableGenerator\"><table align=\"center\" cellpadding=\"5\" border=\"1\">";
    echo "<tr>";
    echo "<td>Imię</td>";
    echo "<td>Nazwisko</td>";
    echo "<td>Email</td>";
    echo "<td>Specjalizacja</td>";
    echo "<td>Początkowa liczba godzin</td>";
    echo "<td>Akutalna liczba godzin</td>";
    echo "<td>Opcje</td>";
    echo "</tr>";
    $docQuery = "SELECT * FROM nazwiska WHERE uprawnienia = 'lekarz'";
    $docResult = mysql_query($docQuery) or die('Błąd zapytania o dane do kontraktów');
    while($docLine = mysql_fetch_assoc($docResult)){
        echo "<tr>";
        echo "<td>" . $docLine['imie'] . "</td>";
        echo "<td>" . $docLine['nazwisko'] . "</td>";
        echo "<td>" . $docLine['email'] . "</td>";
        echo "<td>" . $docLine['specjalizacja'] . "</td>";
        echo "<td>" . $docLine['kontrakt'] . "</td>";
        echo "<td>" . $docLine['pozostalo_kontraktu'] . "</td>";
        echo "<td>";
        echo "<form action = \"contract.php\" method=\"POST\"> ";
        echo "<input type=\"hidden\" name=\"docID\" value=\"" . $docLine['id_nazwiska'] . "\">";
        echo "<input type=\"number\" name=\"newContract\" value=\"200\" ><br>";
        echo "<input type=\"submit\" value=\"Przedłuż kontrakt\" >";
        echo "</form>";
        echo "</td>";
        echo "</tr>";
    }
    echo "</table></div></fieldset>";
}
?>
<?
include("includes/footer.php");
?>
