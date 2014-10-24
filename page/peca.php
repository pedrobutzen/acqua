<?php
$menu_page_active = "peca";
?>
<div class="container-fluid">
    <div class="row-fluid">
        <div class="col-lg-12">
            <h2>Peça <small>Cadastrar, editar e excluir peças</small></h2>
            <hr>
            <div class="row-fluid">
                <div class="col-lg-3 cs-p-botton25">
                    <div class="panel panel-default">
                        <div class="panel-heading text-center">
                            <span id="cs-action">Cadastrar</span> Peça
                        </div>
                        <div class="panel-body" id="cs-form">
                            <input name="id_peca" type="text" class="cs-id-form">
                            <div class="form-group">
                                <label>Descrição*</label>
                                <input name="descricao" type="text" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Marca*</label>
                                <input name="marca" type="text" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Cor*</label>
                                <input name="cor" type="text" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Tamanho</label>
                                <input name="tamanho" type="text" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Tipo*</label>
                                <select class="form-control" name="tipo">
                                    <option value="">Selecione</option>
                                    <option value="1">Camisa</option>
                                    <option value="2">Camiseta</option>
                                    <option value="outro">Outro</option>
                                </select>
                            </div>
                            <div class="form-group cs-outro-form">
                                <label>Outro</label>
                                <input name="tipo_outro" type="text" class="form-control">
                            </div>
                            <button type="button" data-action="editar_peca" class="btn btn-primary cs-editar">Editar</button>
                            <button type="button" data-action="cadastrar_peca" class="btn btn-primary cs-salvar">Salvar</button>
                            <button type="button" data-action="limpar" class="btn btn-default cs-limpar">Limpar</button>
                            <button type="button" data-pagina="peca" class="btn btn-default cs-cancelar">Cancelar</button>
                        </div>
                    </div>
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
                                    <th>Status</th>
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