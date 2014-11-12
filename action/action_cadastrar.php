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
                default:
                    break;
            }
            break;
        case "numero":
            switch ($action) {
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
