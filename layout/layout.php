<?php
$caminho = "http://localhost/acqua/";
include_once 'conexao/conexao.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
        <?php include "view/php/titulo.php"; ?>
        <title>
            <?php echo $pageTitulo ?>
        </title>
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <link href="<?php echo $caminho ?>view/img/falvicon/icone.ico" rel="shortcut icon"/>
        <!-- jQuery JavaScript Library v1.10.2 -->
        <script src="<?php echo $caminho ?>view/js/jquery/jquery-latest.js"></script>
        <!-- Bootstrap v3.1.1 -->        
        <link href="<?php echo $caminho ?>view/css/bootstrap/bootstrap.css" rel="stylesheet" type="text/css">
        <link href="<?php echo $caminho ?>view/css/bootstrap/bootstrap-theme.css" rel="stylesheet" type="text/css">
        <script src="<?php echo $caminho ?>view/js/bootstrap/bootstrap.js" type="text/javascript"></script>
        <!-- Modernizr 2.8.1 (Custom Build)-->
        <script src="<?php echo $caminho ?>view/js/modernizr/modernizr.custom.05848.js" type="text/javascript"></script>
        <!-- JQuery multi-melect -->
        <script src="<?php echo $caminho ?>view/js/jquery/jquery.multi-select.js" type="text/javascript"></script>
        <link href="<?php echo $caminho ?>view/css/multi-select.css" rel="stylesheet" type="text/css">
        <!-- Ionicons, v1.4.1 -->
        <link href="<?php echo $caminho ?>view/css/ion-icon/ionicons.min.css" rel="stylesheet" type="text/css">
        <!-- Docs -->
        <script src="<?php echo $caminho ?>view/js/action.js" type="text/javascript"></script>
        <script src="<?php echo $caminho ?>view/js/script.js" type="text/javascript"></script>
        <link href="<?php echo $caminho ?>view/css/style.css" rel="stylesheet" type="text/css">
    </head>
    <body>
        <div class="container-fluid cs-bg-blue">
            <div class="row-fluid cs-top">
                <div class="col-lg-6 pull-right">
                    <img class="cs-top-unasp pull-right" src="<?php echo $caminho ?>view/img/unasp-top.png"/>
                </div>
                <div class="col-lg-6 pull-left">
                    <img class="pull-left  cs-top-logo" src="<?php echo $caminho ?>view/img/logo.png"/>
                </div>
            </div>
        </div>
        <div class="navbar cs-navbar navbar-static-top" role="navigation">
            <nav>
                <div class="container-fluid">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                    </div>
                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                        <ul class="nav navbar-nav">
                            <li id="home" class=""><a id="button" href="<?php echo $caminho ?>home">Home</a></li>
                            <?php if ($_SESSION['usuario']['permissao'] == '3') { ?>
                                <li id="peca" class="dropdown">
                                    <a href="" class="dropdown-toggle" data-toggle="dropdown">Peça <span class="caret"></a>
                                    <ul class="dropdown-menu" role="menu">
                                        <li><a href="<?php echo $caminho ?>peca">Gerenciar Peça</a></li>
                                        <li class="divider"></li>
                                        <li><a href="<?php echo $caminho ?>ocorrencia">Ocorrência</a></li>
                                    </ul>
                                </li>
                                <li id="lancamento" class="dropdown">
                                    <a href="" class="dropdown-toggle" data-toggle="dropdown">Lançamento <span class="caret"></a>
                                    <ul class="dropdown-menu" role="menu">
                                        <?php
                                        $lancamento_usuario = mysqli_query($conect, "SELECT * FROM lancamento WHERE usuario='$usuario_logado' AND !ISNULL(data_recebimento) AND ISNULL(data_devolucao);");
                                        if (mysqli_num_rows($lancamento_usuario) == 0) {
                                            ?>
                                            <li><a href="<?php echo $caminho ?>lancamento">Lançamento</a></li>
                                            <?php
                                        } else {
                                            ?>
                                            <li><a href="<?php echo $caminho ?>lancamentoativo">Lançamento Ativo</a></li>
                                            <?php
                                        }
                                        ?>
                                        <li class="divider"></li>
                                        <li><a href="<?php echo $caminho ?>lancamentospassados">Lançamentos passados</a></li>
                                    </ul>
                                </li>
                                <li id="graficos" class=""><a id="button" href="<?php echo $caminho ?>graficosaluno">Gráficos/Relatórios</a></li>
                                <?php
                            }
                            if ($_SESSION['usuario']['permissao'] == '0') {
                                ?>
                                <li id="ocorrencia" class="dropdown">
                                    <a href="" class="dropdown-toggle" data-toggle="dropdown">Ocorrência <span class="caret"></a>
                                    <ul class="dropdown-menu" role="menu">
                                        <li><a href="<?php echo $caminho ?>cadastrarocorrencia">Cadastrar Ocorrência</a></li>
                                        <li class="divider"></li>
                                        <li><a href="<?php echo $caminho ?>gerenciarocorrencia">Peças com Ocorrência</a></li>
                                        <li><a href="<?php echo $caminho ?>tipoocorrencia">Cadastrar Tipo de Ocorrência</a></li>
                                    </ul>
                                </li>
                                <li id="lancamento" class="dropdown">
                                    <a href="" class="dropdown-toggle" data-toggle="dropdown">Lançamento <span class="caret"></a>
                                    <ul class="dropdown-menu" role="menu">
                                        <li><a href="<?php echo $caminho ?>visualizarlancamento">Visualizar Lançamento</a></li>
                                        <li class="divider"></li>
                                        <li><a href="<?php echo $caminho ?>entradapeca">Entrada de Peça</a></li>
                                        <li class="divider"></li>
                                        <li><a href="<?php echo $caminho ?>saidapeca">Saída de Peça</a></li>
                                    </ul>
                                </li>
                                <li id="usuario" class="dropdown">
                                    <a href="" class="dropdown-toggle" data-toggle="dropdown">Usuário <span class="caret"></a>
                                    <ul class="dropdown-menu" role="menu">
                                        <li><a href="<?php echo $caminho ?>usuario">Gerenciar Usuário</a></li>
                                        <li class="divider"></li>
                                        <li><a href="<?php echo $caminho ?>numero">Gerenciar Número</a></li>
                                        <li><a href="<?php echo $caminho ?>atribuirnumero">Atribuir Número Automaticamente</a></li> 
                                    </ul>
                                </li>

                            <?php } ?>
                        </ul>
                        <ul class="nav navbar-top-links navbar-right">
                            <li class="dropdown">
                                <a class="dropdown-toggle" data-toggle="dropdown" href="#" title="Alterar senha">
                                    <i class="ion ion-key ion-size-large pull-right"></i></i>
                                </a>
                                <ul class="dropdown-menu dropdown-messages">
                                    <div class="panel panel-default">
                                        <div class="panel-heading text-center">
                                            Alterar Senha
                                        </div>
                                        <div class="panel-body cs-form">
                                            <div class="form-group">
                                                <label>Senha Atual</label>
                                                <input type="text" name="nome" class="form-control">
                                            </div>
                                            <div class="form-group cs-id-editar">
                                                <label>Nova Senha</label>
                                                <input type="text" name="usuario" class="form-control"><span></span>
                                            </div>
                                            <div class="form-group cs-id-editar">
                                                <label>Nova Senha</label>
                                                <input type="text" name="usuario" class="form-control"><span></span>
                                            </div>
                                            <button type="button" data-action="cadastrar_usuario" class="btn btn-success cs-salvar">Alterar</button>
                                            <button type="reset" data-action="limpar" class="btn btn-warning">Limpar</button>
                                        </div>
                                    </div>
                                </ul>
                                <!-- /.dropdown-messages -->
                            </li>
                            <!-- /.dropdown -->
                            <li>
                                <a class="cs-deslogar" href="#" title="Deslogar">
                                    <i class="ion ion-log-out ion-size-large pull-right"></i></i>
                                </a>
                            </li>
                            <!-- /.dropdown -->
                        </ul>
                    </div>
                </div>
            </nav>
        </div><!-- /.navbar -->
        <?php
//include da página correspondente
        if ($is_page_erro) {
            include_once "page/erro/" . $page . ".php";
        } else {
            include_once "page/" . $page . ".php";
            ?>
            <script>
                $(document).ready(function () {
                    $("#<?php echo $menu_page_active; ?>").addClass("active");
                });
            </script>
            <?php
        }
        ?>
        <div class="alert alert-success cs-alert" id="cs-alert-success">
            <button type="button" class="close close-success">&times;</button>
            <strong><i class="ion ion-checkmark-circled"></i> Sucesso!</strong> 
            <span></span>
        </div>
        <div class="alert alert-danger cs-alert" id="cs-alert-danger">
            <button type="button" class="close close-danger">&times;</button>
            <strong><i class="ion ion-close-circled"></i> Erro!</strong> 
            <span></span>
        </div>
        <div class="modal fade" id="cs-modal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true" title="Utilize a tecla ESC para sair.">&times;</button>
                        <h4 class="modal-title"></h4>
                    </div>
                    <div class="modal-body"></div>
                    <div class="modal-footer"></div>
                </div>
            </div>
        </div>
    </body>
</html>