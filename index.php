<?php

include_once 'view/php/paginas_permitidas.php';

if (session_id() == '') {
    session_start();
}
if (isset($_SESSION['usuario'])) {
    $usuario_logado = $_SESSION['usuario']['usuario'];
    if (isset($_GET['page'])) {

        $array_page = explode("/", $_GET['page']);

        $caminho_page = "page/" . $array_page[0] . ".php";
        $caminho_page_erro = "page/erro/" . $array_page[0] . ".php";
        if (file_exists($caminho_page)) {
            $page = $array_page[0];
            if (in_array($page, $paginas_permitidas[$_SESSION['usuario']['permissao']])) {
                $is_page_erro = false;
                switch ($page) {
                    case 'livro':
                        if (count($array_page) == 2) {
                            $livro_idioma = $array_page[1];
                        } else {
                            $livro_idioma = false;
                        }
                        break;
                    case 'capitulo':
                        if (count($array_page) == 3) {
                            $capitulo_idioma = $array_page[1];
                            $capitulo_livro = $array_page[2];
                        } else {
                            $capitulo_idioma = false;
                        }
                        break;

                    default:
                        break;
                }
            } else {
                $page = '403';
                $is_page_erro = true;
            }
        } elseif (file_exists($caminho_page_erro)) {
            $page = $array_page[0];
            $is_page_erro = true;
        } else {
            $page = '404';
            $is_page_erro = true;
        }
    } else {
        $page = 'home';
        $is_page_erro = false;
    }

    include 'layout/layout.php';
} else {
    include 'login.php';
}
?>