<?php

header('Content-type: text/html; charset=UTF-8');
include_once '../conexao/conexao.php';
$tipo = 0;
$marca = array("Tommy", "Nike", "CK", "Polo", "Adidas", "Colcci", "D&G", "Diesel", "Hollister", "Puma", "Lacoste");
$cor = array("Azul", "Branco", "Preto", "Vermelho", "Marrom", "Rosa", "Verde", "Roxo", "Laranja", "Amarelo");
$tamanho = array("PP", "P", "M", "G", "M", "GG");
$sql_user = mysqli_query($conect, "SELECT usuario FROM acqua_db.usuario WHERE permissao='3'");
while ($row = mysqli_fetch_array($sql_user)) {
    $usuario = $row['usuario'];
    for ($i = 0; $i < 25; $i++) {
        if ($tipo == 5) {
            $tipo = 1;
        } else {
            $tipo++;
        }
        $rand1 = rand(1, 35000);
        $texto_sql = "INSERT INTO peca (descricao, marca, cor, tamanho, status, idtipo, usuario) VALUES ('" . utf8_decode("Descrição - ") . $rand1 . "', '" . $marca[rand(0, 10)] . "', '" . $cor[rand(0, 9)] . "', '" . $tamanho[rand(0, 5)] . "', '1', '" . rand(1, 12) . "', '$usuario');";
        $sql = mysqli_query($conect, $texto_sql);
    }
}
echo 'OK';
