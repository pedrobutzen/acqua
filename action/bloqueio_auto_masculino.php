<?php

header('Content-type: text/html; charset=UTF-8');
include_once '../conexao/conexao.php';

$resultados = mysqli_query($conect, "SELECT u.usuario FROM lancamento as l JOIN(usuario as u) ON(u.usuario=l.usuario) WHERE "
        . "ISNULL(l.data_devolucao) AND !ISNULL(l.data_recebimento) AND u.sexo='m'");
if (mysqli_num_rows($resultados) > 0) {
    while ($row = mysqli_fetch_array($resultados)) {
        $usuario_bloquear = $row['usuario'];
        $resultados_desbloqueio = mysqli_query($conect, "SELECT * FROM bloqueio WHERE usuario='$usuario_bloquear' AND ISNULL(data_fim)");
        if (mysqli_num_rows($resultados_desbloqueio) == 0) {
            mysqli_query($conect, "INSERT INTO bloqueio (usuario, usuario_bloqueio) VALUES ('$usuario_bloquear', 'sistema');");
        }
    }
}

$resultados_desbloqueio = mysqli_query($conect, "SELECT usuario.usuario, bloqueio.data_inicio, bloqueio.idbloqueio FROM bloqueio JOIN(usuario) ON(bloqueio.usuario=usuario.usuario) WHERE usuario_bloqueio='sistema' AND ISNULL(data_fim) AND usuario.sexo='m'");
if (mysqli_num_rows($resultados_desbloqueio) > 0) {
    while ($row = mysqli_fetch_array($resultados_desbloqueio)) {
        $usuario_desloquear = $row['usuario'];
        $data_inicio_bloqueio = $row['data_inicio'];
        $idbloqueio = $row['idbloqueio'];

        $resultados_lancamento = mysqli_query($conect, "SELECT * FROM lancamento WHERE ISNULL(data_devolucao) AND usuario='$usuario_desloquear'");
        if (mysqli_num_rows($resultados_lancamento) == 0) {
            $inicio = DateTime::createFromFormat('Y-m-d H:i:s', $data_inicio_bloqueio);
            $fim = DateTime::createFromFormat('Y-m-d H:i:s', date("Y-m-d H:i:s"));

            $intervalo = $inicio->diff($fim)->days;
            if ($intervalo > 4) {
                mysqli_query($conect, "UPDATE bloqueio SET data_fim=NOW(), usuario_desbloqueio='sistema' WHERE idbloqueio='$idbloqueio';");
            }
        }
    }
}