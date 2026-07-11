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

$exports['lookupEnv'] = function($key) {
    return function() use ($key) {
        $val = getenv($key);
        return $val !== false ? $val : null;
    };
};

$exports['stdout'] = defined('STDOUT') ? STDOUT : fopen('php://stdout', 'w');
$exports['stderr'] = defined('STDERR') ? STDERR : fopen('php://stderr', 'w');

$exports['exitImpl'] = function($code) {
    exit($code);
};

return $exports;
