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
        case $action_pagina == "cadastrarocorrencia" || $action_pagina == "gerenciarocorrencia":
            switch ($action) {
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
        case ($action_pagina == "entradapeca" || $action_pagina == "saidapeca"):
            switch ($action) {
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
                        $result = array('erro' => true, 'msg_erro' => 'Lançamento não encontradoss.');
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
        case "usuario-aluno":
            switch ($action) {
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
                case 'deslogar':
                    if (session_id() == '') {
                        session_start();
                    }
                    if (isset($_SESSION['usuario'])) {
                        unset($_SESSION['usuario']);
                        $result = array('erro' => false);
                    } else {
                        $result = array('erro' => true, 'msg_erro' => 'Não existe um usuário logado');
                    }
                    break;
                default:
                    break;
            }
            break;
        case "numero":
            switch ($action) {
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
                default:
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
