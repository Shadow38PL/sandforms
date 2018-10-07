<?php
    require_once 'conf.php';

    function getFullPath($path) {
        global $webRoot;
        $path = preg_match('/\..+$/', $path) ? $path : $path.'.html';
        return $webRoot . $path;
    }

    function loadFile($path) {
        $fullpath = getFullPath($path);

        return file_exists($fullpath) ? file_get_contents($fullpath) : false;
    }

    function getLocalization ($lang) {
        global $langFolder;
        $localization = loadFile($langFolder . '/' . $lang . '.json');

        return $localization ? json_decode($localization, true) : false;
    }

    function localize ($text) {
        global $defaultLanguage;
        global $langCookieName;

        $lang = isset($_COOKIE[$langCookieName]) ? $_COOKIE[$langCookieName] : false;

        if ($lang) {
            if (!$localization = getLocalization($lang))
                $lang = explode('-', $lang)[0];
        }

        if (!$lang || !$localization = getLocalization($lang))
            $lang = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE'])[0];

        if (!$localization = getLocalization($lang))
            $lang = explode('-', $lang)[0];

        if (!$localization = getLocalization($lang))
            $localization = getLocalization($defaultLanguage);

        return array_key_exists($text, $localization) ? $localization[$text] : "### Error: No lacalization for text '" . $text . "' ###";
    }

    function getTags ($string) {
        $splitted = explode('{{', $string);
        array_shift($splitted);
        
        $content = [];

        for ($i = 0; count($splitted); $i++) {
            $content[$i] = '';
            $pairs = 0;

            while ($pairs < 1) {
                $piece = array_shift($splitted);
                $closures = explode('}}', $piece);
                $pairs += count($closures) - 1;
            
                array_pop($closures);
            
                if ($pairs >= 1)
                    $content[$i] .= implode('}}', $closures);
                else {
                    $content[$i] .= $piece . '{{';
                    $pairs--;
                }
            }

            $content[$i] = '{{' . $content[$i] . '}}';
        }

        return $content;
    }

    function insertMap ($mapparts, $params) {
        $param = insertParam(trim($mapparts[0]), $params);
        $template = trim($mapparts[1]);
        
        if ($param == null)
            return "### Error: No such param '" . trim($mapparts[0]) . "' ###";
        
        if (!is_array($param))
            return "### Error: Param '" . trim($mapparts[0]) . "' is not an array ###";
        
        if (array_keys($param) === range(0, count($param) - 1)) {
            if (is_array($param[0]))
                $param = array_map(function ($value) {$value['this'] = $value; return $value;}, $param);
            else
                $param = array_map(function ($value) {return ['value' => $value, 'this' => ['value' => $value]];}, $param);
        } else
            $param['this'] = $param;
        
        return $template[0] == '@' ? render(substr($template, 1), $param) : renderContent($template, $param);
    }

    function insertConditional ($cparts, $params) {
        $param = insertParam(trim($cparts[0]), $params);
        $template = trim($cparts[1]);
        
        return $param != null ? $template[0] == '@' ? render(substr($template, 1), $params) : renderContent($template, $params) : '';
    }

    function insertParam ($content, $params) {
        $tree = explode('.', $content);

        $result = $params;

        for ($depth = 0; $depth < count($tree); $depth++) {
            if (array_key_exists($tree[$depth], $result))
                $result = $result[$tree[$depth]];
            else {
                $result = null;
                break;
            }
        }

        return $result;
    }

    function insert ($tag, $params) {
        global $constants;

        $content = preg_replace('/(^{{\s*)|(\s*}}$)/', '', $tag);

        $mapparts = explode('=>', $content, 2);
        $cparts = explode('?', $content, 2);

        if (count($mapparts) > 1 && count($cparts) > 1) {
            if (strlen($mapparts[0]) < strlen($cparts[0]))
                $result = insertMap($mapparts, $params);
            else
                $result = insertConditional($cparts, $params);
        } else if (count($mapparts) > 1) 
            $result = insertMap($mapparts, $params);
        else if (count($cparts) > 1)
            $result = insertConditional($cparts, $params);
        else {
            if ($content[0] == '@')
                $result = render(substr($content, 1));
            else if ($content[0] == '$')
                $result = localize(substr($content, 1));
            else if ($content[0] == '#') {
                if (array_key_exists(substr($content, 1), $constants))
                    $result = $constants[substr($content, 1)];
                else
                    $result = "### Error: No such constant '" . substr($content, 1) . "' ###";
            } else {
                $result = insertParam($content, $params);

                if ($result == null)
                    $result = "### Error: No such param '" . $content . "' ###";
                else
                    $result = htmlspecialchars($result);
            }
        }

        if (is_array($result)) {
            if ((array_keys($result) === range(0, count($result) - 1))) {
                if (is_array($result[0]))
                    $result = array_map(function ($value) {return json_encode($value);}, $result);
                
                $result = implode('', $result);
            } else 
                $result = json_encode($result);
        }

        return $result;
    }

    function renderContentSingle ($content, $params) {
        $tags = getTags($content);

        for ($i = 0; $i < count($tags); $i++) {
            $tag = $tags[$i];
            $res = insert($tag, $params);
            $content = preg_replace('/'.preg_quote($tag, '/').'/', $res, $content, 1);
        }

        return $content;
    }

    function renderContent ($content, $params, $minify = false) {
        $content = preg_replace('/<!--[^!].*-->/', '', $content);

        if (array_keys($params) === range(0, count($params) - 1)) {
            $result = [];
            for ($i = 0; $i < count($params); $i++) {
                $result[$i] = renderContentSingle($content, $params[$i]);
            }
        } else {
            $result = renderContentSingle($content, $params);
        }

        return $minify ? preg_replace('/>\s+</', '><', $result) : $result;
    }

    function render ($path, $params = [], $minify = false) {
        $content = loadFile($path);

        return $content ? renderContent($content, $params, $minify) : "### Error: No such file '" . $path . "' ###";
    }
?>