<?php

header('Content-type: text/html; charset=UTF-8');
include_once '../conexao/conexao.php';
/*
  for ($i = 0; $i < 25; $i++) {
  if ($i % 2 == 0) {
  $permissao_usuario_cadastrar = '0';
  } else {
  $permissao_usuario_cadastrar = '1';
  }
  $sql = mysqli_query($conect, "INSERT INTO usuario (usuario, nome, senha, quarto, ramal, permissao) VALUES ('usuario." . rand() . "', 'Nome " . rand() . "', '" . rand() . "', NULL, '" . rand() . "', '" . $permissao_usuario_cadastrar . "');");
  }
  for ($i = 0; $i < 10; $i++) {
  $sql = mysqli_query($conect, "INSERT INTO usuario (usuario, nome, senha, quarto, ramal, permissao) VALUES ('usuario." . rand() . "', 'nome " . rand() . "', '" . rand() . "', NULL, '" . rand() . "', '2');");
  }
 */

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
                case 'cadastrar':
                    $descricao = utf8_decode($_GET['descricao']);
                    $marca = utf8_decode($_GET['marca']);
                    $cor = utf8_decode($_GET['cor']);
                    $tamanho = utf8_decode($_GET['tamanho']);
                    $id_tipo = utf8_decode($_GET['idtipo']);

                    if (!is_numeric($id_tipo)) {
                        $sql_tipo = mysqli_query($conect, "SELECT idtipo FROM tipo WHERE nome='$id_tipo' AND (usuario='$usuario_logado' OR ISNULL(usuario));");
                        if (mysqli_num_rows($sql_tipo) == "0") {
                            $sql_insert_tipo = mysqli_query($conect, "INSERT INTO tipo (idtipo, nome, usuario) VALUES (NULL, '$id_tipo', '$usuario_logado');");
                            $id_tipo = mysqli_insert_id($conect);
                        } else {
                            $row = mysqli_fetch_array($sql_tipo);
                            $id_tipo = utf8_encode($row['idtipo']);
                        }
                    }

                    $sql_peca = mysqli_query($conect, "SELECT idpeca FROM peca WHERE descricao='$descricao' AND marca='$marca' AND cor='$cor' AND tamanho='$tamanho' AND idtipo='$id_tipo'");
                    if (mysqli_num_rows($sql_peca) == "0") {
                        $sql_insert_peca = mysqli_query($conect, "INSERT INTO peca (idpeca, descricao, marca, cor, tamanho, status, idtipo, usuario) VALUES (NULL, '$descricao', '$marca', '$cor', '$tamanho', '1', '$id_tipo', '$usuario_logado');");
                    } else {
                        $result = array('erro' => true, 'msg_erro' => 'Peça já existe.');
                    }
                    break;
                case 'listar':
                    $pag = utf8_decode($_GET['listar_pag']);
                    $maximo = utf8_decode($_GET['listar_qtd_itens']);
                    $inicio = ($pag * $maximo) - $maximo;
                    $qtd_geral = mysqli_query($conect, "SELECT idpeca FROM peca WHERE usuario='$usuario_logado'");
                    $qtd_geral_idioma = mysqli_num_rows($qtd_geral);
                    $qtd_array = array(
                        'qtd_geral' => $qtd_geral_idioma
                    );
                    array_push($result, $qtd_array);

                    $dados = NULL;
                    $ids[] = '0';

                    $resultados = mysqli_query($conect, "SELECT peca.idpeca, ocorrencia.idocorrencia, peca.descricao, peca.marca, peca.cor, peca.tamanho, tipo.nome as nometipo, tipo_ocorrencia.tipo as tipoocorrencia FROM peca JOIN(tipo, ocorrencia, tipo_ocorrencia) ON(peca.idtipo = tipo.idtipo AND peca.idpeca = ocorrencia.idpeca AND ocorrencia.idtipo_ocorrencia = tipo_ocorrencia.idtipo_ocorrencia) WHERE peca.usuario='$usuario_logado' AND ocorrencia.status='1' AND peca.status='1' ORDER BY tipo_ocorrencia.tipo, tipo.nome, peca.descricao LIMIT $inicio, $maximo");
                    if (mysqli_num_rows($resultados) > 0) {
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
                            );
                            array_push($result, $dados);
                            $ids[] = $row["idpeca"];
                        }
                    }
                    $resultados1 = mysqli_query($conect, "SELECT peca.idpeca, peca.descricao, peca.marca, peca.cor, peca.tamanho, ocorrencia.idocorrencia, peca.idtipo FROM peca LEFT JOIN(ocorrencia) ON(peca.idpeca = ocorrencia.idpeca) WHERE peca.usuario='$usuario_logado' AND (ISNULL(ocorrencia.idocorrencia) OR ocorrencia.status='0') AND peca.status='1' GROUP BY peca.idpeca ORDER BY peca.descricao LIMIT $inicio, $maximo");

                    if (mysqli_num_rows($resultados1) > 0) {
                        while ($row = mysqli_fetch_array($resultados1)) {
                            if (!in_array($row["idpeca"], $ids)) {
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
                                    'idocorrencia' => "",
                                );
                                array_push($result, $dados);
                            }
                        }
                    } else {
                        if (is_null($dados)) {
                            $result = array('erro' => true, 'msg_erro' => 'Nenhuma peça encontrada.');
                        }
                    }
                    //print_r($result);exit;
                    break;
                case 'montar':
                    $resultados = mysqli_query($conect, "SELECT peca.idpeca, peca.descricao, peca.marca, peca.cor, peca.tamanho, tipo.nome as nometipo, tipo.idtipo FROM peca JOIN(tipo) ON(peca.idtipo = tipo.idtipo) WHERE peca.usuario='$usuario_logado' AND idpeca='$action_id'");
                    if (mysqli_num_rows($resultados) == 0) {
                        $result = array('erro' => true, 'msg_erro' => 'Nenhuma peça encontrado.');
                    } else {
                        $row = mysqli_fetch_array($resultados);
                        $id_peca = $row["idpeca"];
                        $resultados1 = mysqli_query($conect, "SELECT ocorrencia.*, tipo_ocorrencia.tipo FROM ocorrencia JOIN(tipo_ocorrencia) ON(ocorrencia.idtipo_ocorrencia=tipo_ocorrencia.idtipo_ocorrencia)  WHERE idpeca='$id_peca' ORDER BY status DESC");
                        $ocorrencias = array('qtd_ocorrencias' => mysqli_num_rows($resultados1));
                        if (mysqli_num_rows($resultados1) > 0) {
                            while ($row2 = mysqli_fetch_array($resultados1)) {
                                $ocorrencia = array(
                                    'idocorrencia' => utf8_encode($row2['idocorrencia']),
                                    'descricao' => utf8_encode($row2['descricao']),
                                    'status' => utf8_encode($row2['status']),
                                    'idpeca' => utf8_encode($row2['idpeca']),
                                    'tipoocorrenca' => utf8_encode($row2['tipo']),
                                );
                                array_push($ocorrencias, $ocorrencia);
                            }
                        }
                        $dados = array(
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
                case 'editar':
                    $descricao = utf8_decode($_GET['descricao']);
                    $marca = utf8_decode($_GET['marca']);
                    $cor = utf8_decode($_GET['cor']);
                    $tamanho = utf8_decode($_GET['tamanho']);
                    $id_tipo = utf8_decode($_GET['idtipo']);

                    if (!is_numeric($id_tipo)) {
                        $sql_tipo = mysqli_query($conect, "SELECT idtipo FROM tipo WHERE nome='$id_tipo' AND (usuario='$usuario_logado' OR ISNULL(usuario));");
                        if (mysqli_num_rows($sql_tipo) == "0") {
                            $sql_insert_tipo = mysqli_query($conect, "INSERT INTO tipo (idtipo, nome, usuario) VALUES (NULL, '$id_tipo', '$usuario_logado');");
                            $id_tipo = mysqli_insert_id($conect);
                        } else {
                            $row = mysqli_fetch_array($sql_tipo);
                            $id_tipo = utf8_encode($row['idtipo']);
                        }
                    }

                    $sql_peca = mysqli_query($conect, "SELECT idtipo FROM peca WHERE idpeca='$action_id'");
                    if (mysqli_num_rows($sql_peca) == "0") {
                        $result = array('erro' => true, 'msg_erro' => 'Peça não existe.');
                    } else {
                        mysqli_query($conect, "UPDATE peca SET descricao='$descricao', marca='$marca', cor='$cor', tamanho='$tamanho', idtipo='$id_tipo' WHERE idpeca='$action_id';");
                    }
                    break;
                case 'excluir':
                    $sql_lacamento_has_peca = mysqli_query($conect, "SELECT * FROM lancamento_has_peca WHERE idpeca='$action_id'");
                    if (mysqli_num_rows($sql_lacamento_has_peca) == 0) {
                        $sql = mysqli_query($conect, "SELECT idocorrencia FROM peca JOIN(ocorrencia) ON(peca.idpeca=ocorrencia.idpeca) WHERE peca.idpeca='$action_id'");
                        if (mysqli_num_rows($sql) == 0) {
                            $sql_select = mysqli_query($conect, "SELECT * FROM peca WHERE peca.idpeca='$action_id'");
                            if (mysqli_num_rows($sql_select) == "0") {
                                $result = array('erro' => true, 'msg_erro' => 'Peça não exite.');
                            } else {
                                $sql = mysqli_query($conect, "DELETE FROM peca WHERE idpeca='$action_id';");
                            }
                        } else {
                            mysqli_query($conect, "UPDATE peca SET status='0' WHERE idpeca='$action_id';");
                        }
                    } else {
                        mysqli_query($conect, "UPDATE peca SET status='0' WHERE idpeca='$action_id';");
                    }
                    break;
                default:
                    break;
            }

            break;
        case "ocorrencia":
            switch ($action) {
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

                    $dados = NULL;
                    $ids[] = '0';

                    $resultados = mysqli_query($conect, "SELECT peca.idpeca, ocorrencia.idocorrencia, peca.descricao, peca.marca, peca.cor, peca.tamanho, tipo.nome as nometipo, tipo_ocorrencia.tipo as tipoocorrencia, ocorrencia.status as ocorrenciastatus, ocorrencia.descricao as ocorrenciadescricao FROM peca JOIN(tipo, ocorrencia, tipo_ocorrencia) ON(peca.idtipo = tipo.idtipo AND peca.idpeca = ocorrencia.idpeca AND ocorrencia.idtipo_ocorrencia = tipo_ocorrencia.idtipo_ocorrencia) WHERE peca.usuario='$usuario_logado' AND ocorrencia.status='1' ORDER BY tipo_ocorrencia.tipo, tipo.nome, peca.descricao LIMIT $inicio, $maximo");
                    if (mysqli_num_rows($resultados) > 0) {
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
                    $resultados1 = mysqli_query($conect, "SELECT peca.idpeca, ocorrencia.idocorrencia, peca.descricao, peca.marca, peca.cor, peca.tamanho, tipo.nome as nometipo, tipo_ocorrencia.tipo as tipoocorrencia, ocorrencia.status as ocorrenciastatus, ocorrencia.descricao as ocorrenciadescricao FROM peca JOIN(tipo, ocorrencia, tipo_ocorrencia) ON(peca.idtipo = tipo.idtipo AND peca.idpeca = ocorrencia.idpeca AND ocorrencia.idtipo_ocorrencia = tipo_ocorrencia.idtipo_ocorrencia) WHERE peca.usuario='$usuario_logado' AND ocorrencia.status<>'1' GROUP BY peca.idpeca ORDER BY tipo_ocorrencia.tipo, tipo.nome, peca.descricao LIMIT $inicio, $maximo");

                    if (mysqli_num_rows($resultados1) > 0) {
                        while ($row = mysqli_fetch_array($resultados1)) {
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
                            }
                        }
                    } else {
                        if (is_null($dados)) {
                            $result = array('erro' => true, 'msg_erro' => 'Nenhuma peça com ocorrência encontrada.');
                        }
                    }
                    //print_r($result);exit;
                    break;
                case 'montar':
                    $resultados = mysqli_query($conect, "SELECT peca.idpeca, peca.descricao, peca.marca, peca.cor, peca.tamanho, tipo.nome as nometipo, tipo.idtipo FROM peca JOIN(tipo) ON(peca.idtipo = tipo.idtipo) WHERE peca.usuario='$usuario_logado' AND idpeca='$action_id'");
                    if (mysqli_num_rows($resultados) == 0) {
                        $result = array('erro' => true, 'msg_erro' => 'Nenhuma peça encontrado.');
                    } else {
                        $row = mysqli_fetch_array($resultados);
                        $id_peca = $row["idpeca"];
                        $resultados1 = mysqli_query($conect, "SELECT ocorrencia.*, tipo_ocorrencia.tipo FROM ocorrencia JOIN(tipo_ocorrencia) ON(ocorrencia.idtipo_ocorrencia=tipo_ocorrencia.idtipo_ocorrencia)  WHERE idpeca='$id_peca' ORDER BY status DESC");
                        $ocorrencias = array('qtd_ocorrencias' => mysqli_num_rows($resultados1));
                        if (mysqli_num_rows($resultados1) > 0) {
                            while ($row2 = mysqli_fetch_array($resultados1)) {
                                $ocorrencia = array(
                                    'idocorrencia' => utf8_encode($row2['idocorrencia']),
                                    'descricao' => utf8_encode($row2['descricao']),
                                    'status' => utf8_encode($row2['status']),
                                    'idpeca' => utf8_encode($row2['idpeca']),
                                    'tipoocorrenca' => utf8_encode($row2['tipo']),
                                );
                                array_push($ocorrencias, $ocorrencia);
                            }
                        }
                        $dados = array(
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
                case 'cadastrar':
                    $selecionadas = $_GET['selecionadas'];
                    $sql_select_lancamento = mysqli_query($conect, "SELECT idlancamento FROM lancamento WHERE usuario='$usuario_logado' AND ISNULL(data_recebimento);");
                    if (mysqli_num_rows($sql_select_lancamento) != "0") {
                        $row = mysqli_fetch_array($sql_select_lancamento);
                        $id_lancamento = $row['idlancamento'];
                        mysqli_query($conect, "DELETE FROM lancamento_has_peca WHERE idlancamento='$id_lancamento'");
                        $result = array('erro' => "success", 'msg_success' => 'As alterações no lançamento foram salvas com sucesso.');
                    } else {
                        $sql_insert_lancamento = mysqli_query($conect, "INSERT INTO lancamento (usuario) VALUES ('$usuario_logado');");
                        $id_lancamento = mysqli_insert_id($conect);
                    }
                    for ($i = 0; $i < count($selecionadas); $i++) {
                        $idpeca = $selecionadas[$i];
                        $sql_peca = mysqli_query($conect, "SELECT status FROM peca WHERE idpeca='$idpeca'");
                        $row = mysqli_fetch_array($sql_peca);
                        if (mysqli_num_rows($sql_peca) == "0") {
                            mysqli_query($conect, "DELETE FROM lancamento WHERE idlancamento='$id_lancamento'");
                            $result = array('erro' => true, 'msg_erro' => 'Peça marcada não existe no cadastro.');
                        } elseif ($row['status'] == "0") {
                            mysqli_query($conect, "DELETE FROM lancamento WHERE idlancamento='$id_lancamento'");
                            $result = array('erro' => true, 'msg_erro' => 'Peça marcada não existe no cadastro.');
                        } else {
                            $sql_insert_peca = mysqli_query($conect, "INSERT INTO lancamento_has_peca (idpeca, idlancamento) VALUES ('$idpeca', '$id_lancamento');");
                        }
                    }
                    break;
                case 'excluir':
                    $sql = mysqli_query($conect, "SELECT idlancamento FROM lancamento WHERE idlancamento='$action_id'");
                    if (mysqli_num_rows($sql) == "1") {
                        mysqli_query($conect, "DELETE FROM lancamento WHERE idlancamento='$action_id'");
                    } else {
                        $result = array('erro' => true, 'msg_erro' => 'Lançamento não existe.');
                    }
                    break;
                default:
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
                    //print_r($result);exit;
                    break;
                case 'montar':
                    $resultados = mysqli_query($conect, "SELECT peca.idpeca, peca.descricao, peca.marca, peca.cor, peca.tamanho, tipo.nome as nometipo FROM peca JOIN(tipo) ON(peca.idtipo = tipo.idtipo) WHERE peca.usuario='$usuario_logado' AND idpeca='$action_id'");
                    if (mysqli_num_rows($resultados) == 0) {
                        $result = array('erro' => true, 'msg_erro' => 'Nenhuma peça encontrado.');
                    } else {
                        $row = mysqli_fetch_array($resultados);
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
                    break;
                case 'removerpeca':
                    $sql = mysqli_query($conect, "SELECT lp.idlancamento_has_peca FROM peca as p JOIN(lancamento_has_peca as lp, lancamento as l) ON(p.idpeca=lp.idpeca AND l.idlancamento=lp.idlancamento) WHERE p.usuario='$usuario_logado' AND ISNULL(l.data_devolucao) AND p.idpeca='$action_id'");
                    if (mysqli_num_rows($sql) == "0") {
                        $result = array('erro' => true, 'msg_erro' => 'Peça não exite.');
                    } else {
                        $row = mysqli_fetch_array($sql);
                        $idlancamento_has_peca = $row['idlancamento_has_peca'];
                        $sql = mysqli_query($conect, "DELETE FROM lancamento_has_peca WHERE idlancamento_has_peca='$idlancamento_has_peca';");
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
                            $dados = array(
                                'idlancamento' => utf8_encode($row["idlancamento"]),
                                'data_criacao' => utf8_encode($row["data_criacao"]),
                                'data_recebimento' => utf8_encode($row["data_recebimento"]),
                                'data_devolucao' => utf8_encode($row["data_devolucao"]),
                                'usuario_recebimento' => utf8_encode($row["usuario_recebimento"]),
                                'usuario_devolucao' => utf8_encode($row["usuario_devolucao"]),
                            );
                            array_push($result, $dados);
                        }
                    } else {
                        $result = array('erro' => true, 'msg_erro' => 'Nenhum lançamento encontrado.');
                    }
                    break;
                case 'montar':
                    $resultados = mysqli_query($conect, "SELECT * FROM lancamento WHERE usuario='$usuario_logado' AND idlancamento='$action_id'");
                    if (mysqli_num_rows($resultados) == 0) {
                        $result = array('erro' => true, 'msg_erro' => 'Nenhum lançamento encontrado.');
                    } else {
                        $row = mysqli_fetch_array($resultados);
                        $resultados1 = mysqli_query($conect, "SELECT * FROM lancamento_has_peca WHERE idlancamento='$action_id'");
                        if (mysqli_num_rows($resultados1) != 0) {
                            $pecas[] = array('qtd_pecas' => mysqli_num_rows($resultados1));
                            while ($row1 = mysqli_fetch_array($resultados1)) {
                                $id_peca = $row1['idpeca'];
                                $resultados2 = mysqli_query($conect, "SELECT peca.idpeca, peca.descricao, peca.marca, peca.cor, peca.tamanho, tipo.nome as nometipo FROM peca JOIN(tipo) ON(peca.idtipo = tipo.idtipo) WHERE idpeca='$id_peca'");
                                if (mysqli_num_rows($resultados2) != 0) {
                                    while ($row2 = mysqli_fetch_array($resultados2)) {
                                        $dados2 = array(
                                            'idpeca' => utf8_encode($row2["idpeca"]),
                                            'descricao' => utf8_encode($row2["descricao"]),
                                            'marca' => utf8_encode($row2["marca"]),
                                            'cor' => utf8_encode($row2["cor"]),
                                            'tamanho' => utf8_encode($row2["tamanho"]),
                                            'nometipo' => utf8_encode($row2["nometipo"]),
                                        );
                                        array_push($pecas, $dados2);
                                    }
                                }
                            }
                        }
                        $dados = array(
                            'idlancamento' => utf8_encode($row["idlancamento"]),
                            'data_criacao' => utf8_encode($row["data_criacao"]),
                            'data_recebimento' => utf8_encode($row["data_recebimento"]),
                            'data_devolucao' => utf8_encode($row["data_devolucao"]),
                            'usuario_recebimento' => utf8_encode($row["usuario_recebimento"]),
                            'usuario_devolucao' => utf8_encode($row["usuario_devolucao"]),
                            'pecas' => $pecas,
                        );
                        array_push($result, $dados);
                    }
                    break;
                case 'removerpeca':
                    $sql = mysqli_query($conect, "SELECT lp.idlancamento_has_peca FROM peca as p JOIN(lancamento_has_peca as lp, lancamento as l) ON(p.idpeca=lp.idpeca AND l.idlancamento=lp.idlancamento) WHERE p.usuario='$usuario_logado' AND ISNULL(l.data_devolucao) AND p.idpeca='$action_id'");
                    if (mysqli_num_rows($sql) == "0") {
                        $result = array('erro' => true, 'msg_erro' => 'Peça não exite.');
                    } else {
                        $row = mysqli_fetch_array($sql);
                        $idlancamento_has_peca = $row['idlancamento_has_peca'];
                        $sql = mysqli_query($conect, "DELETE FROM lancamento_has_peca WHERE idlancamento_has_peca='$idlancamento_has_peca';");
                    }
                    break;
                default:
                    break;
            }
            break;
        case "usuario-funcionario":
            switch ($action) {
                case 'cadastrar':
                    $nome_usuario = utf8_decode($_GET['nome_usuario_cadastrar']);
                    $usuario_usuario = utf8_decode($_GET['usuario_usuario_cadastrar']);
                    $sexo_usuario = utf8_decode($_GET['sexo_usuario']);
                    $ramal_usuario = utf8_decode($_GET['ramal_usuario_cadastrar']);
                    $permissao_usuario = utf8_decode($_GET['permissao_usuario_cadastrar']);
                    $sql = mysqli_query($conect, "SELECT nome FROM usuario WHERE usuario='$usuario_usuario'");
                    if (mysqli_num_rows($sql) == "0") {
                        $sql = mysqli_query($conect, "INSERT INTO usuario (usuario, nome, senha, quarto, ramal, permissao) VALUES ('" . $usuario_usuario . "', '" . $nome_usuario . "', 'unasp', NULL, '" . $ramal_usuario . "', '" . $permissao_usuario . "');");
                    } else {
                        $result = array('erro' => true, 'msg_erro' => 'Usuário já existe.');
                    }
                    break;
                case 'editar':
                    $nome_usuario = utf8_decode($_GET['nome_usuario']);
                    $usuario_usuario = utf8_decode($_GET['usuario_usuario']);
                    $ramal_usuario = utf8_decode($_GET['ramal_usuario']);
                    $sexo_usuario = utf8_decode($_GET['sexo_usuario']);
                    $permissao_usuario = utf8_decode($_GET['permissao_usuario']);
                    $sql = mysqli_query($conect, "SELECT nome FROM usuario WHERE usuario='$action_id'");
                    if (mysqli_num_rows($sql) == "0") {
                        $result = array('erro' => true, 'msg_erro' => 'Usuário não existe.');
                    } else {
                        $sql = mysqli_query($conect, "UPDATE usuario SET usuario='" . $usuario_usuario . "', nome='" . $nome_usuario . "', sexo='" . $sexo_usuario . "', ramal='" . $ramal_usuario . "', permissao='" . $permissao_usuario . "' WHERE usuario='" . $action_id . "';");
                    }
                    break;
                case 'listar':
                    $pag = utf8_decode($_GET['listar_pag']);
                    $maximo = utf8_decode($_GET['listar_qtd_itens']);
                    $inicio = ($pag * $maximo) - $maximo;
                    $qtd_geral = mysqli_query($conect, "SELECT usuario FROM usuario WHERE permissao in ('0', '1', '2')");
                    $qtd_geral_idioma = mysqli_num_rows($qtd_geral);
                    $qtd_array = array(
                        'qtd_geral' => $qtd_geral_idioma
                    );
                    array_push($result, $qtd_array);

                    $resultados = mysqli_query($conect, "SELECT * FROM usuario WHERE permissao in ('0', '1', '2') ORDER BY nome LIMIT $inicio, $maximo");
                    if (mysqli_num_rows($resultados) == 0) {
                        $result = array('erro' => true, 'msg_erro' => 'Nenhum usuário encontrado.');
                    } else {
                        while ($row = mysqli_fetch_array($resultados)) {
                            $dados = array(
                                'nome' => utf8_encode($row["nome"]),
                                'usuario' => utf8_encode($row["usuario"]),
                                'ramal' => utf8_encode($row["ramal"]),
                                'permissao' => utf8_encode($row["permissao"]),
                            );
                            array_push($result, $dados);
                        }
                    }

                    break;
                case 'montar':
                    $resultados = mysqli_query($conect, "SELECT * FROM usuario WHERE usuario='$action_id'");
                    if (mysqli_num_rows($resultados) == 0) {
                        $result = array('erro' => true, 'msg_erro' => 'Nenhum usuário encontrado.');
                    } else {
                        while ($row = mysqli_fetch_array($resultados)) {
                            $dados = array(
                                'nome' => utf8_encode($row["nome"]),
                                'usuario' => utf8_encode($row["usuario"]),
                                'sexo' => utf8_encode($row["sexo"]),
                                'ramal' => utf8_encode($row["ramal"]),
                                'permissao' => utf8_encode($row["permissao"]),
                            );
                            array_push($result, $dados);
                        }
                    }
                    break;
                case 'excluir':
                    $sql = mysqli_query($conect, "SELECT nome FROM usuario WHERE usuario='$action_id'");
                    if (mysqli_num_rows($sql) == "1") {
                        $sql = mysqli_query($conect, "DELETE FROM usuario WHERE usuario='$action_id';");
                    } else {
                        $result = array('erro' => true, 'msg_erro' => 'Usuário não existe.');
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
                    $qtd_geral = mysqli_query($conect, "SELECT usuario FROM usuario WHERE permissao='3'");
                    $qtd_geral_idioma = mysqli_num_rows($qtd_geral);
                    $qtd_array = array(
                        'qtd_geral' => $qtd_geral_idioma
                    );
                    array_push($result, $qtd_array);

                    $ids[] = "0";

                    $resultados = mysqli_query($conect, "SELECT usuario.*, ISNULL(bloqueio.data_fim) as bloqueado FROM usuario LEFT JOIN(bloqueio) ON(usuario.usuario=bloqueio.usuario) WHERE permissao='3' AND !ISNULL(bloqueio.data_inicio) AND ISNULL(bloqueio.data_fim) ORDER BY nome, num LIMIT $inicio, $maximo");
                    if (mysqli_num_rows($resultados) != 0) {
                        while ($row = mysqli_fetch_array($resultados)) {
                            $dados = array(
                                'nome' => utf8_encode($row["nome"]),
                                'usuario' => utf8_encode($row["usuario"]),
                                'sexo' => utf8_encode($row["sexo"]),
                                'ramal' => utf8_encode($row["ramal"]),
                                'quarto' => utf8_encode($row["quarto"]),
                                'senha' => utf8_encode($row["senha"]),
                                'num' => utf8_encode($row["num"]),
                                'bloqueado' => utf8_encode($row["bloqueado"]),
                            );
                            array_push($result, $dados);
                            $ids[] = $row["usuario"];
                        }
                    }
                    $resultados2 = mysqli_query($conect, "SELECT * FROM usuario WHERE permissao='3' AND ISNULL(num) ORDER BY nome, num LIMIT $inicio, $maximo");
                    if (mysqli_num_rows($resultados2) != 0) {
                        while ($row = mysqli_fetch_array($resultados2)) {
                            if (!in_array($row['usuario'], $ids)) {
                                $dados = array(
                                    'nome' => utf8_encode($row["nome"]),
                                    'usuario' => utf8_encode($row["usuario"]),
                                    'sexo' => utf8_encode($row["sexo"]),
                                    'ramal' => utf8_encode($row["ramal"]),
                                    'quarto' => utf8_encode($row["quarto"]),
                                    'senha' => utf8_encode($row["senha"]),
                                    'num' => utf8_encode($row["num"]),
                                    'bloqueado' => '0',
                                );
                                array_push($result, $dados);
                                $ids[] = $row["usuario"];
                            }
                        }
                    }
                    $resultados3 = mysqli_query($conect, "SELECT * FROM usuario WHERE permissao='3' AND !ISNULL(num) ORDER BY nome, num LIMIT $inicio, $maximo");
                    if (mysqli_num_rows($resultados3) != 0) {
                        while ($row = mysqli_fetch_array($resultados3)) {
                            if (!in_array($row['usuario'], $ids)) {
                                $dados = array(
                                    'nome' => utf8_encode($row["nome"]),
                                    'usuario' => utf8_encode($row["usuario"]),
                                    'sexo' => utf8_encode($row["sexo"]),
                                    'ramal' => utf8_encode($row["ramal"]),
                                    'quarto' => utf8_encode($row["quarto"]),
                                    'senha' => utf8_encode($row["senha"]),
                                    'num' => utf8_encode($row["num"]),
                                    'bloqueado' => '0',
                                );
                                array_push($result, $dados);
                            }
                        }
                    }

                    break;
                case 'montar':
                    $resultados = mysqli_query($conect, "SELECT * FROM usuario WHERE usuario='$action_id'");
                    if (mysqli_num_rows($resultados) == 0) {
                        $result = array('erro' => true, 'msg_erro' => 'Nenhum usuário encontrado.');
                    } else {
                        while ($row = mysqli_fetch_array($resultados)) {
                            $resultados1 = mysqli_query($conect, "SELECT ISNULL(data_fim) as bloqueado FROM bloqueio WHERE usuario='$action_id' AND !ISNULL(data_inicio) AND ISNULL(data_fim);");
                            $row1 = mysqli_fetch_array($resultados1);
                            if (mysqli_num_rows($resultados1) != 0) {
                                $dados = array(
                                    'nome' => utf8_encode($row["nome"]),
                                    'usuario' => utf8_encode($row["usuario"]),
                                    'sexo' => utf8_encode($row["sexo"]),
                                    'ramal' => utf8_encode($row["ramal"]),
                                    'quarto' => utf8_encode($row["quarto"]),
                                    'num' => utf8_encode($row["num"]),
                                    'senha' => utf8_encode($row["senha"]),
                                    'permissao' => utf8_encode($row["permissao"]),
                                    'bloqueado' => utf8_encode($row1["bloqueado"]),
                                );
                            } else {
                                $dados = array(
                                    'nome' => utf8_encode($row["nome"]),
                                    'usuario' => utf8_encode($row["usuario"]),
                                    'sexo' => utf8_encode($row["sexo"]),
                                    'ramal' => utf8_encode($row["ramal"]),
                                    'quarto' => utf8_encode($row["quarto"]),
                                    'num' => utf8_encode($row["num"]),
                                    'senha' => utf8_encode($row["senha"]),
                                    'permissao' => utf8_encode($row["permissao"]),
                                    'bloqueado' => '0',
                                );
                            }
                            array_push($result, $dados);
                        }
                    }
                    break;
                case "bloquear":

                    break;
                case "desbloquear":
                    break;
                default:
                    break;
            }
            break;
        case "usuario":
            switch ($action) {
                case 'reset_senha':
                    $sql = mysqli_query($conect, "SELECT * FROM usuario WHERE usuario='$action_id'");
                    if (mysqli_num_rows($sql) == "0") {
                        $result = array('erro' => true, 'msg_erro' => 'Usuário não existe.');
                    } else {
                        $sql = mysqli_query($conect, "UPDATE usuario SET senha='unasp' WHERE usuario='$action_id';");
                    }
                    break;
                case 'deslogar':
                    if (session_id() == '') {
                        session_start();
                    }
                    if (isset($_SESSION['usuario'])) {
                        unset($_SESSION['usuario']);
                        session_destroy();
                        $result = array('erro' => false);
                    } else {
                        $result = array('erro' => true, 'msg_erro' => 'Não existe um usuário logado.');
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
                case 'atribuir_numero_auto':
                    $sexo = utf8_decode($_GET['sexo']);

                    $sql = mysqli_query($conect, "SELECT usuario, num FROM usuario WHERE sexo='$sexo' AND permissao='3' AND ISNULL(num)");
                    if (mysqli_num_rows($sql) == 0) {
                        $result = array('erro' => true, 'msg_erro' => 'Todos usuários deste sexo já possuem número.');
                    } else {
                        $resultados = mysqli_query($conect, "SELECT num_lavanderia.num, usuario.nome FROM num_lavanderia LEFT JOIN (usuario) ON (usuario.num = num_lavanderia.num) WHERE num_lavanderia.sexo='$sexo' AND ISNULL(usuario.nome) ORDER BY num_lavanderia.num");
                        if (mysqli_num_rows($resultados) == 0) {
                            $result = array('erro' => true, 'msg_erro' => 'Nenhum número está disponível, cadastre mais números.');
                        } else {
                            $numeros[] = "";
                            while ($row = mysqli_fetch_array($resultados)) {
                                $numeros[] = $row['num'];
                            }
                            while ($row_usuario = mysqli_fetch_array($sql)) {
                                for ($i = 0; $i < count($numeros); $i++) {
                                    if ($numeros[$i] != NULL) {
                                        $usuario = utf8_encode($row_usuario['usuario']);
                                        $numero = $numeros[$i];
                                        mysqli_query($conect, "UPDATE usuario SET num='$numero'WHERE usuario='$usuario';");
                                        $numeros[$i] = NULL;
                                        break;
                                    }
                                }
                            }
                            if (mysqli_num_rows($sql) > mysqli_num_rows($resultados)) {
                                $result = array('erro' => true, 'msg_erro' => 'Não há números livres suficientes para os usuários deste sexo, cadastre mais números.');
                            }
                        }
                    }
                    break;
                case 'cadastrar':
                    $num_sexo = utf8_decode($_GET['num_sexo']);
                    $num_numero = utf8_decode($_GET['num_numero']);
                    $sql = mysqli_query($conect, "SELECT * FROM num_lavanderia WHERE num='$num_numero'");
                    if (mysqli_num_rows($sql) == "0") {
                        $sql = mysqli_query($conect, "INSERT INTO num_lavanderia (num, sexo) VALUES ('$num_numero', '$num_sexo');");
                    } else {
                        $result = array('erro' => true, 'msg_erro' => 'Número já existe.');
                    }
                    break;
                case 'editar':
                    $usuario = utf8_decode($_GET['usuario']);
                    $novo_numero = utf8_decode($_GET['novo_numero']);
                    $sql = mysqli_query($conect, "SELECT nome FROM usuario WHERE usuario='$usuario'");
                    if (mysqli_num_rows($sql) == "0") {
                        $result = array('erro' => true, 'msg_erro' => 'Usuário não existe.');
                    } else {
                        $sql = mysqli_query($conect, "UPDATE usuario SET num='$novo_numero'WHERE usuario='$usuario';");
                    }
                    break;
                case 'excluir':
                    $sql = mysqli_query($conect, "SELECT * FROM num_lavanderia WHERE num='$action_id'");
                    if (mysqli_num_rows($sql) == 1) {
                        $sql = mysqli_query($conect, "DELETE FROM num_lavanderia WHERE num='$action_id';");
                    } else {
                        $result = array('erro' => true, 'msg_erro' => 'Número não existe.');
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
} elseif ($action_pagina == "usuario" && $action == "logar") {
    if (session_id() == '') {
        session_start();
    }
    if (isset($_SESSION['usuario'])) {
        $result = array('erro' => true, 'msg_erro' => 'Já existe um usuário logado.');
    } else {
        $usuario_logar_usuario = utf8_decode($_GET['usuario_logar_usuario']);
        $usuario_logar_senha = utf8_decode($_GET['usuario_logar_senha']);
        $resultados = mysqli_query($conect, "SELECT * FROM usuario WHERE usuario='$usuario_logar_usuario';");
        if (mysqli_num_rows($resultados) == 0) {
            $result = array('erro' => true, 'msg_erro' => 'Usuário não existe.');
        } else {
            $row = mysqli_fetch_assoc($resultados);
            if ($usuario_logar_usuario == utf8_encode($row['usuario']) && $usuario_logar_senha == utf8_encode($row['senha'])) {
                session_cache_expire(720);
                $_SESSION['usuario'] = $row;
                $result = array('erro' => false);
            } else {
                $result = array('erro' => true, 'msg_erro' => 'Usuário ou senha inválidos.');
            }
        }
    }
} else {
    $result = array('erro' => true, 'msg_erro' => 'Faça o login para continuar.');
}
mysqli_close($conect);

echo json_encode($result);
?>
