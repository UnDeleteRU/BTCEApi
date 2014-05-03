<?php

spl_autoload_register(function ($class) {
    $paths = explode("\\", $class);

    if (($paths[0] != "Undelete") || ($paths[1] != "BTCEApi")) {
        return;
    }

    $fileName = __DIR__ . '/' .$paths[2] . '.php';

    if (file_exists($fileName)) {
        require_once $fileName;
    }
});
