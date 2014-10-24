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
        <script src="view/js/ajax_action.js" type="text/javascript"></script>
    </head>
    <body class="bg-black"> 
        <div class="form-box" id="login-box"> 
            <div class="header text-center"><img src="view/img/logo.png" /> Gerenciador</div> 
            <form method="post" id="form_login"> 
                <div class="body bg-gray" >
                    <div class="form-group">
                        <input type="text" name="usuario" class="form-control" placeholder="Usuário" required>
                    </div>
                    <div class="form-group">
                        <input type="password" name="senha" class="form-control" placeholder="Senha" required>
                    </div>
                </div>
                <div class="footer">                                                               
                    <button type="button" class="btn bg-gray-strong btn-block cs-logar">Entrar</button>
                </div>
            </form>
        </div>
    </body>
</html>