<?php

header('Content-type: text/html; charset=UTF-8');
include_once '../conexao/conexao.php';

$sql_user = mysqli_query($conect, "SELECT usuario FROM acqua_db.usuario WHERE permissao='3'");

while ($row_user = mysqli_fetch_array($sql_user)) {
    $usuario = $row_user['usuario'];
    $sql_select_lancamento = mysqli_query($conect, "SELECT idlancamento FROM lancamento WHERE usuario='$usuario' AND ISNULL(data_recebimento);");
    if (mysqli_num_rows($sql_select_lancamento) != 0) {
        $row_lancamento = mysqli_fetch_array($sql_select_lancamento);
        $id_lancamento = $row_lancamento['idlancamento'];
        mysqli_query($conect, "DELETE FROM lancamento_has_peca WHERE idlancamento='$id_lancamento'");
        $result = array('erro' => "success", 'msg_success' => 'As alterações no lançamento foram salvas com sucesso.');
    } else {
        $sql_insert_lancamento = mysqli_query($conect, "INSERT INTO lancamento (usuario) VALUES ('$usuario');");
        $id_lancamento = mysqli_insert_id($conect);
    }

    $sql_peca_geral = mysqli_query($conect, "SELECT idpeca, status FROM acqua_db.peca WHERE usuario='$usuario' ORDER BY descricao LIMIT 0, 15;");
    while ($row_peca = mysqli_fetch_array($sql_peca_geral)) {
        $idpeca = $row_peca['idpeca'];
        if ($row_peca['status'] == "0") {
            mysqli_query($conect, "DELETE FROM lancamento WHERE idlancamento='$id_lancamento'");
            $result = array('erro' => true, 'msg_erro' => 'Peça marcada não encontrada.');
        } else {
            $sql_insert_peca = mysqli_query($conect, "INSERT INTO lancamento_has_peca (idpeca, idlancamento) VALUES ('$idpeca', '$id_lancamento');");
        }
    }
}
echo $result;

