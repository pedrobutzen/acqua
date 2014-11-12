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
                default :
                    break;
            }
            break;
        case "usuariogerenciamentoocorrencia":
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
                default :
                    break;
            }
            break;
        case "gerenciarocorrencia":
            switch ($action) {
                case 'montar':
                    $resultados = mysqli_query($conect, "SELECT peca.usuario, peca.idpeca, peca.usuario, peca.descricao, peca.marca, peca.cor, peca.tamanho, tipo.nome as nometipo, tipo.idtipo FROM peca JOIN(tipo) ON(peca.idtipo = tipo.idtipo) WHERE idpeca='$action_id'");
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
                            'usuario' => utf8_encode($row["usuario"]),
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
                default :
                    break;
            }
            break;
        case "tipoocorrencia":
            switch ($action) {
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
                case 'montar':
                    $resultados = mysqli_query($conect, "SELECT peca.usuario, peca.idpeca, peca.descricao, peca.marca, peca.cor, peca.tamanho, tipo.nome as nometipo, tipo.idtipo FROM peca JOIN(tipo) ON(peca.idtipo = tipo.idtipo) WHERE peca.usuario='$usuario_logado' AND idpeca='$action_id'");
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
        case "lancamentoativo":
            switch ($action) {
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
                default:
                    break;
            }
            break;
        case "visualizarlancamentousuario":
            switch ($action) {
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
                default:
                    break;
            }
            break;
        case "visualizarlancamentousuario":
            switch ($action) {
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
                default:
                    break;
            }
            break;
        case ($action_pagina == "entradapeca" || $action_pagina == "saidapeca"):
            switch ($action) {
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
                default :
                    break;
            }
            break;
        case ($action_pagina == "usuarioentradapeca" || $action_pagina == "usuariosaidapeca" || $action_pagina == "usuariogerenciarocorrencia" || $action_pagina == "usuariocadastrarocorrencia" || $action_pagina == "usuariovisualizarlancamento"):
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
                default:
                    break;
            }
            break;
        case "usuario-aluno":
            switch ($action) {
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
                default:
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
