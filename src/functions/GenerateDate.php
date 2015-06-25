<?
// Funkcja generująca pola wyboru OPTION selektrora SELECT
function generateDate($start, $stop){
    if ($start <= $stop) {
        $dataFormula = "";
        for ($current = clone($start); $current <= $stop; date_modify($current, '+'.VISIT_DURATION)) {
            $formatedData = date_format($current, 'H:i');
            $formatedDataSeconds = date_format($current, 'H:i:s');
            $dataFormula .= "<option value=" . $formatedDataSeconds . ">$formatedData</option>";
        }
        echo $dataFormula;
    }
}
?>
