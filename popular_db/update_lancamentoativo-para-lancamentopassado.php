<?php

header('Content-type: text/html; charset=UTF-8');
include_once '../conexao/conexao.php';

$sql_user = mysqli_query($conect, "SELECT usuario FROM acqua_db.usuario WHERE permissao='3'");
while ($row_user = mysqli_fetch_array($sql_user)) {
    $usuario = $row_user['usuario'];
    mysqli_query($conect, "UPDATE lancamento SET usuario_devolucao = 'pedro.butzen', data_devolucao=NOW() WHERE ISNULL(data_devolucao) AND !ISNULL(data_recebimento) AND usuario='$usuario'");
}

