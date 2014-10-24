<?php

$sufixoTitulo = " | Acqua - Sistema de Lavanderia";
if ($is_page_erro) {
    switch ($page) {
        case "403":
            $pageTitulo = "Permissão Negada" . $sufixoTitulo;
            break;
        case "404":
            $pageTitulo = "Página Não Encontrada" . $sufixoTitulo;
            break;
        case "405":
            $pageTitulo = "Método Não Permitido" . $sufixoTitulo;
            break;
        case "500":
            $pageTitulo = "Erro Interno de Servidor" . $sufixoTitulo;
            break;
        default:
            $pageTitulo = "Acqua - Sistema de Lavanderia";
            break;
    }
} else {
    switch ($page) {
        case "home":
            $pageTitulo = "Home" . $sufixoTitulo;
            break;
        case "peca":
            $pageTitulo = "Peça" . $sufixoTitulo;
            break;
        case "livro":
            $pageTitulo = "Livro" . $sufixoTitulo;
            break;
        case "capitulo":
            $pageTitulo = "Capítulo" . $sufixoTitulo;
            break;
        default:
            $pageTitulo = "Acqua - Sistema de Lavanderia";
            break;
    }
}
?>