<?php
    require_once 'engine/render.php';

    $articles = [
        ['title' => 'Tytuł 1', 'content' => 'Treść 1'],
        ['title' => 'Tytuł 2', 'content' => 'Treść 2']
    ];

    $articles = render('templates/article', $articles);

    echo render('pages/index', ['articles' => $articles]);
?>