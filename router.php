<?php
    $url = $_GET['url'] ? $_GET['url'] : 'index';

    if (file_exists($url.'.php')) {
        require($url.'.php');
    } else if (file_exists('pages/'.$url.'.html')) {
        require_once 'render.php';

        echo render('pages/'.$url);
    } else {
        require_once 'render.php';

        echo render('pages/404');
    }
?>