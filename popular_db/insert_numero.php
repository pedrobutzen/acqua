<?php

header('Content-type: text/html; charset=UTF-8');
include_once '../conexao/conexao.php';

$inseridosh = 0;
$inseridosm = 0;

for ($i = 0; $i < 50; $i++) {
    $rand1 = rand(700, 750);
    if ($rand1 < 10) {
        $rand1 = "00" . $rand1 . "M";
    } elseif ($rand1 >= 10 && $rand1 < 100) {
        $rand1 = "0" . $rand1 . "M";
    } else {
        $rand1 = $rand1 . "M";
    }
    $texto_sql = "INSERT INTO num_lavanderia (num, sexo) VALUES ('$rand1', 'm');";
    $sql = mysqli_query($conect, $texto_sql);
    if (mysqli_affected_rows($conect)) {
        $inseridosh++;
    }
}
for ($i = 0; $i < 20; $i++) {
    $rand1 = rand(500, 520);
    if ($rand1 < 10) {
        $rand1 = "00" . $rand1;
    } elseif ($rand1 >= 10 && $rand1 < 100) {
        $rand1 = "0" . $rand1;
    }
    $texto_sql = "INSERT INTO num_lavanderia (num, sexo) VALUES ('$rand1', 'f');";
    $sql = mysqli_query($conect, $texto_sql);
    if (mysqli_affected_rows($conect)) {
        $inseridosm++;
    }
}
echo "Foram inseridos " . $inseridosh . " Números homens <br>";
echo "Foram inseridos " . $inseridosm . " Números mulheres <br>";
