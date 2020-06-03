<?php
include_once "vendor/autoload.php";

spl_autoload_register(function ($class) {
    if ($class != 'default') {
        include_once "src/$class.php";
    }
});
?>