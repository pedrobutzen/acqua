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
                default :
                    break;
            }
            break;
        case "tipoocorrencia":
            switch ($action) {
                case 'excluir':
                    $sql = mysqli_query($conect, "SELECT * FROM tipo_ocorrencia WHERE idtipo_ocorrencia='$action_id'");
                    if (mysqli_num_rows($sql) == 1) {
                        mysqli_query($conect, "DELETE FROM tipo_ocorrencia WHERE idtipo_ocorrencia='$action_id'");
                    } else {
                        $result = array('erro' => true, 'msg_erro' => 'Tipo de ocorrência não encontrado.');
                    }
                    break;
                default :
                    break;
            }
            break;
        case "lancamento":
            switch ($action) {
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
        case ($action_pagina == "entradapeca" || $action_pagina == "saidapeca"):
            switch ($action) {
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
                default :
                    break;
            }
            break;
        case "usuario-funcionario":
            switch ($action) {
                case 'excluir':
                    $sql = mysqli_query($conect, "SELECT nome FROM usuario WHERE usuario='$action_id'");
                    if (mysqli_num_rows($sql) == "1") {
                        $sql = mysqli_query($conect, "DELETE FROM usuario WHERE usuario='$action_id';");
                    } else {
                        $result = array('erro' => true, 'msg_erro' => 'Usuário funcionário não encontrado.');
                    }
                    break;
                default:
                    break;
            }
            break;
        case "numero":
            switch ($action) {
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
