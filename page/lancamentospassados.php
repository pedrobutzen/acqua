<?php
$menu_page_active = "lancamento";
?>
<div class="container-fluid">
    <div class="row-fluid">
        <div class="col-lg-12">
            <h2>Lançamentos Passados <small>Visualizar lançamentos antigos.</small></h2>
            <hr>
            <div class="row-fluid">
                <div class="col-lg-12">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Data Criação</th>
                                    <th>Data Recebimento</th>
                                    <th>Usuário que Recebeu</th>
                                    <th>Data Devolução</th>
                                    <th>Usuário que Devolveu</th>
                                </tr>
                            </thead>
                            <tbody id="cs-dataGrid" class="cs-with-modal">
                            </tbody>
                        </table>
                        <div class="pull-right cs-legenda"></div>
                        <div class="col-lg-12 text-center" id="cs-pagination-content"></div>
                    </div>
                </div>
            </div>
        </div><!-- /.col-lg-12 -->
    </div><!-- /.row-fluid -->
</div><!-- /.container-fluid -->