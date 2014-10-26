<?php

header('Content-type: text/html; charset=UTF-8');
include_once '../conexao/conexao.php';

$sql_ocorrencia = mysqli_query($conect, "SELECT idocorrencia FROM acqua_db.ocorrencia WHERE status='1'");
while ($row_ocorrencia = mysqli_fetch_array($sql_ocorrencia)) {
    $idocorrencia = $row_ocorrencia['idocorrencia'];
    $texto_sql = "UPDATE acqua_db.ocorrencia SET status='0' WHERE idocorrencia='$idocorrencia';";
    $sql_insert_peca = mysqli_query($conect, $texto_sql);
}
echo "OK";
