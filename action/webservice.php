<?php

header('Content-type: text/html; charset=UTF-8');
include_once '../conexao/conexao.php';

$action_pagina = $_GET['action_pagina'];
$action = $_GET['action'];

switch ($action_pagina) {
    case "peca":

        break;
    case "lancamento":
        switch ($action) {
            case 'listar':
                $usuario_pesquisado = utf8_decode($_GET['usuario']);
                $qtd_geral_sql = mysqli_query($conect, "SELECT peca.*, tipo.nome as nometipo FROM peca JOIN(lancamento_has_peca, lancamento, tipo) ON(peca.idpeca=lancamento_has_peca.idpeca AND lancamento_has_peca.idlancamento=lancamento.idlancamento AND peca.idtipo=tipo.idtipo) WHERE lancamento.usuario='$usuario_pesquisado' AND !ISNULL(lancamento.data_recebimento) AND ISNULL(lancamento.data_devolucao) ORDER BY tipo.nome");
                $qtd_geral = mysqli_num_rows($qtd_geral_sql);
                $result = array(
                    'qtd_geral' => $qtd_geral,
                );

                if (mysqli_num_rows($qtd_geral_sql) > 0) {
                    while ($row = mysqli_fetch_array($qtd_geral_sql)) {
                        $dados = array(
                            'idpeca' => utf8_encode($row["idpeca"]),
                            'descricao' => utf8_encode($row["descricao"]),
                            'marca' => utf8_encode($row["marca"]),
                            'cor' => utf8_encode($row["cor"]),
                            'tamanho' => utf8_encode($row["tamanho"]),
                            'nometipo' => utf8_encode($row["nometipo"]),
                        );
                        array_push($result, $dados);
                    }
                }
                break;
            default :
                break;
        }
        break;
    default:
        $result = array('erro' => true, 'msg_erro' => 'Página não encontrada.');
        break;
}

mysqli_close($conect);

echo json_encode($result);
?>
