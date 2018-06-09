<?php
    $url = $_GET['url'] ? $_GET['url'] : 'index';

    if (file_exists($_SERVER["DOCUMENT_ROOT"].'/'.$url.'.php')) {
        require($_SERVER["DOCUMENT_ROOT"].'/'.$url.'.php');
    } else if (file_exists($_SERVER["DOCUMENT_ROOT"].'/pages/'.$url.'.html')) {
        require_once 'render.php';
        echo render('pages/'.$url);
    } else {
        require_once 'render.php';
        echo render('/pages/404');
    }
?>