<?php
function isLoggedPatient($hasloSql, $_SESSION){
    if (isset($_SESSION['login']) && ($_SESSION['haslo'] == $hasloSql)) {
        return true;
    }
    else{
        echo "Brak uprawnie� do tre�ci.<br>";
        return false;
    }
}
?>