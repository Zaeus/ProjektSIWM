<?php
function isLoggedDoctor($hasloSql, $userLogin, $userPassword, $userPower){
    if (isset($userLogin) && ($userPassword == $hasloSql)) {
        if ($userPower == "lekarz" || $userPower == "admin") {
            return true;
        } else {
            return false;
        }
    }
    else{
        echo "Brak uprawnieñ do tre¶ci.<br>";
        return false;
    }
}
?>
