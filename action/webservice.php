<?php

header('Content-type: text/html; charset=UTF-8');
include_once '../conexao/conexao.php';

$action_pagina = $_GET['action_pagina'];
$action = $_GET['action'];

switch ($action_pagina) {
    case "peca":
        switch ($action) {
            case 'montar':
                $action_id = $_GET['idpeca'];
                $resultados = mysqli_query($conect, "SELECT peca.usuario, peca.idpeca, peca.descricao, peca.marca, "
                        . "peca.cor, peca.tamanho, tipo.nome as nometipo, tipo.idtipo FROM peca JOIN(tipo) "
                        . "ON(peca.idtipo = tipo.idtipo) WHERE idpeca='$action_id'");
                if (mysqli_num_rows($resultados) == 0) {
                    $result = array('erro' => true, 'msg_erro' => 'Nenhuma peça encontrada.');
                } else {
                    $result = array('erro' => false);
                    $row = mysqli_fetch_array($resultados);
                    $id_peca = $row["idpeca"];
                    $resultados1 = mysqli_query($conect, "SELECT ocorrencia.*, tipo_ocorrencia.tipo FROM ocorrencia "
                            . "JOIN(tipo_ocorrencia) ON(ocorrencia.idtipo_ocorrencia=tipo_ocorrencia.idtipo_ocorrencia) "
                            . "WHERE idpeca='$id_peca' ORDER BY status DESC");
                    $ocorrencias = array('qtd_ocorrencias' => mysqli_num_rows($resultados1));
                    if (mysqli_num_rows($resultados1) > 0) {
                        while ($row2 = mysqli_fetch_array($resultados1)) {
                            $ocorrencia = array(
                                'idocorrencia' => utf8_encode($row2['idocorrencia']),
                                'descricao' => utf8_encode($row2['descricao']),
                                'status' => utf8_encode($row2['status']),
                                'idpeca' => utf8_encode($row2['idpeca']),
                                'tipoocorrencia' => utf8_encode($row2['tipo']),
                            );
                            array_push($ocorrencias, $ocorrencia);
                        }
                    }
                    $dados = array(
                        'usuario' => utf8_encode($row["usuario"]),
                        'idpeca' => utf8_encode($row["idpeca"]),
                        'descricaopeca' => utf8_encode($row["descricao"]),
                        'marca' => utf8_encode($row["marca"]),
                        'cor' => utf8_encode($row["cor"]),
                        'tamanho' => utf8_encode($row["tamanho"]),
                        'idtipo' => utf8_encode($row["idtipo"]),
                        'nometipo' => utf8_encode($row["nometipo"]),
                        'ocorrencia' => $ocorrencias,
                    );
                    array_push($result, $dados);
                }
                break;
            default :
                break;
        }
        break;
    case "lancamento":
        switch ($action) {
            case 'listar':
                $usuario_pesquisado = utf8_decode($_GET['usuario']);
                $qtd_geral_sql = mysqli_query($conect, "SELECT peca.*, tipo.nome as nometipo FROM "
                        . "peca JOIN(lancamento_has_peca, lancamento, tipo) ON(peca.idpeca=lancamento_has_peca.idpeca "
                        . "AND lancamento_has_peca.idlancamento=lancamento.idlancamento AND peca.idtipo=tipo.idtipo) "
                        . "WHERE lancamento.usuario='$usuario_pesquisado' AND !ISNULL(lancamento.data_recebimento) "
                        . "AND ISNULL(lancamento.data_devolucao) ORDER BY tipo.nome");
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
