<?php
$menu_page_active = "relatorio";

if (session_id() == '') {
    session_start();
}
?>
<div class="container-fluid">
    <div class="row-fluid">
        <div class="col-lg-12 cs-relatorio">
            <h2>Relatório <small>Visualizar os dados</small></h2>
            <hr>
            <div class="row-fluid">
                <h3 class="text-center">Todos</h3>
                <hr>
                <div class="col-lg-4">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Quantidade de Peças Atualmente na Lavanderia
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Início</th>
                                            <th>Fim</th>
                                            <th>Recebidas</th>
                                            <th>Ocorrências</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <?php
                                                unset($sql);
                                                unset($inicio);
                                                unset($fim);
                                                $sql = mysqli_query($conect, "SELECT MIN(data_recebimento) as inicio FROM lancamento WHERE !ISNULL(data_recebimento) AND ISNULL(data_devolucao)");
                                                $row = mysqli_fetch_array($sql);
                                                if ($row['inicio'] != "") {
                                                    $inicio = $row['inicio'];
                                                    echo date_format(date_create($row['inicio']), 'd/m/Y');
                                                } else {
                                                    $inicio = date('Y-m-d H:i:s', strtotime("-0 days"));
                                                    echo date_format(date_create($inicio), 'd/m/Y');
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                $sql = mysqli_query($conect, "SELECT NOW() as agora");
                                                $row = mysqli_fetch_array($sql);
                                                $fim = $row['agora'];
                                                echo date_format(date_create($row['agora']), 'd/m/Y');
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                $sql = mysqli_query($conect, "SELECT peca.idpeca FROM peca JOIN(lancamento_has_peca, lancamento) ON(peca.idpeca=lancamento_has_peca.idpeca AND lancamento_has_peca.idlancamento=lancamento.idlancamento) WHERE !ISNULL(lancamento.data_recebimento) AND ISNULL(lancamento.data_devolucao);");
                                                $geral = mysqli_num_rows($sql);
                                                echo $geral;
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                $sql = mysqli_query($conect, "SELECT ocorrencia.idocorrencia FROM peca JOIN(ocorrencia, tipo_ocorrencia) ON(peca.idpeca = ocorrencia.idpeca AND ocorrencia.idtipo_ocorrencia = tipo_ocorrencia.idtipo_ocorrencia) WHERE data_criacao>='$inicio' AND data_criacao<='$fim'");
                                                $geral = mysqli_num_rows($sql);
                                                echo $geral;
                                                ?>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Quantidade de Peças/Semana
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Início</th>
                                            <th>Fim</th>
                                            <th>Recebidas</th>
                                            <th>Ocorrências</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <?php
                                                $fim = date('Y-m-d H:i:s', strtotime("-0 days"));

                                                $inicio = date('Y-m-d H:i:s', strtotime("-6 days"));
                                                echo date_format(date_create($inicio), 'd/m/Y');
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                echo date_format(date_create($fim), 'd/m/Y');
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                $sql = mysqli_query($conect, "SELECT peca.idpeca FROM peca JOIN(lancamento_has_peca, lancamento) ON(peca.idpeca=lancamento_has_peca.idpeca AND lancamento_has_peca.idlancamento=lancamento.idlancamento) WHERE data_recebimento>='$inicio' AND data_recebimento<='$fim';");
                                                $geral = mysqli_num_rows($sql);
                                                echo $geral;
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                $sql = mysqli_query($conect, "SELECT ocorrencia.idocorrencia FROM peca JOIN(ocorrencia, tipo_ocorrencia) ON(peca.idpeca = ocorrencia.idpeca AND ocorrencia.idtipo_ocorrencia = tipo_ocorrencia.idtipo_ocorrencia) WHERE data_criacao>='$inicio' AND data_criacao<='$fim'");
                                                $geral = mysqli_num_rows($sql);
                                                echo $geral;
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <?php
                                                $fim = date('Y-m-d H:i:s', strtotime("-7 days"));

                                                $inicio = date('Y-m-d H:i:s', strtotime("-13 days"));
                                                echo date_format(date_create($inicio), 'd/m/Y');
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                echo date_format(date_create($fim), 'd/m/Y');
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                $sql = mysqli_query($conect, "SELECT peca.idpeca FROM peca JOIN(lancamento_has_peca, lancamento) ON(peca.idpeca=lancamento_has_peca.idpeca AND lancamento_has_peca.idlancamento=lancamento.idlancamento) WHERE data_recebimento>='$inicio' AND data_recebimento<='$fim';");
                                                $geral = mysqli_num_rows($sql);
                                                echo $geral;
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                $sql = mysqli_query($conect, "SELECT ocorrencia.idocorrencia FROM peca JOIN(ocorrencia, tipo_ocorrencia) ON(peca.idpeca = ocorrencia.idpeca AND ocorrencia.idtipo_ocorrencia = tipo_ocorrencia.idtipo_ocorrencia) WHERE data_criacao>='$inicio' AND data_criacao<='$fim'");
                                                $geral = mysqli_num_rows($sql);
                                                echo $geral;
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <?php
                                                $fim = date('Y-m-d H:i:s', strtotime("-14 days"));

                                                $inicio = date('Y-m-d H:i:s', strtotime("-20 days"));
                                                echo date_format(date_create($inicio), 'd/m/Y');
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                echo date_format(date_create($fim), 'd/m/Y');
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                $sql = mysqli_query($conect, "SELECT peca.idpeca FROM peca JOIN(lancamento_has_peca, lancamento) ON(peca.idpeca=lancamento_has_peca.idpeca AND lancamento_has_peca.idlancamento=lancamento.idlancamento) WHERE data_recebimento>='$inicio' AND data_recebimento<='$fim';");
                                                $geral = mysqli_num_rows($sql);
                                                echo $geral;
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                $sql = mysqli_query($conect, "SELECT ocorrencia.idocorrencia FROM peca JOIN(ocorrencia, tipo_ocorrencia) ON(peca.idpeca = ocorrencia.idpeca AND ocorrencia.idtipo_ocorrencia = tipo_ocorrencia.idtipo_ocorrencia) WHERE data_criacao>='$inicio' AND data_criacao<='$fim'");
                                                $geral = mysqli_num_rows($sql);
                                                echo $geral;
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <?php
                                                $fim = strtotime("-21 days");

                                                $inicio = strtotime("-27 days");
                                                echo date('d/m/Y', $inicio);
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                echo date('d/m/Y', $fim);
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                $sql = mysqli_query($conect, "SELECT peca.idpeca FROM peca JOIN(lancamento_has_peca, lancamento) ON(peca.idpeca=lancamento_has_peca.idpeca AND lancamento_has_peca.idlancamento=lancamento.idlancamento) WHERE data_recebimento>='$inicio' AND data_recebimento<='$fim';");
                                                $geral = mysqli_num_rows($sql);
                                                echo $geral;
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                $sql = mysqli_query($conect, "SELECT ocorrencia.idocorrencia FROM peca JOIN(ocorrencia, tipo_ocorrencia) ON(peca.idpeca = ocorrencia.idpeca AND ocorrencia.idtipo_ocorrencia = tipo_ocorrencia.idtipo_ocorrencia) WHERE data_criacao>='$inicio' AND data_criacao<='$fim'");
                                                $geral = mysqli_num_rows($sql);
                                                echo $geral;
                                                ?>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Quantidade de Peças/Mês
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Início</th>
                                            <th>Fim</th>
                                            <th>Recebidas</th>
                                            <th>Ocorrências</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <?php
                                                unset($sql);
                                                $fim = date('Y-m-d H:i:s', strtotime("-0 month"));

                                                $inicio = date('Y-m-d H:i:s', strtotime("-1 month"));
                                                echo date_format(date_create($inicio), 'd/m/Y');
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                unset($sql);
                                                echo date_format(date_create($fim), 'd/m/Y');
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                unset($sql);
                                                $sql = mysqli_query($conect, "SELECT peca.idpeca FROM peca JOIN(lancamento_has_peca, lancamento) ON(peca.idpeca=lancamento_has_peca.idpeca AND lancamento_has_peca.idlancamento=lancamento.idlancamento) WHERE data_recebimento>='$inicio' AND data_recebimento<='$fim';");
                                                $geral = mysqli_num_rows($sql);
                                                echo $geral;
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                unset($sql);
                                                unset($sql);
                                                $sql = mysqli_query($conect, "SELECT ocorrencia.idocorrencia FROM peca JOIN(ocorrencia, tipo_ocorrencia) ON(peca.idpeca = ocorrencia.idpeca AND ocorrencia.idtipo_ocorrencia = tipo_ocorrencia.idtipo_ocorrencia) WHERE data_criacao>='$inicio' AND data_criacao<='$fim'");
                                                $geral = mysqli_num_rows($sql);
                                                echo $geral;
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <?php
                                                unset($sql);
                                                unset($inicio);
                                                unset($fim);
                                                $fim = strtotime("-1 month -1 days");

                                                $inicio = strtotime("-2 month -1 days");
                                                echo date('d/m/Y', $inicio);
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                unset($sql);
                                                echo date('d/m/Y', $fim);
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                unset($sql);
                                                $sql = mysqli_query($conect, "SELECT peca.idpeca FROM peca JOIN(lancamento_has_peca, lancamento) ON(peca.idpeca=lancamento_has_peca.idpeca AND lancamento_has_peca.idlancamento=lancamento.idlancamento) WHERE data_recebimento>='$inicio' AND data_recebimento<='$fim';");
                                                $geral = mysqli_num_rows($sql);
                                                echo $geral;
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                $sql = mysqli_query($conect, "SELECT ocorrencia.idocorrencia FROM peca JOIN(ocorrencia, tipo_ocorrencia) ON(peca.idpeca = ocorrencia.idpeca AND ocorrencia.idtipo_ocorrencia = tipo_ocorrencia.idtipo_ocorrencia) WHERE data_criacao>='$inicio' AND data_criacao<='$fim'");
                                                $geral = mysqli_num_rows($sql);
                                                echo $geral;
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <?php
                                                unset($sql);
                                                unset($inicio);
                                                unset($fim);
                                                $fim = strtotime("-2 month -2 days");

                                                $inicio = strtotime("-3 month -2 days");
                                                echo date('d/m/Y', $inicio);
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                echo date('d/m/Y', $fim);
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                $sql = mysqli_query($conect, "SELECT peca.idpeca FROM peca JOIN(lancamento_has_peca, lancamento) ON(peca.idpeca=lancamento_has_peca.idpeca AND lancamento_has_peca.idlancamento=lancamento.idlancamento) WHERE data_recebimento>='$inicio' AND data_recebimento<='$fim';");
                                                $geral = mysqli_num_rows($sql);
                                                echo $geral;
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                $sql = mysqli_query($conect, "SELECT ocorrencia.idocorrencia FROM peca JOIN(ocorrencia, tipo_ocorrencia) ON(peca.idpeca = ocorrencia.idpeca AND ocorrencia.idtipo_ocorrencia = tipo_ocorrencia.idtipo_ocorrencia) WHERE data_criacao>='$inicio' AND data_criacao<='$fim'");
                                                $geral = mysqli_num_rows($sql);
                                                echo $geral;
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <?php
                                                unset($sql);
                                                unset($inicio);
                                                unset($fim);
                                                $fim = strtotime("-3 month -3 days");

                                                $inicio = strtotime("-4 month -3 days");
                                                echo date('d/m/Y', $inicio);
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                echo date('d/m/Y', $fim);
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                $sql = mysqli_query($conect, "SELECT peca.idpeca FROM peca JOIN(lancamento_has_peca, lancamento) ON(peca.idpeca=lancamento_has_peca.idpeca AND lancamento_has_peca.idlancamento=lancamento.idlancamento) WHERE data_recebimento>='$inicio' AND data_recebimento<='$fim';");
                                                $geral = mysqli_num_rows($sql);
                                                echo $geral;
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                $sql = mysqli_query($conect, "SELECT ocorrencia.idocorrencia FROM peca JOIN(ocorrencia, tipo_ocorrencia) ON(peca.idpeca = ocorrencia.idpeca AND ocorrencia.idtipo_ocorrencia = tipo_ocorrencia.idtipo_ocorrencia) WHERE data_criacao>='$inicio' AND data_criacao<='$fim'");
                                                $geral = mysqli_num_rows($sql);
                                                echo $geral;
                                                ?>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <h3 class="text-center">Feminino</h3>
                <hr>
                <div class="col-lg-4">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Quantidade de Peças Atualmente na Lavanderia
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Início</th>
                                            <th>Fim</th>
                                            <th>Recebidas</th>
                                            <th>Ocorrências</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <?php
                                                unset($sql);
                                                unset($inicio);
                                                unset($fim);
                                                $sql = mysqli_query($conect, "SELECT MIN(data_recebimento) as inicio FROM lancamento JOIN(usuario) ON(usuario.usuario=lancamento.usuario) WHERE !ISNULL(data_recebimento) AND ISNULL(data_devolucao) AND usuario.sexo='f'");
                                                $row = mysqli_fetch_array($sql);
                                                if ($row['inicio'] != "") {
                                                    $inicio = $row['inicio'];
                                                    echo date_format(date_create($row['inicio']), 'd/m/Y');
                                                } else {
                                                    $inicio = date('Y-m-d H:i:s', strtotime("-0 days"));
                                                    echo date_format(date_create($inicio), 'd/m/Y');
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                $sql = mysqli_query($conect, "SELECT NOW() as agora");
                                                $row = mysqli_fetch_array($sql);
                                                $fim = $row['agora'];
                                                echo date_format(date_create($row['agora']), 'd/m/Y');
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                $sql = mysqli_query($conect, "SELECT peca.idpeca FROM peca JOIN(lancamento_has_peca, lancamento, usuario) ON(peca.idpeca=lancamento_has_peca.idpeca AND lancamento_has_peca.idlancamento=lancamento.idlancamento AND usuario.usuario=lancamento.usuario) WHERE !ISNULL(lancamento.data_recebimento) AND ISNULL(lancamento.data_devolucao) AND usuario.sexo='f' AND data_recebimento>='$inicio' AND data_recebimento<='$fim'");
                                                $geral = mysqli_num_rows($sql);
                                                echo $geral;
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                $sql = mysqli_query($conect, "SELECT ocorrencia.idocorrencia FROM peca JOIN(ocorrencia, tipo_ocorrencia, usuario) ON(peca.idpeca = ocorrencia.idpeca AND ocorrencia.idtipo_ocorrencia = tipo_ocorrencia.idtipo_ocorrencia AND usuario.usuario=peca.usuario) WHERE data_criacao>='$inicio' AND data_criacao<='$fim' AND usuario.sexo='f'");
                                                $geral = mysqli_num_rows($sql);
                                                echo $geral;
                                                ?>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Quantidade de Peças/Semana
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Início</th>
                                            <th>Fim</th>
                                            <th>Recebidas</th>
                                            <th>Ocorrências</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <?php
                                                unset($sql);
                                                unset($inicio);
                                                unset($fim);
                                                $fim = date('Y-m-d H:i:s', strtotime("-0 days"));

                                                $inicio = date('Y-m-d H:i:s', strtotime("-6 days"));
                                                echo date_format(date_create($inicio), 'd/m/Y');
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                echo date_format(date_create($fim), 'd/m/Y');
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                $sql = mysqli_query($conect, "SELECT peca.idpeca FROM peca JOIN(lancamento_has_peca, lancamento, usuario) ON(peca.idpeca=lancamento_has_peca.idpeca AND lancamento_has_peca.idlancamento=lancamento.idlancamento AND usuario.usuario=lancamento.usuario) WHERE usuario.sexo='f' AND data_recebimento>='$inicio' AND data_recebimento<='$fim';");
                                                $geral = mysqli_num_rows($sql);
                                                echo $geral;
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                $sql = mysqli_query($conect, "SELECT ocorrencia.idocorrencia FROM peca JOIN(ocorrencia, tipo_ocorrencia, usuario) ON(peca.idpeca = ocorrencia.idpeca AND ocorrencia.idtipo_ocorrencia = tipo_ocorrencia.idtipo_ocorrencia AND usuario.usuario=peca.usuario) WHERE data_criacao>='$inicio' AND data_criacao<='$fim' AND usuario.sexo='f'");
                                                $geral = mysqli_num_rows($sql);
                                                echo $geral;
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <?php
                                                unset($sql);
                                                unset($inicio);
                                                unset($fim);
                                                $fim = date('Y-m-d H:i:s', strtotime("-7 days"));

                                                $inicio = date('Y-m-d H:i:s', strtotime("-13 days"));
                                                echo date_format(date_create($inicio), 'd/m/Y');
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                echo date_format(date_create($fim), 'd/m/Y');
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                $sql = mysqli_query($conect, "SELECT peca.idpeca FROM peca JOIN(lancamento_has_peca, lancamento, usuario) ON(peca.idpeca=lancamento_has_peca.idpeca AND lancamento_has_peca.idlancamento=lancamento.idlancamento AND usuario.usuario=lancamento.usuario) WHERE usuario.sexo='f' AND data_recebimento>='$inicio' AND data_recebimento<='$fim';");
                                                $geral = mysqli_num_rows($sql);
                                                echo $geral;
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                $sql = mysqli_query($conect, "SELECT ocorrencia.idocorrencia FROM peca JOIN(ocorrencia, tipo_ocorrencia, usuario) ON(peca.idpeca = ocorrencia.idpeca AND ocorrencia.idtipo_ocorrencia = tipo_ocorrencia.idtipo_ocorrencia AND usuario.usuario=peca.usuario) WHERE data_criacao>='$inicio' AND data_criacao<='$fim' AND usuario.sexo='f'");
                                                $geral = mysqli_num_rows($sql);
                                                echo $geral;
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <?php
                                                unset($sql);
                                                unset($inicio);
                                                unset($fim);
                                                $fim = date('Y-m-d H:i:s', strtotime("-14 days"));

                                                $inicio = date('Y-m-d H:i:s', strtotime("-20 days"));
                                                echo date_format(date_create($inicio), 'd/m/Y');
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                echo date_format(date_create($fim), 'd/m/Y');
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                $sql = mysqli_query($conect, "SELECT peca.idpeca FROM peca JOIN(lancamento_has_peca, lancamento, usuario) ON(peca.idpeca=lancamento_has_peca.idpeca AND lancamento_has_peca.idlancamento=lancamento.idlancamento AND usuario.usuario=lancamento.usuario) WHERE usuario.sexo='f' AND data_recebimento>='$inicio' AND data_recebimento<='$fim';");
                                                $geral = mysqli_num_rows($sql);
                                                echo $geral;
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                $sql = mysqli_query($conect, "SELECT ocorrencia.idocorrencia FROM peca JOIN(ocorrencia, tipo_ocorrencia, usuario) ON(peca.idpeca = ocorrencia.idpeca AND ocorrencia.idtipo_ocorrencia = tipo_ocorrencia.idtipo_ocorrencia AND usuario.usuario=peca.usuario) WHERE data_criacao>='$inicio' AND data_criacao<='$fim' AND usuario.sexo='f'");
                                                $geral = mysqli_num_rows($sql);
                                                echo $geral;
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <?php
                                                unset($sql);
                                                unset($inicio);
                                                unset($fim);
                                                $fim = strtotime("-21 days");

                                                $inicio = strtotime("-27 days");
                                                echo date('d/m/Y', $inicio);
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                echo date('d/m/Y', $fim);
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                $sql = mysqli_query($conect, "SELECT peca.idpeca FROM peca JOIN(lancamento_has_peca, lancamento, usuario) ON(peca.idpeca=lancamento_has_peca.idpeca AND lancamento_has_peca.idlancamento=lancamento.idlancamento AND usuario.usuario=lancamento.usuario) WHERE usuario.sexo='f' AND data_recebimento>='$inicio' AND data_recebimento<='$fim';");
                                                $geral = mysqli_num_rows($sql);
                                                echo $geral;
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                $sql = mysqli_query($conect, "SELECT ocorrencia.idocorrencia FROM peca JOIN(ocorrencia, tipo_ocorrencia, usuario) ON(peca.idpeca = ocorrencia.idpeca AND ocorrencia.idtipo_ocorrencia = tipo_ocorrencia.idtipo_ocorrencia AND usuario.usuario=peca.usuario) WHERE data_criacao>='$inicio' AND data_criacao<='$fim' AND usuario.sexo='f'");
                                                $geral = mysqli_num_rows($sql);
                                                echo $geral;
                                                ?>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Quantidade de Peças/Mês
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Início</th>
                                            <th>Fim</th>
                                            <th>Recebidas</th>
                                            <th>Ocorrências</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <?php
                                                unset($sql);
                                                unset($inicio);
                                                unset($fim);
                                                $fim = date('Y-m-d H:i:s', strtotime("-0 month"));

                                                $inicio = date('Y-m-d H:i:s', strtotime("-1 month"));
                                                echo date_format(date_create($inicio), 'd/m/Y');
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                echo date_format(date_create($fim), 'd/m/Y');
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                $sql = mysqli_query($conect, "SELECT peca.idpeca FROM peca JOIN(lancamento_has_peca, lancamento, usuario) ON(peca.idpeca=lancamento_has_peca.idpeca AND lancamento_has_peca.idlancamento=lancamento.idlancamento AND usuario.usuario=lancamento.usuario) WHERE usuario.sexo='f' AND data_recebimento>='$inicio' AND data_recebimento<='$fim';");
                                                $geral = mysqli_num_rows($sql);
                                                echo $geral;
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                $sql = mysqli_query($conect, "SELECT ocorrencia.idocorrencia FROM peca JOIN(ocorrencia, tipo_ocorrencia, usuario) ON(peca.idpeca = ocorrencia.idpeca AND ocorrencia.idtipo_ocorrencia = tipo_ocorrencia.idtipo_ocorrencia AND usuario.usuario=peca.usuario) WHERE data_criacao>='$inicio' AND data_criacao<='$fim' AND usuario.sexo='f'");
                                                $geral = mysqli_num_rows($sql);
                                                echo $geral;
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <?php
                                                unset($sql);
                                                unset($inicio);
                                                unset($fim);
                                                $fim = strtotime("-1 month -1 days");

                                                $inicio = strtotime("-2 month -1 days");
                                                echo date('d/m/Y', $inicio);
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                echo date('d/m/Y', $fim);
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                $sql = mysqli_query($conect, "SELECT peca.idpeca FROM peca JOIN(lancamento_has_peca, lancamento, usuario) ON(peca.idpeca=lancamento_has_peca.idpeca AND lancamento_has_peca.idlancamento=lancamento.idlancamento AND usuario.usuario=lancamento.usuario) WHERE usuario.sexo='f' AND data_recebimento>='$inicio' AND data_recebimento<='$fim';");
                                                $geral = mysqli_num_rows($sql);
                                                echo $geral;
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                $sql = mysqli_query($conect, "SELECT ocorrencia.idocorrencia FROM peca JOIN(ocorrencia, tipo_ocorrencia, usuario) ON(peca.idpeca = ocorrencia.idpeca AND ocorrencia.idtipo_ocorrencia = tipo_ocorrencia.idtipo_ocorrencia AND usuario.usuario=peca.usuario) WHERE data_criacao>='$inicio' AND data_criacao<='$fim' AND usuario.sexo='f'");
                                                $geral = mysqli_num_rows($sql);
                                                echo $geral;
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <?php
                                                unset($sql);
                                                unset($inicio);
                                                unset($fim);
                                                $fim = strtotime("-2 month -2 days");

                                                $inicio = strtotime("-3 month -2 days");
                                                echo date('d/m/Y', $inicio);
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                echo date('d/m/Y', $fim);
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                $sql = mysqli_query($conect, "SELECT peca.idpeca FROM peca JOIN(lancamento_has_peca, lancamento, usuario) ON(peca.idpeca=lancamento_has_peca.idpeca AND lancamento_has_peca.idlancamento=lancamento.idlancamento AND usuario.usuario=lancamento.usuario) WHERE usuario.sexo='f' AND data_recebimento>='$inicio' AND data_recebimento<='$fim';");
                                                $geral = mysqli_num_rows($sql);
                                                echo $geral;
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                $sql = mysqli_query($conect, "SELECT ocorrencia.idocorrencia FROM peca JOIN(ocorrencia, tipo_ocorrencia, usuario) ON(peca.idpeca = ocorrencia.idpeca AND ocorrencia.idtipo_ocorrencia = tipo_ocorrencia.idtipo_ocorrencia AND usuario.usuario=peca.usuario) WHERE data_criacao>='$inicio' AND data_criacao<='$fim' AND usuario.sexo='f'");
                                                $geral = mysqli_num_rows($sql);
                                                echo $geral;
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <?php
                                                unset($sql);
                                                unset($inicio);
                                                unset($fim);
                                                $fim = strtotime("-3 month -3 days");

                                                $inicio = strtotime("-4 month -3 days");
                                                echo date('d/m/Y', $inicio);
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                echo date('d/m/Y', $fim);
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                $sql = mysqli_query($conect, "SELECT peca.idpeca FROM peca JOIN(lancamento_has_peca, lancamento, usuario) ON(peca.idpeca=lancamento_has_peca.idpeca AND lancamento_has_peca.idlancamento=lancamento.idlancamento AND usuario.usuario=lancamento.usuario) WHERE usuario.sexo='f' AND data_recebimento>='$inicio' AND data_recebimento<='$fim';");
                                                $geral = mysqli_num_rows($sql);
                                                echo $geral;
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                $sql = mysqli_query($conect, "SELECT ocorrencia.idocorrencia FROM peca JOIN(ocorrencia, tipo_ocorrencia, usuario) ON(peca.idpeca = ocorrencia.idpeca AND ocorrencia.idtipo_ocorrencia = tipo_ocorrencia.idtipo_ocorrencia AND usuario.usuario=peca.usuario) WHERE data_criacao>='$inicio' AND data_criacao<='$fim' AND usuario.sexo='f'");
                                                $geral = mysqli_num_rows($sql);
                                                echo $geral;
                                                ?>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <h3 class="text-center">Masculino</h3>
                <hr>
                <div class="col-lg-4">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Quantidade de Peças Atualmente na Lavanderia
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Início</th>
                                            <th>Fim</th>
                                            <th>Recebidas</th>
                                            <th>Ocorrências</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <?php
                                                unset($sql);
                                                unset($inicio);
                                                unset($fim);
                                                $sql = mysqli_query($conect, "SELECT MIN(data_recebimento) as inicio FROM lancamento JOIN(usuario) ON(usuario.usuario=lancamento.usuario) WHERE !ISNULL(data_recebimento) AND ISNULL(data_devolucao) AND usuario.sexo='m'");
                                                $row = mysqli_fetch_array($sql);
                                                if ($row['inicio'] != "") {
                                                    $inicio = $row['inicio'];
                                                    echo date_format(date_create($row['inicio']), 'd/m/Y');
                                                } else {
                                                    $inicio = date('Y-m-d H:i:s', strtotime("-0 days"));
                                                    echo date_format(date_create($inicio), 'd/m/Y');
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                $sql = mysqli_query($conect, "SELECT NOW() as agora");
                                                $row = mysqli_fetch_array($sql);
                                                $fim = $row['agora'];
                                                echo date_format(date_create($row['agora']), 'd/m/Y');
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                $sql = mysqli_query($conect, "SELECT peca.idpeca FROM peca JOIN(lancamento_has_peca, lancamento, usuario) ON(peca.idpeca=lancamento_has_peca.idpeca AND lancamento_has_peca.idlancamento=lancamento.idlancamento AND usuario.usuario=lancamento.usuario) WHERE  !ISNULL(lancamento.data_recebimento) AND ISNULL(lancamento.data_devolucao) AND usuario.sexo='m' AND data_recebimento>='$inicio' AND data_recebimento<='$fim'");
                                                $geral = mysqli_num_rows($sql);
                                                echo $geral;
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                $sql = mysqli_query($conect, "SELECT ocorrencia.idocorrencia FROM peca JOIN(ocorrencia, tipo_ocorrencia, usuario) ON(peca.idpeca = ocorrencia.idpeca AND ocorrencia.idtipo_ocorrencia = tipo_ocorrencia.idtipo_ocorrencia AND usuario.usuario=peca.usuario) WHERE data_criacao>='$inicio' AND data_criacao<='$fim' AND usuario.sexo='m'");
                                                $geral = mysqli_num_rows($sql);
                                                echo $geral;
                                                ?>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Quantidade de Peças/Semana
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Início</th>
                                            <th>Fim</th>
                                            <th>Recebidas</th>
                                            <th>Ocorrências</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <?php
                                                unset($sql);
                                                unset($inicio);
                                                unset($fim);
                                                $fim = date('Y-m-d H:i:s', strtotime("-0 days"));

                                                $inicio = date('Y-m-d H:i:s', strtotime("-6 days"));
                                                echo date_format(date_create($inicio), 'd/m/Y');
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                echo date_format(date_create($fim), 'd/m/Y');
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                $sql = mysqli_query($conect, "SELECT peca.idpeca FROM peca JOIN(lancamento_has_peca, lancamento, usuario) ON(peca.idpeca=lancamento_has_peca.idpeca AND lancamento_has_peca.idlancamento=lancamento.idlancamento AND usuario.usuario=lancamento.usuario) WHERE usuario.sexo='m' AND data_recebimento>='$inicio' AND data_recebimento<='$fim';");
                                                $geral = mysqli_num_rows($sql);
                                                echo $geral;
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                $sql = mysqli_query($conect, "SELECT ocorrencia.idocorrencia FROM peca JOIN(ocorrencia, tipo_ocorrencia, usuario) ON(peca.idpeca = ocorrencia.idpeca AND ocorrencia.idtipo_ocorrencia = tipo_ocorrencia.idtipo_ocorrencia AND usuario.usuario=peca.usuario) WHERE data_criacao>='$inicio' AND data_criacao<='$fim' AND usuario.sexo='m'");
                                                $geral = mysqli_num_rows($sql);
                                                echo $geral;
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <?php
                                                unset($sql);
                                                unset($inicio);
                                                unset($fim);
                                                $fim = date('Y-m-d H:i:s', strtotime("-7 days"));

                                                $inicio = date('Y-m-d H:i:s', strtotime("-13 days"));
                                                echo date_format(date_create($inicio), 'd/m/Y');
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                echo date_format(date_create($fim), 'd/m/Y');
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                $sql = mysqli_query($conect, "SELECT peca.idpeca FROM peca JOIN(lancamento_has_peca, lancamento, usuario) ON(peca.idpeca=lancamento_has_peca.idpeca AND lancamento_has_peca.idlancamento=lancamento.idlancamento AND usuario.usuario=lancamento.usuario) WHERE usuario.sexo='m' AND data_recebimento>='$inicio' AND data_recebimento<='$fim';");
                                                $geral = mysqli_num_rows($sql);
                                                echo $geral;
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                $sql = mysqli_query($conect, "SELECT ocorrencia.idocorrencia FROM peca JOIN(ocorrencia, tipo_ocorrencia, usuario) ON(peca.idpeca = ocorrencia.idpeca AND ocorrencia.idtipo_ocorrencia = tipo_ocorrencia.idtipo_ocorrencia AND usuario.usuario=peca.usuario) WHERE data_criacao>='$inicio' AND data_criacao<='$fim' AND usuario.sexo='m'");
                                                $geral = mysqli_num_rows($sql);
                                                echo $geral;
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <?php
                                                unset($sql);
                                                unset($inicio);
                                                unset($fim);
                                                $fim = date('Y-m-d H:i:s', strtotime("-14 days"));

                                                $inicio = date('Y-m-d H:i:s', strtotime("-20 days"));
                                                echo date_format(date_create($inicio), 'd/m/Y');
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                echo date_format(date_create($fim), 'd/m/Y');
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                $sql = mysqli_query($conect, "SELECT peca.idpeca FROM peca JOIN(lancamento_has_peca, lancamento, usuario) ON(peca.idpeca=lancamento_has_peca.idpeca AND lancamento_has_peca.idlancamento=lancamento.idlancamento AND usuario.usuario=lancamento.usuario) WHERE usuario.sexo='m' AND data_recebimento>='$inicio' AND data_recebimento<='$fim';");
                                                $geral = mysqli_num_rows($sql);
                                                echo $geral;
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                $sql = mysqli_query($conect, "SELECT ocorrencia.idocorrencia FROM peca JOIN(ocorrencia, tipo_ocorrencia, usuario) ON(peca.idpeca = ocorrencia.idpeca AND ocorrencia.idtipo_ocorrencia = tipo_ocorrencia.idtipo_ocorrencia AND usuario.usuario=peca.usuario) WHERE data_criacao>='$inicio' AND data_criacao<='$fim' AND usuario.sexo='m'");
                                                $geral = mysqli_num_rows($sql);
                                                echo $geral;
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <?php
                                                unset($sql);
                                                unset($inicio);
                                                unset($fim);
                                                $fim = strtotime("-21 days");

                                                $inicio = strtotime("-27 days");
                                                echo date('d/m/Y', $inicio);
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                echo date('d/m/Y', $fim);
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                $sql = mysqli_query($conect, "SELECT peca.idpeca FROM peca JOIN(lancamento_has_peca, lancamento, usuario) ON(peca.idpeca=lancamento_has_peca.idpeca AND lancamento_has_peca.idlancamento=lancamento.idlancamento AND usuario.usuario=lancamento.usuario) WHERE usuario.sexo='m' AND data_recebimento>='$inicio' AND data_recebimento<='$fim';");
                                                $geral = mysqli_num_rows($sql);
                                                echo $geral;
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                $sql = mysqli_query($conect, "SELECT ocorrencia.idocorrencia FROM peca JOIN(ocorrencia, tipo_ocorrencia, usuario) ON(peca.idpeca = ocorrencia.idpeca AND ocorrencia.idtipo_ocorrencia = tipo_ocorrencia.idtipo_ocorrencia AND usuario.usuario=peca.usuario) WHERE data_criacao>='$inicio' AND data_criacao<='$fim' AND usuario.sexo='m'");
                                                $geral = mysqli_num_rows($sql);
                                                echo $geral;
                                                ?>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Quantidade de Peças/Mês
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Início</th>
                                            <th>Fim</th>
                                            <th>Recebidas</th>
                                            <th>Ocorrências</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <?php
                                                unset($sql);
                                                unset($inicio);
                                                unset($fim);
                                                $fim = date('Y-m-d H:i:s', strtotime("-0 month"));

                                                $inicio = date('Y-m-d H:i:s', strtotime("-1 month"));
                                                echo date_format(date_create($inicio), 'd/m/Y');
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                echo date_format(date_create($fim), 'd/m/Y');
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                $sql = mysqli_query($conect, "SELECT peca.idpeca FROM peca JOIN(lancamento_has_peca, lancamento, usuario) ON(peca.idpeca=lancamento_has_peca.idpeca AND lancamento_has_peca.idlancamento=lancamento.idlancamento AND usuario.usuario=lancamento.usuario) WHERE usuario.sexo='m' AND data_recebimento>='$inicio' AND data_recebimento<='$fim';");
                                                $geral = mysqli_num_rows($sql);
                                                echo $geral;
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                $sql = mysqli_query($conect, "SELECT ocorrencia.idocorrencia FROM peca JOIN(ocorrencia, tipo_ocorrencia, usuario) ON(peca.idpeca = ocorrencia.idpeca AND ocorrencia.idtipo_ocorrencia = tipo_ocorrencia.idtipo_ocorrencia AND usuario.usuario=peca.usuario) WHERE data_criacao>='$inicio' AND data_criacao<='$fim' AND usuario.sexo='m'");
                                                $geral = mysqli_num_rows($sql);
                                                echo $geral;
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <?php
                                                unset($sql);
                                                unset($inicio);
                                                unset($fim);
                                                $fim = strtotime("-1 month -1 days");

                                                $inicio = strtotime("-2 month -1 days");
                                                echo date('d/m/Y', $inicio);
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                echo date('d/m/Y', $fim);
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                $sql = mysqli_query($conect, "SELECT peca.idpeca FROM peca JOIN(lancamento_has_peca, lancamento, usuario) ON(peca.idpeca=lancamento_has_peca.idpeca AND lancamento_has_peca.idlancamento=lancamento.idlancamento AND usuario.usuario=lancamento.usuario) WHERE usuario.sexo='m' AND data_recebimento>='$inicio' AND data_recebimento<='$fim';");
                                                $geral = mysqli_num_rows($sql);
                                                echo $geral;
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                $sql = mysqli_query($conect, "SELECT ocorrencia.idocorrencia FROM peca JOIN(ocorrencia, tipo_ocorrencia, usuario) ON(peca.idpeca = ocorrencia.idpeca AND ocorrencia.idtipo_ocorrencia = tipo_ocorrencia.idtipo_ocorrencia AND usuario.usuario=peca.usuario) WHERE data_criacao>='$inicio' AND data_criacao<='$fim' AND usuario.sexo='m'");
                                                $geral = mysqli_num_rows($sql);
                                                echo $geral;
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <?php
                                                unset($sql);
                                                unset($inicio);
                                                unset($fim);
                                                $fim = strtotime("-2 month -2 days");

                                                $inicio = strtotime("-3 month -2 days");
                                                echo date('d/m/Y', $inicio);
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                echo date('d/m/Y', $fim);
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                $sql = mysqli_query($conect, "SELECT peca.idpeca FROM peca JOIN(lancamento_has_peca, lancamento, usuario) ON(peca.idpeca=lancamento_has_peca.idpeca AND lancamento_has_peca.idlancamento=lancamento.idlancamento AND usuario.usuario=lancamento.usuario) WHERE usuario.sexo='m' AND data_recebimento>='$inicio' AND data_recebimento<='$fim';");
                                                $geral = mysqli_num_rows($sql);
                                                echo $geral;
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                $sql = mysqli_query($conect, "SELECT ocorrencia.idocorrencia FROM peca JOIN(ocorrencia, tipo_ocorrencia, usuario) ON(peca.idpeca = ocorrencia.idpeca AND ocorrencia.idtipo_ocorrencia = tipo_ocorrencia.idtipo_ocorrencia AND usuario.usuario=peca.usuario) WHERE data_criacao>='$inicio' AND data_criacao<='$fim' AND usuario.sexo='m'");
                                                $geral = mysqli_num_rows($sql);
                                                echo $geral;
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <?php
                                                unset($sql);
                                                unset($inicio);
                                                unset($fim);
                                                $fim = strtotime("-3 month -3 days");

                                                $inicio = strtotime("-4 month -3 days");
                                                echo date('d/m/Y', $inicio);
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                echo date('d/m/Y', $fim);
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                $sql = mysqli_query($conect, "SELECT peca.idpeca FROM peca JOIN(lancamento_has_peca, lancamento, usuario) ON(peca.idpeca=lancamento_has_peca.idpeca AND lancamento_has_peca.idlancamento=lancamento.idlancamento AND usuario.usuario=lancamento.usuario) WHERE usuario.sexo='m' AND data_recebimento>='$inicio' AND data_recebimento<='$fim';");
                                                $geral = mysqli_num_rows($sql);
                                                echo $geral;
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                $sql = mysqli_query($conect, "SELECT ocorrencia.idocorrencia FROM peca JOIN(ocorrencia, tipo_ocorrencia, usuario) ON(peca.idpeca = ocorrencia.idpeca AND ocorrencia.idtipo_ocorrencia = tipo_ocorrencia.idtipo_ocorrencia AND usuario.usuario=peca.usuario) WHERE data_criacao>='$inicio' AND data_criacao<='$fim' AND usuario.sexo='m'");
                                                $geral = mysqli_num_rows($sql);
                                                echo $geral;
                                                ?>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>  
            </div>
        </div>
    </div>
</div>