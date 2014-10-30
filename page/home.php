<?php
$menu_page_active = "home";
?>
<div class="container-fluid">
    <div class="row-fluid">
        <div class="col-lg-12">
            <?php
            $lancamentoativo_usuario = mysqli_query($conect, "SELECT * FROM lancamento WHERE usuario='$usuario_logado' AND !ISNULL(data_recebimento) AND ISNULL(data_devolucao);");
            if (mysqli_num_rows($lancamentoativo_usuario) == 1) {
                ?>
                <h2>Lançamento Ativo <small>Visualizar peças que estão na lavanderia.</small></h2>
                <hr>
                <div class="row-fluid">
                    <div class="col-lg-12">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Descrição</th>
                                        <th>Tipo</th>
                                        <th>Marca</th>
                                        <th>Cor</th>
                                        <th>Tamanho</th>
                                    </tr>
                                </thead>
                                <tbody id="cs-dataGrid">
                                </tbody>
                            </table>
                            <div class="pull-right cs-legenda"></div>
                            <div class="col-lg-12 text-center" id="cs-pagination-content"></div>
                        </div>
                    </div>
                </div>
                <?php
                echo '<script>listar("lancamentoativo",1,15);</script>';
            }
            ?>
        </div><!-- /.col-lg-12 -->
    </div><!-- /.row-fluid -->
</div><!-- /.container-fluid -->