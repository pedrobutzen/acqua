<?php
$menu_page_active = "lancamento";
?>
<div class="container-fluid">
    <div class="row-fluid">
        <h2>Lançamento <small>Marcar peças para enviar à lavanderia</small></h2>
        <hr>
        <div class="col-lg-12">
            <?php
            $lancamentoativo_usuario = mysqli_query($conect, "SELECT * FROM lancamento WHERE usuario='$usuario_logado' AND !ISNULL(data_recebimento) AND ISNULL(data_devolucao);");
            if (mysqli_num_rows($lancamentoativo_usuario) == 0) {
                $bloqueio_usuario = mysqli_query($conect, "SELECT * FROM usuario as u JOIN(bloqueio as b) ON(b.usuario = u.usuario) WHERE ISNULL(b.data_fim) AND u.usuario='$usuario_logado';");
                if (mysqli_num_rows($bloqueio_usuario) == "0") {
                    $lancamento_usuario = mysqli_query($conect, "SELECT * FROM lancamento WHERE usuario='$usuario_logado' AND ISNULL(data_recebimento);");
                    if (mysqli_num_rows($lancamento_usuario) == 0) {
                        ?>
                        <select id='pecas-lancamento' multiple='multiple' >
                            <?php
                            $qtd_tipo = mysqli_query($conect, "SELECT tipo.nome, tipo.idtipo FROM peca LEFT JOIN(tipo) ON(peca.idtipo = tipo.idtipo) WHERE peca.usuario='$usuario_logado' AND peca.status='1' GROUP BY tipo.idtipo ORDER BY tipo.nome, peca.descricao");
                            while ($row_tipo = mysqli_fetch_array($qtd_tipo)) {
                                $nome_tipo = $row_tipo['nome'];
                                $id_tipo = $row_tipo['idtipo'];
                                echo "<optgroup label='" . utf8_encode($nome_tipo) . "'>";
                                $qtd_geral = mysqli_query($conect, "SELECT peca.idpeca, peca.descricao, peca.marca, peca.cor, peca.tamanho, tipo.nome FROM peca LEFT JOIN(tipo) ON(peca.idtipo = tipo.idtipo) WHERE peca.usuario='$usuario_logado' AND peca.status='1' AND tipo.idtipo='$id_tipo' GROUP BY peca.idpeca ORDER BY peca.descricao");
                                while ($row = mysqli_fetch_array($qtd_geral)) {
                                    echo "<option value='" . utf8_encode($row["idpeca"]) . "' title='Clique'>" . utf8_encode($row["descricao"]) . ", " . utf8_encode($row["marca"]) . ", " . utf8_encode($row["cor"]) . ", " . utf8_encode($row["tamanho"]) . ", " . utf8_encode($row["nome"]) . "</option>";
                                }
                                echo '</optgroup>';
                            }
                            ?>
                        </select>
                        <button type="button" id="add-todos" class="btn btn-primary">Marcar Todas</button>
                        <button type="button" id="rm-todos" class="btn btn-default">Desmarcar Todas</button>
                        <button type="button" data-action="cadastrar_lancamento" class="btn btn-primary cs-salvar" style="float: right;">Enviar</button>
                        <?php
                    } elseif (mysqli_num_rows($lancamento_usuario) == 1) {
                        $row = mysqli_fetch_array($lancamento_usuario);
                        $id_lancamento = $row['idlancamento'];
                        ?>
                        <select id='pecas-lancamento' multiple='multiple' >
                            <?php
                            $qtd_tipo = mysqli_query($conect, "SELECT tipo.nome, tipo.idtipo FROM peca LEFT JOIN(tipo) ON(peca.idtipo = tipo.idtipo) WHERE peca.usuario='$usuario_logado' AND peca.status='1' GROUP BY tipo.idtipo ORDER BY tipo.nome, peca.descricao");
                            while ($row_tipo = mysqli_fetch_array($qtd_tipo)) {
                                $nome_tipo = $row_tipo['nome'];
                                $id_tipo = $row_tipo['idtipo'];
                                echo "<optgroup label='" . utf8_encode($nome_tipo) . "'>";
                                $qtd_geral_selecionados = mysqli_query($conect, "SELECT p.idpeca, p.descricao, p.marca, p.cor, p.tamanho, t.nome FROM peca as p JOIN(lancamento_has_peca as lp, lancamento as l, tipo as t) ON(p.idpeca=lp.idpeca AND l.idlancamento=lp.idlancamento AND p.idtipo=t.idtipo) WHERE p.usuario='$usuario_logado' AND ISNULL(l.data_recebimento) AND t.idtipo='$id_tipo' ORDER BY t.nome, p.descricao");
                                while ($row = mysqli_fetch_array($qtd_geral_selecionados)) {
                                    $ids_selecionados[] = $row["idpeca"];
                                    echo "<option value='" . utf8_encode($row["idpeca"]) . "' title='Clique' selected>" . utf8_encode($row["descricao"]) . ", " . utf8_encode($row["marca"]) . ", " . utf8_encode($row["cor"]) . ", " . utf8_encode($row["tamanho"]) . ", " . utf8_encode($row["nome"]) . "</option>";
                                }
                                $qtd_geral = mysqli_query($conect, "SELECT peca.idpeca, peca.descricao, peca.marca, peca.cor, peca.tamanho, tipo.nome FROM peca LEFT JOIN(tipo) ON(peca.idtipo = tipo.idtipo) WHERE peca.usuario='$usuario_logado' AND peca.status='1' AND tipo.idtipo='$id_tipo' GROUP BY peca.idpeca ORDER BY tipo.nome, peca.descricao");
                                while ($row = mysqli_fetch_array($qtd_geral)) {
                                    if (!in_array($row['idpeca'], $ids_selecionados)) {
                                        echo "<option value='" . utf8_encode($row["idpeca"]) . "' title='Clique'>" . utf8_encode($row["descricao"]) . ", " . utf8_encode($row["marca"]) . ", " . utf8_encode($row["cor"]) . ", " . utf8_encode($row["tamanho"]) . ", " . utf8_encode($row["nome"]) . "</option>";
                                    }
                                }
                                echo '</optgroup>';
                            }
                            ?>
                        </select>
                        <button type="button" id="add-todos" class="btn btn-primary">Marcar Todas</button>
                        <button type="button" id="rm-todos" class="btn btn-default">Desmarcar Todas</button>
                        <button type="button" id="rm-lancamento" data-id="<?php echo $id_lancamento; ?>" class="btn btn-primary">Excluir Lançamento</button>
                        <button type="button" data-action="cadastrar_lancamento" class="btn btn-primary cs-salvar" style="float: right;">Enviar</button>
                        <?php
                    }
                } else {
                    ?>
                    <div class="well well-lg">Usuário não pode enviar peças para lavanderia pois está bloqueado.</div>
                    <?php
                }
            } else {
                echo '<script>location.href = caminho+"lancamentoativo";</script>';
            }
            ?>
        </div>
    </div>
</div>