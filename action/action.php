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
                case 'montar':
                    $resultados = mysqli_query($conect, "SELECT peca.idpeca, peca.descricao, peca.marca, peca.cor, peca.tamanho, tipo.nome as nometipo, tipo.idtipo FROM peca JOIN(tipo) ON(peca.idtipo = tipo.idtipo) WHERE idpeca='$action_id'");
                    if (mysqli_num_rows($resultados) == 0) {
                        $result = array('erro' => true, 'msg_erro' => 'Nenhuma peça encontrada.');
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
                                    'usuario_criacao' => utf8_encode($row2['usuario_criacao']),
                                    'usuario_finalizou' => utf8_encode($row2['usuario_finalizou']),
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
                            'idtipo' => $row["idtipo"],
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
        case ($action_pagina == "usuariogerenciamentoocorrencia"):
            switch ($action) {
                case 'montar':
                    $resultados_usuario = mysqli_query($conect, "SELECT * FROM usuario WHERE usuario='$action_id' OR num='$action_id'");
                    if (mysqli_num_rows($resultados_usuario) == 0) {
                        $resultados_usuario = NULL;
                        $result = array('erro' => true, 'msg_erro' => 'Nenhum usuário encontrado.');
                    } else {
                        $row = mysqli_fetch_array($resultados_usuario);
                        $_SESSION['usuarioentrada']['usuario'] = $row['usuario'];
                        $_SESSION['usuarioentrada']['status'] = 0;
                        $usuario_entrada = $_SESSION['usuarioentrada']['usuario'];
                        $action_id = $row['usuario'];
                        $resultados1 = mysqli_query($conect, "SELECT ISNULL(data_fim) as bloqueado FROM bloqueio WHERE usuario='$action_id' AND ISNULL(data_fim);");
                        $resultados2 = mysqli_query($conect, "SELECT ocorrencia.idocorrencia FROM peca JOIN(ocorrencia) ON(peca.idpeca = ocorrencia.idpeca) WHERE peca.usuario='$action_id' GROUP BY peca.idpeca");
                        if (mysqli_num_rows($resultados2) > 0) {
                            $row2 = mysqli_fetch_array($resultados2);
                            $hasocorrencia = utf8_encode($row2["idocorrencia"]);
                        } else {
                            $hasocorrencia = 0;
                        }

                        $row1 = mysqli_fetch_array($resultados1);
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
                            'hasocorrencia' => $hasocorrencia,
                        );
                        array_push($result, $dados);
                    }
                    break;
                default:
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
                case 'montar':
                    $resultados = mysqli_query($conect, "SELECT peca.usuario, peca.idpeca, peca.descricao, peca.marca, peca.cor, peca.tamanho, tipo.nome as nometipo, tipo.idtipo FROM peca JOIN(tipo) ON(peca.idtipo = tipo.idtipo) WHERE idpeca='$action_id'");
                    if (mysqli_num_rows($resultados) == 0) {
                        $result = array('erro' => true, 'msg_erro' => 'Nenhuma peça encontrada.');
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
                                    'tipoocorrencia' => utf8_encode($row2['tipo']),
                                    'usuario_criacao' => utf8_encode($row2['usuario_criacao']),
                                    'usuario_finalizou' => utf8_encode($row2['usuario_finalizou']),
                                );
                                array_push($ocorrencias, $ocorrencia);
                            }
                        }
                        $dados = array(
                            'idpeca' => utf8_encode($row["idpeca"]),
                            'descricaopeca' => utf8_encode($row["descricao"]),
                            'usuario' => utf8_encode($row["usuario"]),
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
                case 'finalizar':
                    $sql_ocorrencia = mysqli_query($conect, "SELECT status FROM ocorrencia WHERE idocorrencia='$action_id'");
                    if (mysqli_num_rows($sql_ocorrencia) == 0) {
                        $result = array('erro' => true, 'msg_erro' => 'Ocorrência não encontrada.');
                    } else {
                        $sql_update = mysqli_query($conect, "UPDATE ocorrencia SET status='0', usuario_finalizou='$usuario_logado' WHERE idocorrencia='$action_id';");
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
                case 'montar':
                    $resultados = mysqli_query($conect, "SELECT peca.idpeca, peca.usuario, peca.descricao, peca.marca, peca.cor, peca.tamanho, tipo.nome as nometipo, tipo.idtipo FROM peca JOIN(tipo) ON(peca.idtipo = tipo.idtipo) WHERE idpeca='$action_id'");
                    if (mysqli_num_rows($resultados) == 0) {
                        $result = array('erro' => true, 'msg_erro' => 'Nenhuma peça encontrada.');
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
                                    'tipoocorrencia' => utf8_encode($row2['tipo']),
                                    'usuario_criacao' => utf8_encode($row2['usuario_criacao']),
                                    'usuario_finalizou' => utf8_encode($row2['usuario_finalizou']),
                                );
                                array_push($ocorrencias, $ocorrencia);
                            }
                        }
                        $dados = array(
                            'idpeca' => utf8_encode($row["idpeca"]),
                            'descricaopeca' => utf8_encode($row["descricao"]),
                            'usuario' => utf8_encode($row["usuario"]),
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
                case 'finalizar':
                    $sql_ocorrencia = mysqli_query($conect, "SELECT status FROM ocorrencia WHERE idocorrencia='$action_id'");
                    if (mysqli_num_rows($sql_ocorrencia) == 0) {
                        $result = array('erro' => true, 'msg_erro' => 'Ocorrência não encontrada.');
                    } else {
                        $sql_update = mysqli_query($conect, "UPDATE ocorrencia SET status='0', usuario_finalizou='$usuario_logado' WHERE idocorrencia='$action_id';");
                    }
                    break;
                default :
                    break;
            }
            break;
        case "tipoocorrencia":
            switch ($action) {
                case 'cadastrar':
                    $tipo = $_GET['tipo'];
                    $sql = mysqli_query($conect, "SELECT * FROM tipo_ocorrencia WHERE tipo='$tipo'");
                    if (mysqli_num_rows($sql) != 0) {
                        $result = array('erro' => true, 'msg_erro' => 'Tipo de ocorrência já existe.');
                    } else {
                        $sql_insert = mysqli_query($conect, "INSERT INTO tipo_ocorrencia (tipo) VALUES ('$tipo');");
                    }
                    break;
                case 'editar':
                    $tipo = $_GET['tipo'];
                    $sql = mysqli_query($conect, "SELECT * FROM tipo_ocorrencia WHERE idtipo_ocorrencia='$action_id'");
                    if (mysqli_num_rows($sql) == 0) {
                        $result = array('erro' => true, 'msg_erro' => 'Tipo de ocorrência não encontrada.');
                    } else {
                        $sql_insert = mysqli_query($conect, "UPDATE tipo_ocorrencia SET tipo='$tipo' WHERE idtipo_ocorrencia='$action_id';");
                    }
                    break;
                case 'excluir':
                    $sql = mysqli_query($conect, "SELECT * FROM tipo_ocorrencia WHERE idtipo_ocorrencia='$action_id'");
                    if (mysqli_num_rows($sql) == 1) {
                        mysqli_query($conect, "DELETE FROM tipo_ocorrencia WHERE idtipo_ocorrencia='$action_id'");
                    } else {
                        $result = array('erro' => true, 'msg_erro' => 'Tipo de ocorrência não encontrado.');
                    }
                    break;
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
                case 'montar':
                    $resultados = mysqli_query($conect, "SELECT * FROM tipo_ocorrencia WHERE idtipo_ocorrencia='$action_id'");
                    if (mysqli_num_rows($resultados) == 0) {
                        $result = array('erro' => true, 'msg_erro' => 'Nenhum tipo de ocorrência encontrado.');
                    } else {
                        $row = mysqli_fetch_array($resultados);
                        $dados = array(
                            'idtipo_ocorrencia' => utf8_encode($row["idtipo_ocorrencia"]),
                            'tipo' => utf8_encode($row["tipo"]),
                        );
                        array_push($result, $dados);
                    }
                    break;
                default :
                    break;
            }
            break;
        case "ocorrencia":
            switch ($action) {
                case 'cadastrar':
                    $descricao = $_GET['descricao'];
                    $idtipo_ocorrencia = $_GET['idtipo_ocorrencia'];
                    $idpeca = $_GET['idpeca'];
                    $sql_peca = mysqli_query($conect, "SELECT status FROM peca WHERE idpeca='$idpeca'");
                    if (mysqli_num_rows($sql_peca) == 0) {
                        $result = array('erro' => true, 'msg_erro' => 'Peça não encontrada.');
                    } else {
                        $sql_tipo = mysqli_query($conect, "SELECT tipo FROM tipo_ocorrencia WHERE idtipo_ocorrencia='$idtipo_ocorrencia'");
                        if (mysqli_num_rows($sql_tipo) == 0) {
                            $result = array('erro' => true, 'msg_erro' => 'Tipo selecionado não encontrado.');
                        } else {
                            $sql_insert_lancamento = mysqli_query($conect, "INSERT INTO ocorrencia (descricao, status, idpeca, idtipo_ocorrencia, usuario_criacao) VALUES ('$descricao', '1', '$idpeca', '$idtipo_ocorrencia', '$usuario_logado');");
                            $id_lancamento = mysqli_insert_id($conect);
                        }
                    }
                    break;
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
                case 'montar':
                    $resultados = mysqli_query($conect, "SELECT peca.idpeca, peca.descricao, peca.marca, peca.cor, peca.tamanho, tipo.nome as nometipo, tipo.idtipo FROM peca JOIN(tipo) ON(peca.idtipo = tipo.idtipo) WHERE peca.usuario='$usuario_logado' AND idpeca='$action_id'");
                    if (mysqli_num_rows($resultados) == 0) {
                        $result = array('erro' => true, 'msg_erro' => 'Nenhuma peça encontrada.');
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
                                    'tipoocorrencia' => utf8_encode($row2['tipo']),
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
                            $result = array('erro' => true, 'msg_erro' => 'Peça marcada não encontrada.');
                        } elseif ($row['status'] == "0") {
                            mysqli_query($conect, "DELETE FROM lancamento WHERE idlancamento='$id_lancamento'");
                            $result = array('erro' => true, 'msg_erro' => 'Peça marcada não encontrada.');
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
                        $result = array('erro' => true, 'msg_erro' => 'Lançamento não encontrado.');
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
                case 'montar':
                    $resultados = mysqli_query($conect, "SELECT * FROM lancamento WHERE idlancamento='$action_id'");
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
                            'pecas' => $pecas,
                        );
                        array_push($result, $dados);
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
                case 'montar':
                    $resultados = mysqli_query($conect, "SELECT peca.idpeca, peca.descricao, peca.marca, peca.cor, peca.tamanho, tipo.nome as nometipo, tipo.idtipo FROM peca JOIN(tipo) ON(peca.idtipo = tipo.idtipo) WHERE idpeca='$action_id'");
                    if (mysqli_num_rows($resultados) == 0) {
                        $result = array('erro' => true, 'msg_erro' => 'Nenhuma peça encontrada.');
                    } else {
                        $row = mysqli_fetch_array($resultados);
                        $dados = array(
                            'idpeca' => utf8_encode($row["idpeca"]),
                            'descricaopeca' => utf8_encode($row["descricao"]),
                            'marca' => utf8_encode($row["marca"]),
                            'cor' => utf8_encode($row["cor"]),
                            'tamanho' => utf8_encode($row["tamanho"]),
                            'idtipo' => utf8_encode($row["idtipo"]),
                            'nometipo' => utf8_encode($row["nometipo"]),
                        );
                        array_push($result, $dados);
                    }
                    break;
                case 'excluir':
                    $usuario_entrada = $_SESSION['usuarioentrada']['usuario'];
                    $sql_select_lancamento = mysqli_query($conect, "SELECT idlancamento FROM lancamento WHERE usuario='$usuario_entrada' AND ISNULL(data_recebimento);");
                    $row = mysqli_fetch_array($sql_select_lancamento);
                    $idlancamento = $row['idlancamento'];
                    $sql = mysqli_query($conect, "SELECT idpeca FROM lancamento_has_peca WHERE idpeca='$action_id' AND idlancamento='$idlancamento'");
                    if (mysqli_num_rows($sql) == 1) {
                        mysqli_query($conect, "DELETE FROM lancamento_has_peca WHERE idpeca='$action_id' AND idlancamento='$idlancamento';");
                    } else {
                        $result = array('erro' => true, 'msg_erro' => 'Peça não encontrada.');
                    }
                    break;
                case 'confirmarentrada':
                    $usuario_entrada = $_SESSION['usuarioentrada']['usuario'];
                    $sql_select_lancamento = mysqli_query($conect, "SELECT idlancamento FROM lancamento WHERE usuario='$usuario_entrada' AND ISNULL(data_recebimento);");

                    if (mysqli_num_rows($sql_select_lancamento) == 1) {
                        $row = mysqli_fetch_array($sql_select_lancamento);
                        $idlancamento = $row['idlancamento'];
                        mysqli_query($conect, "UPDATE lancamento SET data_recebimento=NOW(), usuario_recebimento='$usuario_logado' WHERE idlancamento='$idlancamento';");
                    } else {
                        $result = array('erro' => true, 'msg_erro' => 'Lançamento não encontrado.');
                    }
                    break;
                case 'confirmarsaida':
                    $usuario_entrada = $_SESSION['usuarioentrada']['usuario'];
                    $sql_select_lancamento = mysqli_query($conect, "SELECT idlancamento FROM lancamento WHERE usuario='$usuario_entrada' AND !ISNULL(data_recebimento) AND ISNULL(data_devolucao);");

                    if (mysqli_num_rows($sql_select_lancamento) == 1) {
                        $row = mysqli_fetch_array($sql_select_lancamento);
                        $idlancamento = $row['idlancamento'];
                        mysqli_query($conect, "UPDATE lancamento SET data_devolucao=NOW(), usuario_devolucao='$usuario_logado' WHERE idlancamento='$idlancamento';");
                    } else {
                        $result = array('erro' => true, 'msg_erro' => 'Lançamento não encontrado.');
                    }
                    break;
                case 'logarusuario':
                    $usuario = $_SESSION['usuarioentrada']['usuario'];
                    $senha = utf8_decode($_GET['senha']);
                    $resultados = mysqli_query($conect, "SELECT * FROM usuario WHERE usuario='$usuario' AND permissao='3';");
                    if (mysqli_num_rows($resultados) == 0) {
                        $result = array('erro' => true, 'msg_erro' => 'Usuário não encontrado.');
                    } else {
                        $row = mysqli_fetch_assoc($resultados);
                        if ($usuario == utf8_encode($row['usuario']) && $senha == utf8_encode($row['senha'])) {
                            session_cache_expire(720);
                            $_SESSION['usuarioentrada']['status'] = 1;
                            $result = array('erro' => false);
                        } else {
                            $result = array('erro' => true, 'msg_erro' => 'Senha digitada está incorreta.');
                        }
                    }
                    break;
                case 'pegarlogado':
                    // unset($_SESSION['usuarioentrada']);
                    if (isset($_SESSION['usuarioentrada'])) {
                        $result = array('erro' => false, 'usuario' => $_SESSION['usuarioentrada']['usuario'], 'status' => $_SESSION['usuarioentrada']['status']);
                    } else {
                        $result = array('erro' => true, 'msg_erro' => 'Nenhum usuário entrou.');
                    }
                    break;
                default :
                    break;
            }
            break;
        case ($action_pagina == "usuarioentradapeca" || $action_pagina == "usuariosaidapeca" || $action_pagina == "usuariogerenciarocorrencia" || $action_pagina == "usuariocadastrarocorrencia"):
            switch ($action) {
                case 'montar':
                    $resultados_usuario = mysqli_query($conect, "SELECT * FROM usuario WHERE usuario='$action_id' OR num='$action_id'");
                    if (mysqli_num_rows($resultados_usuario) == 0) {
                        $resultados_usuario = NULL;
                        $result = array('erro' => true, 'msg_erro' => 'Nenhum usuário encontrado.');
                    } else {
                        $row = mysqli_fetch_array($resultados_usuario);
                        $_SESSION['usuarioentrada']['usuario'] = $row['usuario'];
                        $_SESSION['usuarioentrada']['status'] = 0;
                        $usuario_entrada = $_SESSION['usuarioentrada']['usuario'];
                        $action_id = $row['usuario'];
                        $resultados1 = mysqli_query($conect, "SELECT ISNULL(data_fim) as bloqueado FROM bloqueio WHERE usuario='$action_id' AND !ISNULL(data_inicio) AND ISNULL(data_fim);");
                        $resultados2 = mysqli_query($conect, "SELECT idlancamento as lancamentoativo FROM lancamento WHERE usuario='$action_id' AND ISNULL(data_recebimento);");
                        $resultados3 = mysqli_query($conect, "SELECT idlancamento as lancamentoativo FROM lancamento WHERE usuario='$action_id' AND !ISNULL(data_recebimento) AND ISNULL(data_devolucao);");
                        if ($action_pagina == "usuarioentradapeca") {
                            if (mysqli_num_rows($resultados2) > 0) {
                                $row2 = mysqli_fetch_array($resultados2);
                                $haslancamento = utf8_encode($row2["lancamentoativo"]);
                            } elseif (mysqli_num_rows($resultados3) > 0) {
                                $haslancamento = -1;
                            } else {
                                $haslancamento = 0;
                            }
                        } else {
                            if (mysqli_num_rows($resultados3) > 0) {
                                $row2 = mysqli_fetch_array($resultados3);
                                $haslancamento = utf8_encode($row2["lancamentoativo"]);
                            } elseif (mysqli_num_rows($resultados2) > 0) {
                                $haslancamento = -1;
                            } else {
                                $haslancamento = 0;
                            }
                        }

                        if (mysqli_num_rows($resultados1) != 0) {
                            $row1 = mysqli_fetch_array($resultados1);
                            $dados = array(
                                'nome' => utf8_encode($row["nome"]),
                                'usuario' => utf8_encode($row["usuario"]),
                                'sexo' => utf8_encode($row["sexo"]),
                                'ramal' => utf8_encode($row["ramal"]),
                                'quarto' => utf8_encode($row["quarto"]),
                                'email' => utf8_encode($row["email"]),
                                'num' => utf8_encode($row["num"]),
                                'senha' => utf8_encode($row["senha"]),
                                'permissao' => utf8_encode($row["permissao"]),
                                'bloqueado' => utf8_encode($row1["bloqueado"]),
                                'lancamentoativo' => $haslancamento,
                            );
                        } else {
                            $dados = array(
                                'nome' => utf8_encode($row["nome"]),
                                'usuario' => utf8_encode($row["usuario"]),
                                'sexo' => utf8_encode($row["sexo"]),
                                'ramal' => utf8_encode($row["ramal"]),
                                'email' => utf8_encode($row["email"]),
                                'quarto' => utf8_encode($row["quarto"]),
                                'num' => utf8_encode($row["num"]),
                                'senha' => utf8_encode($row["senha"]),
                                'permissao' => utf8_encode($row["permissao"]),
                                'bloqueado' => '0',
                                'lancamentoativo' => $haslancamento,
                            );
                        }
                        array_push($result, $dados);
                    }
                    break;
                default:
                    break;
            }
            break;
        case "usuario-funcionario":
            switch ($action) {
                case 'cadastrar':
                    $email_usuario = utf8_decode($_GET['email_usuario']);
                    $nome_usuario = utf8_decode($_GET['nome_usuario_cadastrar']);
                    $usuario_usuario = utf8_decode($_GET['usuario_usuario_cadastrar']);
                    $sexo_usuario = utf8_decode($_GET['sexo_usuario']);
                    $ramal_usuario = utf8_decode($_GET['ramal_usuario_cadastrar']);
                    $permissao_usuario = utf8_decode($_GET['permissao_usuario_cadastrar']);
                    $senha_usuario = utf8_decode($_GET['senha']);
                    $sql = mysqli_query($conect, "SELECT nome FROM usuario WHERE usuario='$usuario_usuario'");
                    if (mysqli_num_rows($sql) == "0") {
                        $sql = mysqli_query($conect, "INSERT INTO usuario (usuario, nome, senha, quarto, ramal, permissao, email, sexo) VALUES ('" . $usuario_usuario . "', '" . $nome_usuario . "', '$senha_usuario', NULL, '" . $ramal_usuario . "', '" . $permissao_usuario . "', '" . $email_usuario . "', '" . $sexo_usuario . "');");
                    } else {
                        $result = array('erro' => true, 'msg_erro' => 'Usuário já existe.');
                    }
                    break;
                case 'editar':
                    $email_usuario = utf8_decode($_GET['email_usuario']);
                    $nome_usuario = utf8_decode($_GET['nome_usuario']);
                    $usuario_usuario = utf8_decode($_GET['usuario_usuario']);
                    $ramal_usuario = utf8_decode($_GET['ramal_usuario']);
                    $sexo_usuario = utf8_decode($_GET['sexo_usuario']);
                    $permissao_usuario = utf8_decode($_GET['permissao_usuario']);
                    $sql = mysqli_query($conect, "SELECT nome FROM usuario WHERE usuario='$action_id'");
                    if (mysqli_num_rows($sql) == "0") {
                        $result = array('erro' => true, 'msg_erro' => 'Usuário não encontrado.');
                    } else {
                        $sql = mysqli_query($conect, "UPDATE usuario SET email='" . $email_usuario . "', usuario='" . $usuario_usuario . "', nome='" . $nome_usuario . "', sexo='" . $sexo_usuario . "', ramal='" . $ramal_usuario . "', permissao='" . $permissao_usuario . "' WHERE usuario='" . $action_id . "';");
                    }
                    break;
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
                                'email' => utf8_encode($row["email"]),
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
                        $result = array('erro' => true, 'msg_erro' => 'Usuário não encontrado.');
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
                    //print_r($result);exit;
                    break;
                case 'montar':
                    $resultados = mysqli_query($conect, "SELECT * FROM usuario WHERE usuario='$action_id'");
                    if (mysqli_num_rows($resultados) == 0) {
                        $result = array('erro' => true, 'msg_erro' => 'Nenhum usuário encontrado.');
                    } else {
                        $row = mysqli_fetch_array($resultados);
                        $usuario = $row["usuario"];
                        $resultados2 = mysqli_query($conect, "SELECT * FROM acqua_db.bloqueio WHERE usuario='$usuario' ORDER BY data_inicio");
                        $bloqueios = array('qtd_bloqueios' => mysqli_num_rows($resultados2));
                        if (mysqli_num_rows($resultados2) > 0) {
                            while ($row2 = mysqli_fetch_array($resultados2)) {
                                $data_inicio = "";
                                $data_fim = "";
                                if ($row2['data_inicio'] != NULL) {
                                    $data_inicio = date_format(date_create($row2['data_inicio']), 'd/m/Y H:i:s');
                                }
                                if ($row2['data_fim'] != NULL) {
                                    $data_fim = date_format(date_create($row2['data_fim']), 'd/m/Y H:i:s');
                                }
                                $bloqueio = array(
                                    'idbloqueio' => utf8_encode($row2['idbloqueio']),
                                    'data_inicio' => $data_inicio,
                                    'data_fim' => $data_fim,
                                    'usuario' => utf8_encode($row2['usuario']),
                                    'usuario_bloqueio' => utf8_encode($row2['usuario_bloqueio']),
                                    'usuario_desbloqueio' => utf8_encode($row2['usuario_desbloqueio']),
                                );
                                array_push($bloqueios, $bloqueio);
                            }
                        }
                        $resultados1 = mysqli_query($conect, "SELECT ISNULL(data_fim) as bloqueado FROM bloqueio WHERE usuario='$action_id' AND !ISNULL(data_inicio) AND ISNULL(data_fim);");
                        $row1 = mysqli_fetch_array($resultados1);
                        if (mysqli_num_rows($resultados1) != 0) {
                            $dados = array(
                                'nome' => utf8_encode($row["nome"]),
                                'usuario' => utf8_encode($row["usuario"]),
                                'sexo' => utf8_encode($row["sexo"]),
                                'ramal' => utf8_encode($row["ramal"]),
                                'quarto' => utf8_encode($row["quarto"]),
                                'email' => utf8_encode($row["email"]),
                                'num' => utf8_encode($row["num"]),
                                'senha' => utf8_encode($row["senha"]),
                                'permissao' => utf8_encode($row["permissao"]),
                                'bloqueado' => utf8_encode($row1["bloqueado"]),
                                'bloqueios' => $bloqueios,
                            );
                        } else {
                            $dados = array(
                                'nome' => utf8_encode($row["nome"]),
                                'usuario' => utf8_encode($row["usuario"]),
                                'sexo' => utf8_encode($row["sexo"]),
                                'ramal' => utf8_encode($row["ramal"]),
                                'quarto' => utf8_encode($row["quarto"]),
                                'email' => utf8_encode($row["email"]),
                                'num' => utf8_encode($row["num"]),
                                'senha' => utf8_encode($row["senha"]),
                                'permissao' => utf8_encode($row["permissao"]),
                                'bloqueado' => '0',
                                'bloqueios' => $bloqueios,
                            );
                        }
                        array_push($result, $dados);
                    }
                    break;
                case "bloquear":
                    $sql_usuario = mysqli_query($conect, "SELECT * FROM usuario WHERE usuario='$action_id';");
                    if (mysqli_num_rows($sql_usuario) == 0) {
                        $result = array('erro' => true, 'msg_erro' => 'Usuário não encontrado.');
                    } else {
                        $sql = mysqli_query($conect, "SELECT * FROM bloqueio WHERE usuario='$action_id' AND ISNULL(data_fim);");
                        if (mysqli_num_rows($sql) > 0) {
                            $result = array('erro' => true, 'msg_erro' => 'Usuário já possui bloqueio ativo.');
                        } else {
                            mysqli_query($conect, "INSERT INTO bloqueio (data_inicio, usuario, usuario_bloqueio) VALUES (NOW(), '$action_id', '$usuario_logado');");
                        }
                    }
                    break;
                case "desbloquear":
                    $sql_usuario = mysqli_query($conect, "SELECT * FROM usuario WHERE usuario='$action_id';");
                    if (mysqli_num_rows($sql_usuario) == 0) {
                        $result = array('erro' => true, 'msg_erro' => 'Usuário não encontrado.');
                    } else {
                        $sql = mysqli_query($conect, "SELECT * FROM bloqueio WHERE usuario='$action_id' AND ISNULL(data_fim);");
                        if (mysqli_num_rows($sql) > 0) {
                            mysqli_query($conect, "UPDATE bloqueio SET data_fim=NOW(), usuario_desbloqueio='$usuario_logado' WHERE usuario='$action_id' AND ISNULL(data_fim);");
                        } else {
                            $result = array('erro' => true, 'msg_erro' => 'Usuário não já possui bloqueio ativo.');
                        }
                    }
                    break;
                default:
                    break;
            }
            break;
        case "usuario":
            switch ($action) {
                case 'alterar_senha':
                    $senha_nova = $_GET['senha_nova'];
                    $senha_atual = $_GET['senha_atual'];
                    $sql = mysqli_query($conect, "SELECT * FROM usuario WHERE usuario='$usuario_logado' AND senha='$senha_atual'");
                    if (mysqli_num_rows($sql) == 0) {
                        $result = array('erro' => true, 'msg_erro' => 'Senha atual incorreta.');
                    } else {
                        $sql = mysqli_query($conect, "UPDATE usuario SET senha='$senha_nova' WHERE usuario='$usuario_logado';");
                    }
                    break;
                case 'reset_senha':
                    $sql = mysqli_query($conect, "SELECT * FROM usuario WHERE usuario='$action_id'");
                    if (mysqli_num_rows($sql) == "0") {
                        $result = array('erro' => true, 'msg_erro' => 'Usuário não encontrado.');
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
                        $result = array('erro' => true, 'msg_erro' => 'Usuário não encontrado.');
                    } else {
                        $sql = mysqli_query($conect, "UPDATE usuario SET num='$novo_numero'WHERE usuario='$usuario';");
                    }
                    break;
                case 'excluir':
                    $sql = mysqli_query($conect, "SELECT * FROM num_lavanderia WHERE num='$action_id'");
                    if (mysqli_num_rows($sql) == 1) {
                        $sql = mysqli_query($conect, "DELETE FROM num_lavanderia WHERE num='$action_id';");
                    } else {
                        $result = array('erro' => true, 'msg_erro' => 'Número não encontrado.');
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
            $result = array('erro' => true, 'msg_erro' => 'Usuário não encontrado.');
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
