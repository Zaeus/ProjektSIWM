<?php
function isLoggedAdmin($hasloSql, $_SESSION){
    if (isset($_SESSION['login']) && ($_SESSION['haslo'] == $hasloSql)) {
        if ($_SESSION['uprawnienia'] == "admin") {
            return true;
        } else {
            echo "Nie posiadasz uprawnie� admina";
            return false;
        }
    }
    else{
        echo "Brak uprawnie� do tre�ci.<br>";
        return false;
    }
}
?>