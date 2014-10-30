<?php
$menu_page_active = "usuario";
?>
<div class="container-fluid">
    <div class="row-fluid">
        <div class="col-lg-12">
            <h2>Gerenciar número de identificação <small>Cadastrar e excluir números</small></h2>
            <hr>
            <div class="row-fluid">
                <form role="form" class="col-lg-3 cs-p-botton25">
                    <div class="panel panel-default">
                        <div class="panel-heading text-center">
                            Cadastrar Número
                        </div>
                        <div class="panel-body cs-form">
                            <div class="form-group">
                                <label>Número</label>
                                <input type="text" name="num_numero" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Sexo</label>
                                <select class="form-control" name="num_sexo">
                                    <option value="">Selecione</option>
                                    <option value="f">Feminino</option>
                                    <option value="m">Masculino</option>
                                </select>
                            </div>
                            <button type="button" data-action="cadastrar_numero" class="btn btn-primary cs-salvar">Salvar</button>
                            <button type="reset" data-action="limpar" class="btn btn-default">Limpar</button>
                        </div>
                    </div>
                </form>
                <div class="col-lg-9">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="active cs-li-feminino"><a href="javascript:;" id="cs-dataGrid-num-f">Feminino</a></li>
                        <li class="cs-li-masculino"><a href="javascript:;" id="cs-dataGrid-num-m">Masculino</a></li>
                        <div class="input-group cs-search pull-right">
                            <input type="text" class="form-control" placeholder="Pesquisar">
                            <span class="input-group-btn">
                                <button class="btn btn-default" type="button">
                                    <i class="ion ion-search"></i>
                                </button>
                            </span>
                        </div>
                    </ul>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead id="cs-thead-dataGrid">
                                <tr>
                                    <th>Número</th>
                                    <th>Usuário(Aluno)</th>
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
        </div>
    </div>
</div>