<?php
    require_once 'libs/render/render.php';

    $articles = [
        ['title' => 'Darmowe kursy frontendu!', 'content' => 'Mocno pojebany Artur uczy nowychj pajaców za darmo! CO ZA OKAZJA!!!'],
        ['title' => 'Bartek pozwany o mobbing?!', 'content' => 'Jak podają najnowsze źródła Bartek znęca się psychicznie nad pracownikami za słabą znajomość JavaScript\'a']
    ];

    $banner = ['title' => localize('main_page'), 'subtitle' => localize('cool_page')];

    echo render('pages/index', ['banner' => $banner, 'articles' => $articles, 'ad' => true], true);
?>