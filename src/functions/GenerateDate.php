<?
// Funkcja generuj±ca pola wyboru OPTION selektrora SELECT
function generateDate($start, $stop, $visitDuration){
    if ($start <= $stop) {
        $dataFormula = "";
        for ($current = clone($start); $current <= $stop; date_modify($current, '+'.$visitDuration)) {
            $formatedData = date_format($current, 'H:i');
            $formatedDataSeconds = date_format($current, 'H:i:s');
            $dataFormula .= "<option value=" . $formatedDataSeconds . ">$formatedData</option>";
        }
        echo $dataFormula;
    }
}
?>
