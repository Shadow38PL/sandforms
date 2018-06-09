<?php
    require_once 'conf.php';

    $url = $_GET['url'] ? $_GET['url'] : 'index';

    if (file_exists($webRoot . $url . '.php')) {
        require($webRoot . $url . '.php');
    } else if (file_exists($webRoot . 'pages/' . $url . '.html')) {
        require_once 'render.php';
        echo render('pages/'.$url);
    } else {
        require_once 'render.php';
        echo render('/pages/404');
    }
?>