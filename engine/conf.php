<?php
    $constants = [];

    $constants['production'] = false;
    $constants['version'] = 1;
    $constants['ver'] = $constants['production'] ? $constants['version'] : rand(0, 9999);
?>