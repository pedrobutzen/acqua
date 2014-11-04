<?php
$menu_page_active = "usuario";
?>
<div class="container-fluid">
    <div class="row-fluid">
        <div class="col-lg-12">
            <h2>Gerenciar Usuário <small>Cadastrar, editar e excluir usuários</small></h2>
            <hr>
            <div class="row-fluid">
                <form role="form" class="col-lg-3 cs-p-botton25">
                    <div class="panel panel-default">
                        <div class="panel-heading text-center">
                            <span id="cs-action">Cadastrar</span> Funcionário
                        </div>
                        <div class="panel-body cs-form">
                            <div class="cs-id-editar"></div>
                            <div class="form-group">
                                <label>Nome*</label>
                                <input type="text" name="usuario_nome" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Usuário*</label>
                                <input type="text" name="usuario_usuario" class="form-control">
                            </div>
                            <div id="senhas">
                                 <div class="form-group">
                                    <label>Senha*</label>
                                    <input type="password" name="senha" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>Repetir Senha*</label>
                                    <input type="password" name="repetirsenha" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Email*</label>
                                <input type="text" name="email" class="form-control">
                            </div>
                            <div class="form-group col-lg-6">
                                <label>Sexo*</label>
                                <select class="form-control" name="usuario_sexo">
                                    <option value="">Selecione</option>
                                    <option value="f">Feminino</option>
                                    <option value="m">Masculino</option>
                                </select>
                            </div>
                            <div class="form-group col-lg-6">
                                <label>Telefone/Contato</label>
                                <input type="text" name="usuario_telefone" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Permissão*</label><label class="pull-right"></label>
                                <select class="form-control" name="usuario_permissao">
                                    <option value="">Selecione</option>
                                    <option value="1">Total</option>
                                    <option value="2">Visualização Relatórios/Gráficos</option>
                                </select>
                            </div>

                            <button type="button" data-action="editar_usuario" class="btn btn-primary cs-editar">Editar</button>
                            <button type="button" data-action="cadastrar_usuario" class="btn btn-primary cs-salvar">Salvar</button>
                            <button type="reset" data-action="limpar" class="btn btn-default">Limpar</button>
                            <button type="button" data-pagina="usuario" class="btn btn-default cs-cancelar">Cancelar</button>
                        </div>
                    </div>
                </form>
                <div class="col-lg-9">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="active cs-li-funcionario"><a href="javascript:;" id="cs-dataGrid-funcionario">Funcionário</a></li>
                        <li class="cs-li-aluno"><a href="javascript:;" id="cs-dataGrid-aluno">Aluno</a></li>
                        <div class="input-group cs-search pull-right">
                            <input type="text" class="form-control" name="pesquisa" placeholder="Pesquisar">
                            <span class="input-group-btn">
                                <button class="btn btn-default cs-btn-pesquisar-usuario" type="button">
                                    <i class="ion ion-search"></i>
                                </button>
                            </span>
                        </div>
                    </ul>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead id="cs-thead-dataGrid">

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