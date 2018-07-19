<?php

if (function_exists('setCustomErrorHandler')) {
    return;
}

$path = __DIR__ . '/../../../dev/tests/unit/framework/bootstrap.php';

if (strpos(__DIR__, 'app/code') !== false) {
    $path = __DIR__ . '/../../../../../dev/tests/unit/framework/bootstrap.php';
}

require_once($path);
