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
        case "atribuirnumero":
            $pageTitulo = "Atribuir Número" . $sufixoTitulo;
            break;
        case "gerenciarocorrencia":
            $pageTitulo = "Peça com Ocorrência" . $sufixoTitulo;
            break;
        case "entradapeca":
            $pageTitulo = "Entrada Peça" . $sufixoTitulo;
            break;
        case "lancamento":
            $pageTitulo = "Lançamento" . $sufixoTitulo;
            break;
        case "lancamentoativo":
            $pageTitulo = "Lançamento Ativo" . $sufixoTitulo;
            break;
        case "lancamentospassados":
            $pageTitulo = "Lanamentos Passados" . $sufixoTitulo;
            break;
        case "numero":
            $pageTitulo = "Número" . $sufixoTitulo;
            break;
        case "ocorrencia":
            $pageTitulo = "Ocorrência" . $sufixoTitulo;
            break;
        case "peca":
            $pageTitulo = "Peça" . $sufixoTitulo;
            break;
        case "saidapeca":
            $pageTitulo = "Saída Peça" . $sufixoTitulo;
            break;
        case "usuario":
            $pageTitulo = "Usuário" . $sufixoTitulo;
            break;
        default:
            $pageTitulo = "Acqua - Sistema de Lavanderia";
            break;
    }
}
?>