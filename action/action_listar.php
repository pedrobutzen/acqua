<?php

header('Content-type: text/html; charset=UTF-8');
include_once '../conexao/conexao.php';

$result = array('erro' => false);

$action_pagina = $_GET['action_pagina'];
$action = $_GET['action'];
@$action_id = $_GET['action_id'];

if (session_id() == '') {
    session_start();
}
if (isset($_SESSION['usuario'])) {
    $usuario_logado = $_SESSION['usuario']['usuario'];
    switch ($action_pagina) {
        case "peca":
            switch ($action) {
                case 'listar':
                    $pag = utf8_decode($_GET['listar_pag']);
                    $maximo = utf8_decode($_GET['listar_qtd_itens']);
                    $inicio = ($pag * $maximo) - $maximo;
                    $qtd_geral = mysqli_query($conect, "SELECT idpeca FROM peca WHERE usuario='$usuario_logado' AND peca.status='1'");
                    $qtd_geral_idioma = mysqli_num_rows($qtd_geral);
                    $qtd_array = array(
                        'qtd_geral' => $qtd_geral_idioma
                    );
                    array_push($result, $qtd_array);
                    $ids[] = "";
                    $resultados = mysqli_query($conect, "SELECT peca.idpeca, ocorrencia.idocorrencia, peca.descricao, peca.marca, peca.cor, peca.tamanho, tipo.idtipo, tipo_ocorrencia.tipo as tipoocorrencia, ocorrencia.status as hasocorrencia FROM peca LEFT JOIN(tipo, ocorrencia, tipo_ocorrencia) ON(peca.idtipo = tipo.idtipo AND peca.idpeca = ocorrencia.idpeca AND ocorrencia.idtipo_ocorrencia = tipo_ocorrencia.idtipo_ocorrencia) WHERE peca.usuario='$usuario_logado' AND peca.status='1' AND (ocorrencia.status='1' OR ISNULL(ocorrencia.status)) ORDER BY ocorrencia.status DESC, tipo.nome, peca.descricao LIMIT $inicio, $maximo");
                    if (mysqli_num_rows($resultados) > 0) {
                        while ($row = mysqli_fetch_array($resultados)) {
                            $id_peca = $row["idpeca"];
                            $resultados2 = mysqli_query($conect, "SELECT t.nome FROM tipo as t JOIN(peca as p) ON(t.idtipo=p.idtipo) WHERE p.idpeca='$id_peca'");
                            $row2 = mysqli_fetch_array($resultados2);
                            $dados = array(
                                'idpeca' => utf8_encode($row["idpeca"]),
                                'descricao' => utf8_encode($row["descricao"]),
                                'marca' => utf8_encode($row["marca"]),
                                'cor' => utf8_encode($row["cor"]),
                                'tamanho' => utf8_encode($row["tamanho"]),
                                'nometipo' => utf8_encode($row2["nome"]),
                                'hasocorrencia' => utf8_encode($row["hasocorrencia"]),
                                'tipoocorrencia' => utf8_encode($row["tipoocorrencia"]),
                            );
                            array_push($result, $dados);
                            $ids[] = $row["idpeca"];
                        }
                    } else {
                        $result = array('erro' => true, 'msg_erro' => 'Nenhuma peça encontrada.');
                    }
                    if (mysqli_num_rows($resultados) < $maximo) {
                        $resultados = mysqli_query($conect, "SELECT peca.idpeca, ocorrencia.idocorrencia, peca.descricao, peca.marca, peca.cor, peca.tamanho, tipo.idtipo, tipo_ocorrencia.tipo as tipoocorrencia, ocorrencia.status as hasocorrencia FROM peca LEFT JOIN(tipo, ocorrencia, tipo_ocorrencia) ON(peca.idtipo = tipo.idtipo AND peca.idpeca = ocorrencia.idpeca AND ocorrencia.idtipo_ocorrencia = tipo_ocorrencia.idtipo_ocorrencia) WHERE peca.usuario='$usuario_logado' AND peca.status='1' AND ocorrencia.status='0' ORDER BY ocorrencia.status DESC, tipo.nome, peca.descricao LIMIT $inicio, $maximo");
                        if (mysqli_num_rows($resultados) > 0) {
                            while ($row = mysqli_fetch_array($resultados)) {
                                $id_peca = $row["idpeca"];
                                if (!in_array($id_peca, $ids)) {
                                    $resultados2 = mysqli_query($conect, "SELECT t.nome FROM tipo as t JOIN(peca as p) ON(t.idtipo=p.idtipo) WHERE p.idpeca='$id_peca'");
                                    $row2 = mysqli_fetch_array($resultados2);
                                    $dados = array(
                                        'idpeca' => utf8_encode($row["idpeca"]),
                                        'descricao' => utf8_encode($row["descricao"]),
                                        'marca' => utf8_encode($row["marca"]),
                                        'cor' => utf8_encode($row["cor"]),
                                        'tamanho' => utf8_encode($row["tamanho"]),
                                        'nometipo' => utf8_encode($row2["nome"]),
                                        'hasocorrencia' => utf8_encode($row["hasocorrencia"]),
                                        'tipoocorrencia' => utf8_encode($row["tipoocorrencia"]),
                                    );
                                    array_push($result, $dados);
                                    $ids[] = $row["idpeca"];
                                }
                            }
                        }
                    }
                    break;
                case 'listarselect':
                    if (isset($_SESSION['usuarioentrada'])) {
                        $usuario_entrada = $_SESSION['usuarioentrada']['usuario'];
                        $qtd_geral = mysqli_query($conect, "SELECT nome, idtipo FROM tipo WHERE ISNULL(usuario) OR usuario='$usuario_entrada' ORDER BY nome");
                    } else {
                        $qtd_geral = mysqli_query($conect, "SELECT nome, idtipo FROM tipo WHERE ISNULL(usuario) OR usuario='$usuario_logado' ORDER BY nome");
                    }
                    $qtd_geral_idioma = mysqli_num_rows($qtd_geral);
                    $qtd_array = array(
                        'qtd_geral' => $qtd_geral_idioma
                    );
                    array_push($result, $qtd_array);

                    if (mysqli_num_rows($qtd_geral) > 0) {
                        while ($row = mysqli_fetch_array($qtd_geral)) {
                            $dados = array(
                                'idtipo' => utf8_encode($row["idtipo"]),
                                'nome' => utf8_encode($row["nome"]),
                            );
                            array_push($result, $dados);
                        }
                    }
                    break;
                default :
                    break;
            }
            break;
        case "cadastrarocorrencia":
            switch ($action) {
                case 'listar':
                    $pag = utf8_decode($_GET['listar_pag']);
                    $maximo = utf8_decode($_GET['listar_qtd_itens']);
                    $inicio = ($pag * $maximo) - $maximo;
                    if ($action_id == "") {
                        $qtd_geral = mysqli_query($conect, "SELECT peca.idpeca FROM peca JOIN(tipo) ON(peca.idtipo = tipo.idtipo)");
                    } else {
                        $qtd_geral = mysqli_query($conect, "SELECT peca.idpeca FROM peca JOIN(tipo) ON(peca.idtipo = tipo.idtipo) WHERE peca.usuario='$action_id'");
                    }
                    $qtd_geral_idioma = mysqli_num_rows($qtd_geral);
                    $qtd_array = array(
                        'qtd_geral' => $qtd_geral_idioma
                    );
                    array_push($result, $qtd_array);
                    if ($action_id == "") {
                        $resultados = mysqli_query($conect, "SELECT peca.*, tipo.nome as nometipo FROM peca JOIN(tipo) ON(peca.idtipo = tipo.idtipo) ORDER BY tipo.nome, peca.descricao LIMIT $inicio, $maximo");
                    } else {
                        $resultados = mysqli_query($conect, "SELECT peca.*, tipo.nome as nometipo FROM peca JOIN(tipo) ON(peca.idtipo = tipo.idtipo) WHERE peca.usuario='$action_id' ORDER BY tipo.nome, peca.descricao LIMIT $inicio, $maximo");
                    }
                    if (mysqli_num_rows($resultados) > 0) {
                        $vazio = false;
                        while ($row = mysqli_fetch_array($resultados)) {
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
                    } else {
                        $result = array('erro' => true, 'msg_erro' => 'Nenhuma peça encontrada.');
                    }
                    break;
                default :
                    break;
            }
            break;
        case "gerenciarocorrencia":
            switch ($action) {
                case 'listar':
                    $pag = utf8_decode($_GET['listar_pag']);
                    $maximo = utf8_decode($_GET['listar_qtd_itens']);
                    $inicio = ($pag * $maximo) - $maximo;
                    if ($action_id == "") {
                        $qtd_geral = mysqli_query($conect, "SELECT peca.idpeca FROM peca JOIN(tipo, ocorrencia, tipo_ocorrencia) ON(peca.idtipo = tipo.idtipo AND peca.idpeca = ocorrencia.idpeca AND ocorrencia.idtipo_ocorrencia = tipo_ocorrencia.idtipo_ocorrencia) WHERE ocorrencia.status='1'");
                    } else {
                        $qtd_geral = mysqli_query($conect, "SELECT peca.idpeca FROM peca JOIN(tipo, ocorrencia, tipo_ocorrencia) ON(peca.idtipo = tipo.idtipo AND peca.idpeca = ocorrencia.idpeca AND ocorrencia.idtipo_ocorrencia = tipo_ocorrencia.idtipo_ocorrencia) WHERE peca.usuario='$action_id' GROUP BY peca.idpeca");
                    }
                    $qtd_geral_idioma = mysqli_num_rows($qtd_geral);
                    $qtd_array = array(
                        'qtd_geral' => $qtd_geral_idioma
                    );
                    array_push($result, $qtd_array);

                    $ids[] = "0";
                    $vazio = true;
                    if ($action_id == "") {
                        $resultados = mysqli_query($conect, "SELECT peca.idpeca, ocorrencia.idocorrencia, peca.descricao, peca.marca, peca.cor, peca.tamanho, tipo.nome as nometipo, tipo_ocorrencia.tipo as tipoocorrencia, ocorrencia.status as ocorrenciastatus, ocorrencia.descricao as ocorrenciadescricao FROM peca JOIN(tipo, ocorrencia, tipo_ocorrencia) ON(peca.idtipo = tipo.idtipo AND peca.idpeca = ocorrencia.idpeca AND ocorrencia.idtipo_ocorrencia = tipo_ocorrencia.idtipo_ocorrencia) WHERE ocorrencia.status='1' ORDER BY tipo.nome, tipo_ocorrencia.tipo, peca.descricao LIMIT $inicio, $maximo");
                    } else {
                        $resultados = mysqli_query($conect, "SELECT peca.idpeca, ocorrencia.idocorrencia, peca.descricao, peca.marca, peca.cor, peca.tamanho, tipo.nome as nometipo, tipo_ocorrencia.tipo as tipoocorrencia, ocorrencia.status as ocorrenciastatus, ocorrencia.descricao as ocorrenciadescricao FROM peca JOIN(tipo, ocorrencia, tipo_ocorrencia) ON(peca.idtipo = tipo.idtipo AND peca.idpeca = ocorrencia.idpeca AND ocorrencia.idtipo_ocorrencia = tipo_ocorrencia.idtipo_ocorrencia) WHERE peca.usuario='$action_id' AND ocorrencia.status='1' ORDER BY tipo.nome, tipo_ocorrencia.tipo, peca.descricao LIMIT $inicio, $maximo");
                    }
                    if (mysqli_num_rows($resultados) > 0) {
                        $vazio = false;
                        while ($row = mysqli_fetch_array($resultados)) {
                            $dados = array(
                                'idpeca' => utf8_encode($row["idpeca"]),
                                'descricao' => utf8_encode($row["descricao"]),
                                'marca' => utf8_encode($row["marca"]),
                                'cor' => utf8_encode($row["cor"]),
                                'tamanho' => utf8_encode($row["tamanho"]),
                                'nometipo' => utf8_encode($row["nometipo"]),
                                'idocorrencia' => utf8_encode($row["idocorrencia"]),
                                'tipoocorrencia' => utf8_encode($row["tipoocorrencia"]),
                                'ocorrenciadescricao' => utf8_encode($row["ocorrenciadescricao"]),
                                'ocorrenciastatus' => utf8_encode($row["ocorrenciastatus"]),
                            );
                            array_push($result, $dados);
                            $ids[] = $row["idpeca"];
                        }
                    }
                    if (mysqli_num_rows($resultados) < $maximo) {
                        $resultados = mysqli_query($conect, "SELECT peca.idpeca, ocorrencia.idocorrencia, peca.descricao, peca.marca, peca.cor, peca.tamanho, tipo.nome as nometipo, tipo_ocorrencia.tipo as tipoocorrencia, ocorrencia.status as ocorrenciastatus, ocorrencia.descricao as ocorrenciadescricao FROM peca JOIN(tipo, ocorrencia, tipo_ocorrencia) ON(peca.idtipo = tipo.idtipo AND peca.idpeca = ocorrencia.idpeca AND ocorrencia.idtipo_ocorrencia = tipo_ocorrencia.idtipo_ocorrencia) WHERE peca.usuario='$action_id' AND ocorrencia.status='0' ORDER BY tipo.nome, tipo_ocorrencia.tipo, peca.descricao LIMIT $inicio, $maximo");
                        if (mysqli_num_rows($resultados) > 0) {
                            $vazio = false;
                            while ($row = mysqli_fetch_array($resultados)) {
                                if (!in_array($row["idpeca"], $ids)) {
                                    $dados = array(
                                        'idpeca' => utf8_encode($row["idpeca"]),
                                        'descricao' => utf8_encode($row["descricao"]),
                                        'marca' => utf8_encode($row["marca"]),
                                        'cor' => utf8_encode($row["cor"]),
                                        'tamanho' => utf8_encode($row["tamanho"]),
                                        'nometipo' => utf8_encode($row["nometipo"]),
                                        'idocorrencia' => utf8_encode($row["idocorrencia"]),
                                        'tipoocorrencia' => utf8_encode($row["tipoocorrencia"]),
                                        'ocorrenciadescricao' => utf8_encode($row["ocorrenciadescricao"]),
                                        'ocorrenciastatus' => utf8_encode($row["ocorrenciastatus"]),
                                    );
                                    array_push($result, $dados);
                                    $ids[] = $row["idpeca"];
                                }
                            }
                        }
                    }
                    if ($vazio) {
                        $result = array('erro' => true, 'msg_erro' => 'Nenhuma peça com ocorrência encontrada.');
                    }
                    break;
                default :
                    break;
            }
            break;
        case "tipoocorrencia":
            switch ($action) {
                case 'listar':
                    $pag = utf8_decode($_GET['listar_pag']);
                    $maximo = utf8_decode($_GET['listar_qtd_itens']);
                    $inicio = ($pag * $maximo) - $maximo;
                    $qtd_geral = mysqli_query($conect, "SELECT * FROM tipo_ocorrencia");
                    $qtd_geral = mysqli_num_rows($qtd_geral);
                    $qtd_array = array(
                        'qtd_geral' => $qtd_geral
                    );
                    array_push($result, $qtd_array);

                    $resultados = mysqli_query($conect, "SELECT * FROM tipo_ocorrencia ORDER BY tipo LIMIT $inicio, $maximo");
                    if (mysqli_num_rows($resultados) > 0) {
                        $vazio = false;
                        while ($row = mysqli_fetch_array($resultados)) {
                            $dados = array(
                                'idtipo_ocorrencia' => utf8_encode($row["idtipo_ocorrencia"]),
                                'tipo' => utf8_encode($row["tipo"]),
                            );
                            array_push($result, $dados);
                        }
                    } else {
                        $result = array('erro' => true, 'msg_erro' => 'Nenhum tipo de ocorrência encontrado.');
                    }
                    break;
                default :
                    break;
            }
            break;
        case "ocorrencia":
            switch ($action) {
                case 'listarselect':
                    $qtd_geral = mysqli_query($conect, "SELECT * FROM tipo_ocorrencia ORDER BY tipo");
                    $qtd_geral_idioma = mysqli_num_rows($qtd_geral);
                    $qtd_array = array(
                        'qtd_geral' => $qtd_geral_idioma
                    );
                    array_push($result, $qtd_array);

                    if (mysqli_num_rows($qtd_geral) > 0) {
                        while ($row = mysqli_fetch_array($qtd_geral)) {
                            $dados = array(
                                'idtipo_ocorrencia' => utf8_encode($row["idtipo_ocorrencia"]),
                                'tipo' => utf8_encode($row["tipo"]),
                            );
                            array_push($result, $dados);
                        }
                    }
                    break;
                case 'listar':
                    $pag = utf8_decode($_GET['listar_pag']);
                    $maximo = utf8_decode($_GET['listar_qtd_itens']);
                    $inicio = ($pag * $maximo) - $maximo;
                    $qtd_geral = mysqli_query($conect, "SELECT peca.idpeca FROM peca JOIN(tipo, ocorrencia, tipo_ocorrencia) ON(peca.idtipo = tipo.idtipo AND peca.idpeca = ocorrencia.idpeca AND ocorrencia.idtipo_ocorrencia = tipo_ocorrencia.idtipo_ocorrencia) WHERE peca.usuario='$usuario_logado' GROUP BY peca.idpeca");
                    $qtd_geral_idioma = mysqli_num_rows($qtd_geral);
                    $qtd_array = array(
                        'qtd_geral' => $qtd_geral_idioma
                    );
                    array_push($result, $qtd_array);

                    $ids[] = "0";
                    $vazio = true;
                    $resultados = mysqli_query($conect, "SELECT peca.idpeca, ocorrencia.idocorrencia, peca.descricao, peca.marca, peca.cor, peca.tamanho, tipo.nome as nometipo, tipo_ocorrencia.tipo as tipoocorrencia, ocorrencia.status as ocorrenciastatus, ocorrencia.descricao as ocorrenciadescricao FROM peca JOIN(tipo, ocorrencia, tipo_ocorrencia) ON(peca.idtipo = tipo.idtipo AND peca.idpeca = ocorrencia.idpeca AND ocorrencia.idtipo_ocorrencia = tipo_ocorrencia.idtipo_ocorrencia) WHERE peca.usuario='$usuario_logado' AND ocorrencia.status='1' ORDER BY tipo.nome, tipo_ocorrencia.tipo, peca.descricao LIMIT $inicio, $maximo");
                    if (mysqli_num_rows($resultados) > 0) {
                        $vazio = false;
                        while ($row = mysqli_fetch_array($resultados)) {
                            $dados = array(
                                'idpeca' => utf8_encode($row["idpeca"]),
                                'descricao' => utf8_encode($row["descricao"]),
                                'marca' => utf8_encode($row["marca"]),
                                'cor' => utf8_encode($row["cor"]),
                                'tamanho' => utf8_encode($row["tamanho"]),
                                'nometipo' => utf8_encode($row["nometipo"]),
                                'idocorrencia' => utf8_encode($row["idocorrencia"]),
                                'tipoocorrencia' => utf8_encode($row["tipoocorrencia"]),
                                'ocorrenciadescricao' => utf8_encode($row["ocorrenciadescricao"]),
                                'ocorrenciastatus' => utf8_encode($row["ocorrenciastatus"]),
                            );
                            array_push($result, $dados);
                            $ids[] = $row["idpeca"];
                        }
                    }
                    if (mysqli_num_rows($resultados) < $maximo) {
                        $resultados = mysqli_query($conect, "SELECT peca.idpeca, ocorrencia.idocorrencia, peca.descricao, peca.marca, peca.cor, peca.tamanho, tipo.nome as nometipo, tipo_ocorrencia.tipo as tipoocorrencia, ocorrencia.status as ocorrenciastatus, ocorrencia.descricao as ocorrenciadescricao FROM peca JOIN(tipo, ocorrencia, tipo_ocorrencia) ON(peca.idtipo = tipo.idtipo AND peca.idpeca = ocorrencia.idpeca AND ocorrencia.idtipo_ocorrencia = tipo_ocorrencia.idtipo_ocorrencia) WHERE peca.usuario='$usuario_logado' AND ocorrencia.status='0' ORDER BY tipo.nome, tipo_ocorrencia.tipo, peca.descricao LIMIT $inicio, $maximo");
                        if (mysqli_num_rows($resultados) > 0) {
                            $vazio = false;
                            while ($row = mysqli_fetch_array($resultados)) {
                                $id_peca = $row["idpeca"];
                                if (!in_array($id_peca, $ids)) {
                                    $dados = array(
                                        'idpeca' => utf8_encode($row["idpeca"]),
                                        'descricao' => utf8_encode($row["descricao"]),
                                        'marca' => utf8_encode($row["marca"]),
                                        'cor' => utf8_encode($row["cor"]),
                                        'tamanho' => utf8_encode($row["tamanho"]),
                                        'nometipo' => utf8_encode($row["nometipo"]),
                                        'idocorrencia' => utf8_encode($row["idocorrencia"]),
                                        'tipoocorrencia' => utf8_encode($row["tipoocorrencia"]),
                                        'ocorrenciadescricao' => utf8_encode($row["ocorrenciadescricao"]),
                                        'ocorrenciastatus' => utf8_encode($row["ocorrenciastatus"]),
                                    );
                                    array_push($result, $dados);
                                    $ids[] = $row["idpeca"];
                                }
                            }
                        }
                    }
                    if ($vazio) {
                        $result = array('erro' => true, 'msg_erro' => 'Nenhuma peça com ocorrência encontrada.');
                    }
                    break;
                default :
                    break;
            }
            break;
        case "lancamentoativo":
            switch ($action) {
                case 'listar':
                    $pag = utf8_decode($_GET['listar_pag']);
                    $maximo = utf8_decode($_GET['listar_qtd_itens']);
                    $inicio = ($pag * $maximo) - $maximo;
                    $sql_qtd_geral = mysqli_query($conect, "SELECT p.idpeca FROM peca as p JOIN(lancamento_has_peca as lp, lancamento as l) ON(p.idpeca=lp.idpeca AND l.idlancamento=lp.idlancamento) WHERE p.usuario='$usuario_logado' AND ISNULL(l.data_devolucao)");
                    $qtd_geral = mysqli_num_rows($sql_qtd_geral);
                    $qtd_array = array(
                        'qtd_geral' => $qtd_geral
                    );
                    array_push($result, $qtd_array);

                    $resultados = mysqli_query($conect, "SELECT p.idpeca, p.descricao, p.marca, p.cor, p.tamanho, t.nome FROM peca as p JOIN(lancamento_has_peca as lp, lancamento as l, tipo as t) ON(p.idpeca=lp.idpeca AND l.idlancamento=lp.idlancamento AND p.idtipo=t.idtipo) WHERE p.usuario='$usuario_logado' AND ISNULL(l.data_devolucao) ORDER BY t.nome, p.descricao LIMIT $inicio, $maximo");
                    if (mysqli_num_rows($resultados) > 0) {
                        while ($row = mysqli_fetch_array($resultados)) {
                            $dados = array(
                                'idpeca' => utf8_encode($row["idpeca"]),
                                'descricao' => utf8_encode($row["descricao"]),
                                'marca' => utf8_encode($row["marca"]),
                                'cor' => utf8_encode($row["cor"]),
                                'tamanho' => utf8_encode($row["tamanho"]),
                                'nometipo' => utf8_encode($row["nome"]),
                            );
                            array_push($result, $dados);
                        }
                    } else {
                        $result = array('erro' => true, 'msg_erro' => 'Nenhuma peça encontrada no lançamento.');
                    }
                    break;
                default:
                    break;
            }
            break;
        case "visualizarlancamentousuario":
            switch ($action) {
                case 'listar':
                    $pag = utf8_decode($_GET['listar_pag']);
                    $maximo = utf8_decode($_GET['listar_qtd_itens']);
                    $inicio = ($pag * $maximo) - $maximo;
                    if ($action_id != "") {
                        $sql_qtd_geral = mysqli_query($conect, "SELECT * FROM lancamento WHERE usuario='$action_id';");
                    } else {
                        $sql_qtd_geral = mysqli_query($conect, "SELECT * FROM lancamento;");
                    }
                    $qtd_geral = mysqli_num_rows($sql_qtd_geral);
                    $qtd_array = array(
                        'qtd_geral' => $qtd_geral
                    );
                    array_push($result, $qtd_array);

                    if ($action_id != "") {
                        $resultados = mysqli_query($conect, "SELECT * FROM lancamento WHERE usuario='$action_id' ORDER BY data_criacao DESC LIMIT $inicio, $maximo");
                    } else {
                        $resultados = mysqli_query($conect, "SELECT * FROM lancamento ORDER BY data_criacao DESC LIMIT $inicio, $maximo");
                    }

                    if (mysqli_num_rows($resultados) > 0) {
                        while ($row = mysqli_fetch_array($resultados)) {
                            $data_criacao = date_format(date_create($row['data_criacao']), 'd/m/Y H:i:s');
                            $data_recebimento = date_format(date_create($row['data_recebimento']), 'd/m/Y H:i:s');
                            $data_devolucao = date_format(date_create($row['data_devolucao']), 'd/m/Y H:i:s');
                            $dados = array(
                                'idlancamento' => utf8_encode($row["idlancamento"]),
                                'data_criacao' => $data_criacao,
                                'data_recebimento' => $data_recebimento,
                                'data_devolucao' => $data_devolucao,
                                'usuario' => utf8_encode($row["usuario"]),
                                'usuario_recebimento' => utf8_encode($row["usuario_recebimento"]),
                                'usuario_devolucao' => utf8_encode($row["usuario_devolucao"]),
                            );
                            array_push($result, $dados);
                        }
                    } else {
                        $result = array('erro' => true, 'msg_erro' => 'Nenhum lançamento encontrado.');
                    }
                    break;
                default:
                    break;
            }
            break;
        case "lancamentospassados":
            switch ($action) {
                case 'listar':
                    $pag = utf8_decode($_GET['listar_pag']);
                    $maximo = utf8_decode($_GET['listar_qtd_itens']);
                    $inicio = ($pag * $maximo) - $maximo;
                    $sql_qtd_geral = mysqli_query($conect, "SELECT * FROM lancamento WHERE usuario='$usuario_logado' AND !ISNULL(data_recebimento) AND !ISNULL(data_devolucao);");
                    $qtd_geral = mysqli_num_rows($sql_qtd_geral);
                    $qtd_array = array(
                        'qtd_geral' => $qtd_geral
                    );
                    array_push($result, $qtd_array);

                    $resultados = mysqli_query($conect, "SELECT * FROM lancamento WHERE usuario='$usuario_logado' AND !ISNULL(data_recebimento) AND !ISNULL(data_devolucao) ORDER BY data_criacao DESC LIMIT $inicio, $maximo");
                    if (mysqli_num_rows($resultados) > 0) {
                        while ($row = mysqli_fetch_array($resultados)) {
                            $data_criacao = date_format(date_create($row['data_criacao']), 'd/m/Y H:i:s');
                            $data_recebimento = date_format(date_create($row['data_recebimento']), 'd/m/Y H:i:s');
                            $data_devolucao = date_format(date_create($row['data_devolucao']), 'd/m/Y H:i:s');
                            $dados = array(
                                'idlancamento' => utf8_encode($row["idlancamento"]),
                                'usuario' => utf8_encode($row["usuario"]),
                                'data_criacao' => $data_criacao,
                                'data_recebimento' => $data_recebimento,
                                'data_devolucao' => $data_devolucao,
                                'usuario_recebimento' => utf8_encode($row["usuario_recebimento"]),
                                'usuario_devolucao' => utf8_encode($row["usuario_devolucao"]),
                            );
                            array_push($result, $dados);
                        }
                    } else {
                        $result = array('erro' => true, 'msg_erro' => 'Nenhum lançamento encontrado.');
                    }
                    break;
                default:
                    break;
            }
            break;
        case "visualizarlancamentousuario":
            switch ($action) {
                case 'listar':
                    $pag = utf8_decode($_GET['listar_pag']);
                    $maximo = utf8_decode($_GET['listar_qtd_itens']);
                    $inicio = ($pag * $maximo) - $maximo;
                    if ($action_id != "") {
                        $sql_qtd_geral = mysqli_query($conect, "SELECT * FROM lancamento WHERE usuario='$action_id';");
                    } else {
                        $sql_qtd_geral = mysqli_query($conect, "SELECT * FROM lancamento;");
                    }
                    $qtd_geral = mysqli_num_rows($sql_qtd_geral);
                    $qtd_array = array(
                        'qtd_geral' => $qtd_geral
                    );
                    array_push($result, $qtd_array);

                    if ($action_id != "") {
                        $resultados = mysqli_query($conect, "SELECT * FROM lancamento WHERE usuario='$action_id' ORDER BY data_criacao DESC LIMIT $inicio, $maximo");
                    } else {
                        $resultados = mysqli_query($conect, "SELECT * FROM lancamento ORDER BY data_criacao DESC LIMIT $inicio, $maximo");
                    }

                    if (mysqli_num_rows($resultados) > 0) {
                        while ($row = mysqli_fetch_array($resultados)) {
                            $data_criacao = date_format(date_create($row['data_criacao']), 'd/m/Y H:i:s');
                            $data_recebimento = date_format(date_create($row['data_recebimento']), 'd/m/Y H:i:s');
                            $data_devolucao = date_format(date_create($row['data_devolucao']), 'd/m/Y H:i:s');
                            $dados = array(
                                'idlancamento' => utf8_encode($row["idlancamento"]),
                                'data_criacao' => $data_criacao,
                                'data_recebimento' => $data_recebimento,
                                'data_devolucao' => $data_devolucao,
                                'usuario' => utf8_encode($row["usuario"]),
                                'usuario_recebimento' => utf8_encode($row["usuario_recebimento"]),
                                'usuario_devolucao' => utf8_encode($row["usuario_devolucao"]),
                            );
                            array_push($result, $dados);
                        }
                    } else {
                        $result = array('erro' => true, 'msg_erro' => 'Nenhum lançamento encontrado.');
                    }
                    break;
                default:
                    break;
            }
            break;
        case "lancamentospassados":
            switch ($action) {
                case 'listar':
                    $pag = utf8_decode($_GET['listar_pag']);
                    $maximo = utf8_decode($_GET['listar_qtd_itens']);
                    $inicio = ($pag * $maximo) - $maximo;
                    $sql_qtd_geral = mysqli_query($conect, "SELECT * FROM lancamento WHERE usuario='$usuario_logado' AND !ISNULL(data_recebimento) AND !ISNULL(data_devolucao);");
                    $qtd_geral = mysqli_num_rows($sql_qtd_geral);
                    $qtd_array = array(
                        'qtd_geral' => $qtd_geral
                    );
                    array_push($result, $qtd_array);

                    $resultados = mysqli_query($conect, "SELECT * FROM lancamento WHERE usuario='$usuario_logado' AND !ISNULL(data_recebimento) AND !ISNULL(data_devolucao) ORDER BY data_criacao DESC LIMIT $inicio, $maximo");
                    if (mysqli_num_rows($resultados) > 0) {
                        while ($row = mysqli_fetch_array($resultados)) {
                            $data_criacao = date_format(date_create($row['data_criacao']), 'd/m/Y H:i:s');
                            $data_recebimento = date_format(date_create($row['data_recebimento']), 'd/m/Y H:i:s');
                            $data_devolucao = date_format(date_create($row['data_devolucao']), 'd/m/Y H:i:s');
                            $dados = array(
                                'idlancamento' => utf8_encode($row["idlancamento"]),
                                'usuario' => utf8_encode($row["usuario"]),
                                'data_criacao' => $data_criacao,
                                'data_recebimento' => $data_recebimento,
                                'data_devolucao' => $data_devolucao,
                                'usuario_recebimento' => utf8_encode($row["usuario_recebimento"]),
                                'usuario_devolucao' => utf8_encode($row["usuario_devolucao"]),
                            );
                            array_push($result, $dados);
                        }
                    } else {
                        $result = array('erro' => true, 'msg_erro' => 'Nenhum lançamento encontrado.');
                    }
                    break;
                default:
                    break;
            }
            break;
        case ($action_pagina == "entradapeca" || $action_pagina == "saidapeca"):
            switch ($action) {
                case 'listar':
                    $pag = utf8_decode($_GET['listar_pag']);
                    $maximo = utf8_decode($_GET['listar_qtd_itens']);
                    $inicio = ($pag * $maximo) - $maximo;
                    $qtd_geral_sql = mysqli_query($conect, "SELECT peca.* FROM peca JOIN(lancamento_has_peca) ON(peca.idpeca=lancamento_has_peca.idpeca AND lancamento_has_peca.idlancamento='$action_id')");
                    $qtd_geral = mysqli_num_rows($qtd_geral_sql);
                    $qtd_array = array(
                        'qtd_geral' => $qtd_geral
                    );
                    array_push($result, $qtd_array);

                    $peca_sql = mysqli_query($conect, "SELECT peca.* FROM peca JOIN(lancamento_has_peca, tipo) ON(peca.idpeca=lancamento_has_peca.idpeca AND peca.idtipo=tipo.idtipo) WHERE lancamento_has_peca.idlancamento='$action_id' ORDER BY tipo.nome LIMIT $inicio, $maximo");

                    if (mysqli_num_rows($peca_sql) > 0) {
                        while ($row = mysqli_fetch_array($peca_sql)) {
                            $id_tipo = $row["idtipo"];
                            $resultados2 = mysqli_query($conect, "SELECT nome FROM tipo WHERE idtipo='$id_tipo'");
                            $row2 = mysqli_fetch_array($resultados2);
                            $dados = array(
                                'idpeca' => utf8_encode($row["idpeca"]),
                                'descricao' => utf8_encode($row["descricao"]),
                                'marca' => utf8_encode($row["marca"]),
                                'cor' => utf8_encode($row["cor"]),
                                'tamanho' => utf8_encode($row["tamanho"]),
                                'nometipo' => utf8_encode($row2["nome"]),
                            );
                            array_push($result, $dados);
                        }
                    }
                    break;
                default :
                    break;
            }
            break;
        case "usuario-funcionario":
            switch ($action) {
                case 'listar':
                    $pag = utf8_decode($_GET['listar_pag']);
                    $maximo = utf8_decode($_GET['listar_qtd_itens']);
                    $inicio = ($pag * $maximo) - $maximo;
                    if ($action_id == "") {
                        $qtd_geral = mysqli_query($conect, "SELECT usuario FROM usuario WHERE permissao in ('1', '2')");
                    } else {
                        $qtd_geral = mysqli_query($conect, "SELECT usuario FROM usuario WHERE permissao in ('1', '2') AND (usuario LIKE '%$action_id%' OR nome LIKE '%$action_id%' OR quarto LIKE '%Pe%' OR ramal LIKE '%$action_id%' OR num LIKE '%$action_id%')");
                    }
                    $qtd_geral_idioma = mysqli_num_rows($qtd_geral);
                    $qtd_array = array(
                        'qtd_geral' => $qtd_geral_idioma
                    );
                    array_push($result, $qtd_array);
                    if ($action_id == "") {
                        $resultados = mysqli_query($conect, "SELECT * FROM usuario WHERE permissao in ('1', '2') ORDER BY nome LIMIT $inicio, $maximo");
                    } else {
                        $resultados = mysqli_query($conect, "SELECT * FROM usuario WHERE permissao in ('1', '2') AND (usuario LIKE '%$action_id%' OR nome LIKE '%$action_id%' OR quarto LIKE '%Pe%' OR ramal LIKE '%$action_id%' OR num LIKE '%$action_id%') ORDER BY nome LIMIT $inicio, $maximo");
                    }
                    if (mysqli_num_rows($resultados) == 0) {
                        $result = array('erro' => true, 'msg_erro' => 'Nenhum usuário encontrado.');
                    } else {
                        while ($row = mysqli_fetch_array($resultados)) {
                            $dados = array(
                                'nome' => utf8_encode($row["nome"]),
                                'usuario' => utf8_encode($row["usuario"]),
                                'email' => utf8_encode($row["email"]),
                                'ramal' => utf8_encode($row["ramal"]),
                                'permissao' => utf8_encode($row["permissao"]),
                            );
                            array_push($result, $dados);
                        }
                    }

                    break;
                default:
                    break;
            }
            break;
        case "usuario-aluno":
            switch ($action) {
                case 'listar':
                    $pag = utf8_decode($_GET['listar_pag']);
                    $maximo = utf8_decode($_GET['listar_qtd_itens']);
                    $inicio = ($pag * $maximo) - $maximo;
                    if ($action_id == "") {
                        $qtd_geral = mysqli_query($conect, "SELECT usuario FROM usuario WHERE permissao='3'");
                    } else {
                        $qtd_geral = mysqli_query($conect, "SELECT usuario FROM usuario WHERE permissao='3' AND (usuario LIKE '%$action_id%' OR nome LIKE '%$action_id%' OR quarto LIKE '%$action_id%' OR ramal LIKE '%$action_id%' OR num LIKE '%$action_id%') ");
                    }
                    $qtd_geral_idioma = mysqli_num_rows($qtd_geral);
                    $qtd_array = array(
                        'qtd_geral' => $qtd_geral_idioma
                    );
                    array_push($result, $qtd_array);

                    $ids[] = "0";
                    if ($action_id == "") {
                        $resultados = mysqli_query($conect, "(SELECT usuario.*, '0' as bloqueado FROM usuario WHERE permissao='3') UNION (SELECT usuario.*, ISNULL(bloqueio.data_fim) as bloqueado FROM usuario JOIN(bloqueio) ON(usuario.usuario=bloqueio.usuario) WHERE permissao='3' AND ISNULL(bloqueio.data_fim)) ORDER BY bloqueado DESC, nome ASC LIMIT $inicio, $maximo");
                    } else {
                        $resultados = mysqli_query($conect, "(SELECT usuario.*, '0' as bloqueado FROM usuario WHERE permissao='3' AND (usuario.usuario LIKE '%$action_id%' OR nome LIKE '%$action_id%' OR quarto LIKE '%$action_id%' OR ramal LIKE '%$action_id%' OR num LIKE '%$action_id%')) UNION (SELECT usuario.*, ISNULL(bloqueio.data_fim) as bloqueado FROM usuario JOIN(bloqueio) ON(usuario.usuario=bloqueio.usuario) WHERE permissao='3' AND ISNULL(bloqueio.data_fim) AND (usuario.usuario LIKE '%$action_id%' OR nome LIKE '%$action_id%' OR quarto LIKE '%$action_id%' OR ramal LIKE '%$action_id%' OR num LIKE '%$action_id%')) ORDER BY bloqueado DESC, nome ASC LIMIT $inicio, $maximo");
                    }
                    if (mysqli_num_rows($resultados) != 0) {
                        while ($row = mysqli_fetch_array($resultados)) {
                            if (!in_array($row['usuario'], $ids)) {
                                $dados = array(
                                    'nome' => utf8_encode($row["nome"]),
                                    'usuario' => utf8_encode($row["usuario"]),
                                    'sexo' => utf8_encode($row["sexo"]),
                                    'ramal' => utf8_encode($row["ramal"]),
                                    'quarto' => utf8_encode($row["quarto"]),
                                    'senha' => utf8_encode($row["senha"]),
                                    'email' => utf8_encode($row["email"]),
                                    'num' => utf8_encode($row["num"]),
                                    'bloqueado' => utf8_encode($row["bloqueado"]),
                                );
                                array_push($result, $dados);
                                $ids[] = $row["usuario"];
                            }
                        }
                    }
                    break;
                default:
                    break;
            }
            break;
        case "numero":
            switch ($action) {
                case 'listar':
                    $sexo = utf8_decode($_GET['sexo']);
                    $resultados = mysqli_query($conect, "SELECT num_lavanderia.num, usuario.nome FROM num_lavanderia LEFT JOIN (usuario) ON (usuario.num = num_lavanderia.num) WHERE num_lavanderia.sexo='$sexo' ORDER BY num_lavanderia.num");
                    if (mysqli_num_rows($resultados) == 0) {
                        $result = array('erro' => true, 'msg_erro' => 'Nenhum número encontrado.');
                    } else {
                        while ($row = mysqli_fetch_array($resultados)) {
                            if (utf8_encode($row["nome"]) == NULL) {
                                $dados = array(
                                    'num' => utf8_encode($row["num"]),
                                );
                                array_push($result, $dados);
                            }
                        }
                    }
                    break;
                default:
                    break;
            }
            break;
        case "numero_masculino":
            switch ($action) {
                case 'listar':
                    $pag = utf8_decode($_GET['listar_pag']);
                    $maximo = utf8_decode($_GET['listar_qtd_itens']);
                    $inicio = ($pag * $maximo) - $maximo;
                    $qtd_geral_sql = mysqli_query($conect, "SELECT * FROM num_lavanderia WHERE sexo='m'");
                    $qtd_geral = mysqli_num_rows($qtd_geral_sql);
                    $qtd_array = array(
                        'qtd_geral' => $qtd_geral
                    );
                    array_push($result, $qtd_array);

                    $resultados = mysqli_query($conect, "SELECT num_lavanderia.num, usuario.nome FROM num_lavanderia LEFT JOIN (usuario) ON (usuario.num = num_lavanderia.num) WHERE num_lavanderia.sexo='m' ORDER BY usuario.nome ,num_lavanderia.num  LIMIT $inicio, $maximo");
                    if (mysqli_num_rows($resultados) == 0) {
                        $result = array('erro' => true, 'msg_erro' => 'Nenhum número encontrado.');
                    } else {
                        while ($row = mysqli_fetch_array($resultados)) {
                            $dados = array(
                                'nome_usuario' => utf8_encode($row["nome"]),
                                'num' => utf8_encode($row["num"]),
                            );
                            array_push($result, $dados);
                        }
                    }
                default :
                    break;
            }
            break;
        case "numero_feminino":
            switch ($action) {
                case 'listar':
                    $pag = utf8_decode($_GET['listar_pag']);
                    $maximo = utf8_decode($_GET['listar_qtd_itens']);
                    $inicio = ($pag * $maximo) - $maximo;
                    $qtd_geral = mysqli_query($conect, "SELECT * FROM num_lavanderia WHERE sexo='f'");
                    $qtd_geral_idioma = mysqli_num_rows($qtd_geral);
                    $qtd_array = array(
                        'qtd_geral' => $qtd_geral_idioma
                    );
                    array_push($result, $qtd_array);

                    $resultados = mysqli_query($conect, "SELECT num_lavanderia.num, usuario.nome FROM num_lavanderia LEFT JOIN (usuario) ON (usuario.num = num_lavanderia.num) WHERE num_lavanderia.sexo='f' ORDER BY usuario.nome ,num_lavanderia.num LIMIT $inicio, $maximo");
                    if (mysqli_num_rows($resultados) == 0) {
                        $result = array('erro' => true, 'msg_erro' => 'Nenhum número encontrado.');
                    } else {
                        while ($row = mysqli_fetch_array($resultados)) {
                            $dados = array(
                                'nome_usuario' => utf8_encode($row["nome"]),
                                'num' => utf8_encode($row["num"]),
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
} else {
    $result = array('erro' => true, 'msg_erro' => 'Faça o login para continuar.');
}
mysqli_close($conect);

echo json_encode($result);
?>
