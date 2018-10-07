<?php
    //File structure
    $webRoot = $_SERVER["DOCUMENT_ROOT"] . '/';
    $langFolder = 'lang';

    //Languages
    $defaultLanguage = 'en';
    $langCookieName = 'lang';

    //Constants
    $constants = [];
    $constants['env'] = 'development';
    $constants['prod_ver'] = 1;
    $constants['ver'] = $constants['env'] == 'production' ? $constants['prod_ver'] : rand(0, 9999);
?>