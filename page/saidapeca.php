<?php
$menu_page_active = "lancamento";
?>
<div class="container-fluid">
    <div class="row-fluid">
        <div class="col-lg-12">
            <h2>Saída de Peça <small>Receber, editar e excluir peças do lançamento</small></h2>
            <hr>
            <div class="row-fluid">
                <div class="col-lg-3 cs-p-botton25">
                    <span id="cs-div-pesquisa">
                        <div class="panel panel-default">
                            <div class="panel-heading text-center">
                                Pesquisar Aluno
                            </div>
                            <div class="panel-body" id="cs-form">
                                <div class="form-group">
                                    <label>RA</label>
                                    <input name="ra" type="text" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>Número</label>
                                    <input name="numero" type="text" class="form-control">
                                </div>
                                <button type="button" data-pagina="usuariosaidapeca" class="btn btn-primary cs-pesquisar">Pesquisar</button>
                                <button type="button" data-action="limpar" class="btn btn-default cs-limpar">Limpar</button>
                            </div>

                        </div>
                    </span>
                </div>
                <div class="col-lg-9">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Descrição</th>
                                    <th>Tipo</th>
                                    <th>Marca</th>
                                    <th>Cor</th>
                                    <th>Tamanho</th>
                                </tr>
                            </thead>
                            <tbody id="cs-dataGrid" class="cs-with-modal">
                            </tbody>
                        </table>
                        <div class="pull-right cs-legenda"></div>
                        <div class="col-lg-12 text-center" id="cs-pagination-content"></div>
                    </div>
                    <span style="float: right;" id="botao-rigth"></span>
                </div>
            </div>
        </div><!-- /.col-lg-12 -->
    </div><!-- /.row-fluid -->
</div><!-- /.container-fluid -->