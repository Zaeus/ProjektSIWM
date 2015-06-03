<?
// Funkcja generująca pola wyboru OPTION selektrora SELECT
function generateDate($start, $stop) {
    if($start<=$stop) {
        $dataFormula = "";
        for ($current = $start; $current != $stop; date_modify($current, '+30 minutes')) {
            $formatedData = date_format($current, 'H:i');
            $formatedDataSeconds = date_format($current, 'H:i:s');
            +            $dataFormula .= "<option value=" . $formatedDataSeconds . ">$formatedData</option>";
        }
        $formatedData = date_format($stop, 'H:i');
        $formatedDataSeconds = date_format($stop, 'H:i:s');
        $dataFormula .= "<option value=" . $formatedDataSeconds . ">$formatedData</option>";
        echo $dataFormula;
     }
}
?>
