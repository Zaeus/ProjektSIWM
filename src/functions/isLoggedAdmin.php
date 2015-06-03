<?php
function isLoggedAdmin($hasloSql, $_SESSION){
    if (isset($_SESSION['login']) && ($_SESSION['haslo'] == $hasloSql)) {
        if ($_SESSION['uprawnienia'] == "admin") {
            return true;
        } else {
            echo "Nie posiadasz uprawnieñ admina";
            return false;
        }
    }
    else{
        echo "Brak uprawnieñ do tre¶ci.<br>";
        return false;
    }
}
?>