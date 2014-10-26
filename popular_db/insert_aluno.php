<?php

header('Content-type: text/html; charset=UTF-8');
include_once '../conexao/conexao.php';

$inseridosh = 0;
$inseridosm = 0;

for ($i = 0; $i < 750; $i++) {
    $rand1 = rand(10000, 99999);
    $texto_sql = "INSERT INTO usuario (usuario, nome, sexo, senha, quarto, ramal, permissao) VALUES ('$rand1', '" . utf8_decode("João da Silva -" . $rand1) . "', 'm', '1234', '" . rand(100, 999) . "', '" . rand(1000, 9999) . "', '3');";
    $sql = mysqli_query($conect, $texto_sql);
    if (mysqli_affected_rows($conect)) {
        $inseridosh++;
    }
}
for ($i = 0; $i < 520; $i++) {
    $rand1 = rand(10000, 99999);
    $texto_sql = "INSERT INTO usuario (usuario, nome, sexo, senha, quarto, ramal, permissao) VALUES ('$rand1', '" . utf8_decode("Maria Souza -" . $rand1) . "', 'f', '1234', '" . rand(100, 999) . "', '" . rand(1000, 9999) . "', '3');";
    $sql = mysqli_query($conect, $texto_sql);
    if (mysqli_affected_rows($conect)) {
        $inseridosm++;
    }
}
echo "Foram inseridos " . $inseridosh." Usuários homens <br>";
echo "Foram inseridos " . $inseridosm." Usuários mulheres <br>";
