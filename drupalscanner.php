<?php

$root = realpath(isset($argv[1]) ? $argv[1] : '.');

function scanStrangeContents($dir, $file) {
    $flen = strlen($file) - 4;
    $path = $dir.'/'.$file;
    if (strpos($file, '.php') === $flen || strpos($file, '.inc') === $flen || strpos($file, '.ico') === $flen) {
        $contents = file_get_contents($dir.'/'.$file);
        if (
            strpos($contents, '\\x') !== false 
            || strpos($contents, 'NullLogger') 
            || strpos($contents, 'REVRES_$') !== false 
            || strpos($contents, 'base64_decode($_SERVER') !== false
            || strpos($contents, 'base64_decode("Y"') !== false
            || strpos($contents, 'base64_decode(\'\'') !== false
            || strpos($contents, 'eval(v') !== false
            || strpos($contents, '$auth_pass =') !== false
            || strpos($contents, '=$_COOKIE;') !== false
            || strpos($contents, '= $GLOBALS;') !== false
            || strpos($contents, '($_COOKIE, $_POST)') !== false
            || strpos($contents, 'define(\'stream_context_create') !== false
        ) {
            echo $path."<br/>";
        }
    }
}

function scan($dir) {
    $list = array_diff(scandir($dir), array('..', '.'));
    foreach($list as $file) {
        if (is_file($dir.'/'.$file)) {
            scanStrangeContents($dir, $file);
        } else if ($file !== '.' && $file !== '..' && is_dir($dir.'/'.$file)) {
            scan($dir.'/'.$file);
        }
    }
}

scan($root);
