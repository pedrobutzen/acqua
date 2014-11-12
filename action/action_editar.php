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
                default :
                    break;
            }
            break;
        case "tipoocorrencia":
            switch ($action) {
                case 'editar':
                    $tipo = $_GET['tipo'];
                    $sql = mysqli_query($conect, "SELECT * FROM tipo_ocorrencia WHERE idtipo_ocorrencia='$action_id'");
                    if (mysqli_num_rows($sql) == 1) {
                        $sql = mysqli_query($conect, "SELECT * FROM tipo_ocorrencia WHERE tipo='$tipo'");
                        if (mysqli_num_rows($sql) == 0) {
                            $result = array('erro' => true, 'msg_erro' => 'Tipo de ocorrência não encontrada.');
                        } else {
                            $sql_insert = mysqli_query($conect, "UPDATE tipo_ocorrencia SET tipo='$tipo' WHERE idtipo_ocorrencia='$action_id';");
                        }
                    } else {
                        $result = array('erro' => true, 'msg_erro' => 'Tipo de ocorrência não encontrado.');
                    }
                    break;
                default :
                    break;
            }
            break;
        case "usuario-funcionario":
            switch ($action) {
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
                default:
                    break;
            }
            break;
        case "numero":
            switch ($action) {
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
