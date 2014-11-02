<?php
$menu_page_active = "ocorrencia";
?>
<div class="container-fluid">
    <div class="row-fluid">
        <div class="col-lg-12">
            <h2>Gerenciar Tipo de Ocorrência <small>Cadastrar, editar e excluir tipo de ocorrência</small></h2>
            <hr>
            <div class="row-fluid">
                <form role="form" class="col-lg-3 cs-p-botton25">
                    <div class="panel panel-default">
                        <div class="panel-heading text-center">
                            <span id="cs-action">Cadastrar</span> Tipo de Ocorrência
                        </div>
                        <div class="panel-body cs-form">
                            <div class="cs-id-editar"></div>
                            <div class="form-group">
                                <label>Nome do Tipo</label>
                                <input type="text" name="tipo" class="form-control">
                            </div>
                            <button type="button" data-action="editar_tipoocorrencia" class="btn btn-primary cs-editar">Editar</button>
                            <button type="button" data-action="cadastrar_tipoocorrencia" class="btn btn-primary cs-salvar">Salvar</button>
                            <button type="button" data-action="limpar" class="btn btn-default cs-limpar">Limpar</button>
                            <button type="button" data-pagina="tipoocorrencia" class="btn btn-default cs-cancelar">Cancelar</button>
                        </div>
                    </div>
                </form>
                <div class="col-lg-9">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead id="cs-thead-dataGrid">
                                <tr>
                                    <th>Tipo de Ocorrência</th>
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
        </div>
    </div>
</div>