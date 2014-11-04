<?php
$menu_page_active = "home";
?>
<div class="container-fluid">
    <div class="row-fluid">
        <div class="col-lg-12">
            <?php
            if ($_SESSION['usuario']['permissao'] == "3") {
                $lancamentoativo_usuario = mysqli_query($conect, "SELECT * FROM lancamento WHERE usuario='$usuario_logado' AND !ISNULL(data_recebimento) AND ISNULL(data_devolucao);");
                if (mysqli_num_rows($lancamentoativo_usuario) == 1) {
                    echo '<script>location.href = caminho+"lancamentoativo";</script>';
                } else {
                    echo '<script>location.href = caminho+"lancamento";</script>';
                }
            } elseif ($_SESSION['usuario']['permissao'] == "2") {
                
            } elseif ($_SESSION['usuario']['permissao'] == "1") {
                
            }
            ?>
        </div><!-- /.col-lg-12 -->
    </div><!-- /.row-fluid -->
</div><!-- /.container-fluid -->