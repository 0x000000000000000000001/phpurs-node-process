<?php

$exports['argv'] = function() {
    global $argv;
    return $argv ?? [];
};

$exports['setEnv'] = function($key) {
    return function($val) {
        return function() use ($key, $val) {
            putenv("$key=$val");
        };
    };
};

$exports['unsafeGetEnv'] = function() {
    return (object)(getenv() ?: []);
};

$exports['stdout'] = defined('STDOUT') ? STDOUT : fopen('php://stdout', 'w');
$exports['stderr'] = defined('STDERR') ? STDERR : fopen('php://stderr', 'w');

$exports['exitImpl'] = function($code) {
    exit($code);
};

return $exports;
