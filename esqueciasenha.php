<?php

header('Content-type: text/html; charset=UTF-8');
include_once 'conexao/conexao.php';

$usuario = '10090';

$resultados = mysqli_query($conect, "SELECT email FROM usuario WHERE usuario='$usuario'");
if (mysqli_num_rows($resultados) > 0) {
    while ($row = mysqli_fetch_array($resultados)) {
        $email = $row['email'];

        $senha_gerada = rand(1000000, 9999999);
        mysqli_query($conect, "UPDATE usuario SET senha='$senha_gerada' WHERE usuario='$usuario'");

        $to = '' . $email;
        $subject = 'Esqueci a senha';
        $message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Lembrete Acqua</title>
  <style type="text/css">
  body {
   padding-top: 0 !important;
   padding-bottom: 0 !important;
   padding-top: 0 !important;
   padding-bottom: 0 !important;
   margin:0 !important;
   width: 100% !important;
   -webkit-text-size-adjust: 100% !important;
   -ms-text-size-adjust: 100% !important;
   -webkit-font-smoothing: antialiased !important;
 }
 .table{
    font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
    font-size: 14px;
    line-height: 1.42857143;
    color: #333;    
    width: 100%;
    margin-bottom: 20px;
 }
 table{
    max-width: 100%;
    background-color: transparent;
    border-spacing: 0;
    border-collapse: collapse;
 }
 .table-striped > tbody > tr:nth-child(odd) > td, .table-striped > tbody > tr:nth-child(odd) > th {
   background-color: #f9f9f9;
 }
 a{
  color:#382F2E;
}

p, h1,h2,ul,ol,li,div{
  margin:0;
  padding:0;
}

h1,h2{
  font-weight: normal;
  background:transparent !important;
  border:none !important;
}
table theaad td, table theaad th{
    padding: 8px;
    line-height: 1.42857143;
    
    border-top: 1px solid #ddd;
    text-align: left;  
}
td.middle{
  vertical-align: middle;
}
a.link1{
  font-size:13px;
  color:#27A1E5;
  line-height: 24px;
  text-decoration:none;
}
a{
  text-decoration: none;
}
h2,h1{
line-height: 20px;
}
p{
  font-size: 14px;
  line-height: 21px;
  color:#AAAAAA;
}
.bgItem{
background: #ffffff;
}
.bgBody{
background: #ffffff;
}
</style>
  
</head>
<body paddingwidth="0" paddingheight="0" bgcolor="#d1d3d4"  style="padding-top: 0; padding-bottom: 0; padding-top: 0; padding-bottom: 0; background-repeat: repeat; width: 100% !important; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; -webkit-font-smoothing: antialiased;" offset="0" toppadding="0" leftpadding="0">
  <table width="100%" border="0" cellspacing="0" cellpadding="0" class="tableContent bgBody" align="center"  style="font-family:Helvetica, sans-serif;">
        <tr>
          <td align="center">
            <table width="600" border="0" cellspacing="0" cellpadding="0" align="center" >
            <tr>
            <td class="bgItem" align="center">
            <table width="580" border="0" cellspacing="0" cellpadding="0" align="center">
              <tr>
                <td class="movableContentContainer" align="center">

                  <div class="movableContent">
                    <table width="580" border="0" cellspacing="0" cellpadding="0" align="center">
                      <tr><td height="15"></td></tr>
                      <tr>
                        <td>
                          <table width="580" border="0" cellspacing="0" cellpadding="0" align="center">
                            <tr>
                              <td>
                                <div>
                                  <div class="contentEditable">
                                      <a href="http://pedrobutzen.uni.me/acqua/" target="_blank"><img src="http://pedrobutzen.uni.me/acqua/action/email/images/logo.png" alt="Logo" height="60"/></a>
                                  </div>
                                </div>
                              </td>
                              <td valign="middle" style="vertical-align: middle;" width="150">
                                <div class="">
                                  <div class="contentEditable" style="text-align: right;">
                                    <a href="http://pedrobutzen.uni.me/acqua/" target="_blank" class="link1" >Entrar</a>
                                  </div>
                                </div>
                              </td>
                            </tr>
                          </table>
                        </td>
                      </tr>
                    </table>  
                  </div>  
                  <div class="movableContent">
                    <table width="580" border="0" cellspacing="0" cellpadding="0" align="center">
                      <tr><td height="40"></td></tr>
                      <tr>
                        <td style="border: 1px solid #EEEEEE; border-radius:6px;-moz-border-radius:6px;-webkit-border-radius:6px">
                          <table width="480" border="0" cellspacing="0" cellpadding="0" align="center">
                            <tr><td height="25"></td></tr>
                            <tr>
                              <td>
                                <div class="">
                                  <div class="contentEditable" style="text-align: center;">
                                    <h2 style="font-size: 20px;">Esqueci a senha <br> Acqua - Lavanderia Unasp</h2>
                                    <br>
                                    <p>Para entrar no sistema utilize:
                                    <br>Usu&aacute;rio: <b>' . $usuario . '</b>
                                    <br>Senha: <b>' . $senha_gerada . '</b></p>
                                  </div>
                                </div>
                              </td>
                            </tr>
                            <tr><td height="24"></td></tr>
                          </table>
                        </td>
                      </tr>
                    </table>  
                  </div>
                  <div class="movableContent">
                    <table cellpadding="0" cellspacing="0" border="0" align="center" width="600" class="container">
                        <tr>
                            <td width="100%" colspan="2" style="padding-top:65px;">
                                <hr style="height:1px;border:none;color:#333;background-color:#ddd;" />
                            </td>
                        </tr>
                        <tr>
                            <td width="60%" height="70" valign="middle" style="padding-bottom:20px;">
                                <div class="contentEditableContainer contentTextEditable">
                                    <div class="contentEditable" align="left" >
                                        <span style="font-size:13px;color:#181818;font-family:Helvetica, Arial, sans-serif;line-height:200%;">N&atilde;o responder, email enviado automaticamente.</span>
                                        <br/>
                                        <span style="font-size:11px;color:#555;font-family:Helvetica, Arial, sans-serif;line-height:200%;font-style:italic;">ACQUA - Sistema de Lavanderia &copy 2014</span>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
                </td>
              </tr>
            </table>
            </td>
            </tr>
            </table>
          </td>
        </tr>
    </table>
</body>
</html>
';

        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

        $headers .= 'From: Acqua | Sistema de Lavanderia <esqueciasenha@acqua.com>' . "\r\n";

        mail($to, $subject, $message, $headers);
    }
}