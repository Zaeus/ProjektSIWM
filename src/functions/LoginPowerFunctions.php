<?php
// Funkcja isLoggedAdmin - określa czy osoba zalogowana posiada uprawnienia administratorskie
function isLoggedAdmin($hasloSql, $userLogin, $userPassword, $userPower)
{
    if (isset($userLogin) && ($userPassword == $hasloSql)) {
        if ($userPower == "admin") {
            return true;
        } else {
            return false;
        }
    }
    else{
        echo "Brak uprawnień do treści.<br>";
        return false;
    }
}

// Funkcja isLoggedAdmin - określa czy osoba zalogowana posiada uprawnienia doktora
function isLoggedDoctor($hasloSql, $userLogin, $userPassword, $userPower){
    if (isset($userLogin) && ($userPassword == $hasloSql)) {
        if ($userPower == "lekarz" || $userPower == "admin") {
            return true;
        } else {
            return false;
        }
    }
    else{
        echo "Brak uprawnień do treści.<br>";
        return false;
    }
}

// Funkcja isLoggedAdmin - określa czy osoba zalogowana posiada uprawnienia pacjenta
function isLoggedPatient($hasloSql, $userLogin, $userPassword){
    if (isset($userLogin) && ($userPassword == $hasloSql)) {
        return true;
    }
    else{
        echo "Brak uprawnień do treści.<br>";
        return false;
    }
}
?>
