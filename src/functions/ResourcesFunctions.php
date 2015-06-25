<?php
// Funkcja drawAllResourcesTable najpierw pobiera wszystki nazwy kolumn z tabeli o podanej nazwie, a następnie w
// oparciu o nie jest budowana kwerenda pobierająca wszystki dane z tej tabeli
function drawAllResourcesTable($tableName)
{
    $columnNameQuery = "SELECT COLUMN_NAME FROM information_schema.columns WHERE table_name = '" . $tableName . "' ORDER BY ordinal_position";
    $columnNameResult = mysql_query($columnNameQuery) or die('Błąd zapytania o nazwy kolumn');
    $columnCount = mysql_num_rows($columnNameResult);
    $allResourcesQuery = "SELECT ";
    $iterator = 0;
    echo "<fieldset><legend>Tabela: " . $tableName . "</legend>";
    echo "<div class=\"CSSTableGenerator\" ><table align=\"center\" cellpadding=\"5\" border=\"1\">";
    echo "<tr>";
    while($columnNameLine = mysql_fetch_assoc($columnNameResult)) {
        if($iterator < $columnCount-1) {
            echo "<td>" . $columnNameLine['COLUMN_NAME'] . "</td>";
            $allResourcesQuery .= $columnNameLine['COLUMN_NAME'] . ", ";
            $iterator++;
        } else {
            echo "<td>" . $columnNameLine['COLUMN_NAME'] . "</td>";
            $allResourcesQuery .= $columnNameLine['COLUMN_NAME'] . " FROM " . $tableName;
        }
    }
    echo "</tr>";
    $allResourcesQuery .= " WHERE 1";
    $allResourcesResult = mysql_query($allResourcesQuery) or die('Bład zapytania o zawartość tabeli');
    while($allResourcesLine = mysql_fetch_assoc($allResourcesResult)) {
        echo "<tr>";
        foreach($allResourcesLine as $key => $value){
            echo "<td>" . $value . "</td>";
        }
        echo "</tr>";
    }
    echo "</table></div></fieldset><br>";
}

function specialization($specjalizacja, $tabelaSpecjalizacje, $selectName='nowa_specjalnosc', $selectText = 'Specjalność gabinetu:', $uprawnienia ='lekarz' ){
    $form_gab = " $selectText <select name=\"$selectName\"";
    if($uprawnienia=='lekarz') {
        $form_gab.=">";
        foreach ($tabelaSpecjalizacje as $spec => $value) {
            if ($specjalizacja == $value) {
                $form_gab .= "<option value=\"$value\" selected=\"selected\">$value</option>";
            } else {
                $form_gab .= "<option value=\"$value\" >$value</option>";
            }
        }
    }else{
        $form_gab .= "disabled>";
    }
    $form_gab .= "</select>";
    echo $form_gab;
}
?>
