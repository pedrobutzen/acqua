<?php

header('Content-type: text/html; charset=UTF-8');
include_once '../../conexao/conexao.php';

$resultados = mysqli_query($conect, "SELECT l.idlancamento, u.usuario, u.email FROM lancamento as l "
        . "JOIN(usuario as u) ON(u.usuario=l.usuario) WHERE ISNULL(l.data_devolucao) AND !ISNULL(l.data_recebimento) AND u.sexo='m'");
if (mysqli_num_rows($resultados) > 0) {
    while ($row = mysqli_fetch_array($resultados)) {
        $idlancamento = $row['idlancamento'];
        $email = $row['email'];

        $html_pecas = "";

        $resultados_pecas = mysqli_query($conect, "SELECT p.*, t.nome as nometipo FROM peca as p JOIN(lancamento_has_peca as lp, "
                . "lancamento as l, tipo as t) ON(p.idpeca=lp.idpeca AND l.idlancamento=lp.idlancamento AND p.idtipo=t.idtipo) WHERE "
                . "l.idlancamento='$idlancamento'");
        if (mysqli_num_rows($resultados_pecas) != 0) {
            while ($row_peca = mysqli_fetch_array($resultados_pecas)) {
                $html_pecas .= '<tr><td style="padding: 8px;line-height: 1.42857143;border-top: 1px solid #ddd;text-align: left;">'
                        . $row_peca['descricao']
                        . '</td><td style="padding: 8px;line-height: 1.42857143;border-top: 1px solid #ddd;text-align: left;">'
                        . $row_peca['nometipo']
                        . '</td><td style="padding: 8px;line-height: 1.42857143;border-top: 1px solid #ddd;text-align: left;">'
                        . $row_peca['marca']
                        . '</td><td style="padding: 8px;line-height: 1.42857143;border-top: 1px solid #ddd;text-align: left;">'
                        . $row_peca['cor']
                        . '</td><td style="padding: 8px;line-height: 1.42857143;border-top: 1px solid #ddd;text-align: left;">'
                        . $row_peca['tamanho']
                        . '</td></tr>';
            }
        }

        $to = '' . $email;
        $subject = 'Lembrete';
        $message = ''; // <== Aqui vai todo o html concatenado com a variável $html_pecas.

        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

        $headers .= 'From: Acqua | Sistema de Lavanderia <lembrete@acqua.com>' . "\r\n";

        mail($to, $subject, $message, $headers);
    }
}