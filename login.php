<!DOCTYPE html>
<html class="bg-black"><head>
        <meta charset="UTF-8">
        <title>Login | Acqua - Lavanderia UNASP-EC</title>
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <link href="view/img/falvicon/icone.ico" rel="shortcut icon"/>
        <!-- Jquery -->
        <script src="view/js/jquery/jquery-latest.js"></script>
        <!-- Bootstrap v3.1.1 -->        
        <link href="view/css/bootstrap/bootstrap.min.css" rel="stylesheet" type="text/css">
        <link href="view/css/bootstrap/bootstrap-theme.min.css" rel="stylesheet" type="text/css">
        <script src="view/js/bootstrap/bootstrap.min.js" type="text/javascript"></script>
        <!-- Modernizr 2.8.1 (Custom Build)-->
        <script src="view/js/modernizr/modernizr.custom.05848.js" type="text/javascript"></script>
        <!-- Docs -->
        <link href="view/css/login_style.css" rel="stylesheet" type="text/css">
        <script src="view/js/script.js" type="text/javascript"></script>
        <script src="view/js/action.js" type="text/javascript"></script>
    </head>
    <body class="bg-blue"> 
        <div class="text-center"><img src="view/img/unasp-top.png" /></div> 
        <div class="container">
            <div class="row">
                <div class="text-center col-lg-8"><img src="view/img/logo.png" /></div> 
                <div class="form-box col-lg-4" id="login-box"> 
                    <div class="form-group">
                        <input type="text" name="usuario" class="form-control" placeholder="Usuário">
                    </div>
                    <div class="form-group">
                        <input type="password" name="senha" class="form-control" placeholder="Senha">
                    </div>
                    <button type="button" class="btn btn-block btn-outline cs-logar btn-danger">Entrar</button>
                    <a href="esqueciasenha" class="pull-right">Esqueci a senha</a>
                </div>
            </div>
        </div>
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
    </body>
</html>