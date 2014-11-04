<?php

header('Content-type: text/html; charset=UTF-8');
include_once '../../conexao/conexao.php';

$resultados = mysqli_query($conect, "SELECT l.idlancamento, u.usuario, u.email FROM lancamento as l JOIN(usuario as u) ON(u.usuario=l.usuario) WHERE ISNULL(l.data_devolucao) AND !ISNULL(l.data_recebimento) AND u.sexo='m'");
if (mysqli_num_rows($resultados) > 0) {
    while ($row = mysqli_fetch_array($resultados)) {
        $idlancamento = $row['idlancamento'];
        $email = $row['email'];

        $html_pecas = "";

        $resultados_pecas = mysqli_query($conect, "SELECT p.*, t.nome as nometipo FROM peca as p JOIN(lancamento_has_peca as lp, lancamento as l, tipo as t) ON(p.idpeca=lp.idpeca AND l.idlancamento=lp.idlancamento AND p.idtipo=t.idtipo) WHERE l.idlancamento='$idlancamento'");
        if (mysqli_num_rows($resultados_pecas) != 0) {
            while ($row_peca = mysqli_fetch_array($resultados_pecas)) {
                $html_pecas .= '<tr><td style="padding: 8px;line-height: 1.42857143;border-top: 1px solid #ddd;text-align: left;">' . $row_peca['descricao'] . '</td><td style="padding: 8px;line-height: 1.42857143;border-top: 1px solid #ddd;text-align: left;">' . $row_peca['nometipo'] . '</td><td style="padding: 8px;line-height: 1.42857143;border-top: 1px solid #ddd;text-align: left;">' . $row_peca['marca'] . '</td><td style="padding: 8px;line-height: 1.42857143;border-top: 1px solid #ddd;text-align: left;">' . $row_peca['cor'] . '</td><td style="padding: 8px;line-height: 1.42857143;border-top: 1px solid #ddd;text-align: left;">' . $row_peca['tamanho'] . '</td></tr>';
            }
        }

        $to = '' . $email;
        $subject = 'Lembrete';
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
                                    <h2 style="font-size: 20px;">Lembrete Acqua - Lavanderia Unasp</h2>
                                    <br>
                                    <p>Você possui peças na lavanderia, busque até 14:00 de hoje ou será bloqueado e não poderá levar peças por uma semana.</p>
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
                      <table width="580" cellpadding="0" cellspacing="0" align="center">
                        <tr><td height="40"></td></tr>
                        <tr>
                          <td>
                            <div class="table-responsive">
                               <table width="580" class="table table-striped">
                                   <thead>
                                       <tr>
                                           <th style="padding: 8px;line-height: 1.42857143;border-top: 1px solid #ddd;text-align: left;">Descrição</th>
                                           <th style="padding: 8px;line-height: 1.42857143;border-top: 1px solid #ddd;text-align: left;">Tipo</th>
                                           <th style="padding: 8px;line-height: 1.42857143;border-top: 1px solid #ddd;text-align: left;">Marca</th>
                                           <th style="padding: 8px;line-height: 1.42857143;border-top: 1px solid #ddd;text-align: left;">Cor</th>
                                           <th style="padding: 8px;line-height: 1.42857143;border-top: 1px solid #ddd;text-align: left;">Tamanho</th>
                                       </tr>
                                   </thead>
                                   <tbody id="cs-dataGrid">
                    ' . $html_pecas . '</table>
                            </div>
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
                                        <span style="font-size:13px;color:#181818;font-family:Helvetica, Arial, sans-serif;line-height:200%;">Não responder, email enviado automaticamente.</span>
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

        $headers .= 'From: Acqua | Sistema de Lavanderia <lembrete@acqua.com>' . "\r\n";

        mail($to, $subject, $message, $headers);
    }
}