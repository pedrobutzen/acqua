<?php

header('Content-type: text/html; charset=UTF-8');
include_once '../conexao/conexao.php';

$sql_user = mysqli_query($conect, "SELECT usuario FROM acqua_db.usuario WHERE permissao='3'");

while ($row_user = mysqli_fetch_array($sql_user)) {
    $usuario = $row_user['usuario'];

    $sql_peca_geral = mysqli_query($conect, "SELECT peca.idpeca FROM acqua_db.peca LEFT JOIN(ocorrencia) ON(peca.idpeca=ocorrencia.idpeca) WHERE (ISNULL(ocorrencia.status) OR ocorrencia.status='0') AND peca.status='1' AND usuario='$usuario' LIMIT 0, 3;");
    while ($row_peca = mysqli_fetch_array($sql_peca_geral)) {
        $idpeca = $row_peca['idpeca'];
        $texto_sql = "INSERT INTO ocorrencia (descricao, status, idpeca, idtipo_ocorrencia) VALUES ('" . utf8_decode("Descrição - ") . rand(0, 6000) . "', '1', '$idpeca', '" . rand(1, 3) . "');";
        $sql_insert_peca = mysqli_query($conect, $texto_sql);
    }
}
echo "OK";

