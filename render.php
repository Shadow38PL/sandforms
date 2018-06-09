<?php
    $localizations = [
        'en' => [
            'hello_world' => 'Hello world!'
        ],
        'pl' =>  [
            'hello_world' => 'Witaj świecie!'
        ]
    ];

    $defaultLanguage = 'en';

    function isLocalized ($text, $lang) {
        global $localizations;

        if (array_key_exists($lang, $localizations))
            if (array_key_exists($text, $localizations[$lang]))
                return true;

        return false;
    }

    function localize ($text) {
        global $localizations;
        global $defaultLanguage;
        $lang = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE'])[0];

        if (isLocalized($text, $lang))
            return $localizations[$lang][$text];
        
        $lang = explode('-', $lang)[0];

        if (isLocalized($text, $lang))
            return $localizations[$lang][$text];

        $lang = $defaultLanguage;

        if (isLocalized($text, $lang))
            return $localizations[$lang][$text];
        
        return "### Error: No lacalization for text '".$text."' ###";
    }

    function loadFile($path) {
        $path = preg_match('/\..+$/', $path) ? $path : $path.'.html';
        $fullpath = $_SERVER["DOCUMENT_ROOT"] . '/' . $path;
        return file_exists($fullpath) ? file_get_contents($fullpath) : "### Error: No such file '".$path."' ###";
    }

    function inject ($text, $open, $close, $params, $escape) {
        $text = preg_replace_callback('/'.$open.'[^\*}]*'.$close.'/', function ($match) use ($open, $close, $params, $escape) {
            $match = preg_replace('('.$open.'\s*|\s*'.$close.')', '', $match[0]);
            
            if ($match[0] == '@') {
                $match = loadFile(substr($match, 1));
            } else if ($match[0] == '$') {
                $match = localize(substr($match, 1));
            } else {
                if (array_key_exists($match, $params))
                    $match = $params[$match];
                else
                    $match = "### Error: No such param '".$match."' ###";
            }
            
            return $escape ? htmlspecialchars($match) : $match;
        }, $text);
        return $text;
    }
    
    function render ($path, $params = []) {
        $text = loadFile($path);
        $text = preg_replace('/<!--[^!].*-->/', '', $text);
        $text = inject($text, '{{', '}}', $params, true);
        $text = inject($text, '{\*', '\*}', $params, false);
        return $text;
    }
?>