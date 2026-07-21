<?php

$exports['argv'] = function() {
    global $argv;
    return $argv ?? [];
};

$exports['setEnvImpl'] = function($key) {
    return function($val) {
        return function() use ($key, $val) {
            putenv("$key=$val");
        };
    };
};

$exports['unsafeGetEnv'] = function() {
    return (object)(getenv() ?: []);
};

$exports['stdin'] = defined('STDIN') ? STDIN : fopen('php://stdin', 'r');

$exports['stdout'] = function_exists('\\Amp\\ByteStream\\getStdout') 
    ? \Amp\ByteStream\getStdout() 
    : (defined('STDOUT') ? STDOUT : fopen('php://stdout', 'w'));

$exports['stderr'] = function_exists('\\Amp\\ByteStream\\getStderr') 
    ? \Amp\ByteStream\getStderr() 
    : (defined('STDERR') ? STDERR : fopen('php://stderr', 'w'));

$exports['exitImpl'] = function($code) {
    exit($code);
};

$exports['process'] = (object)[];
$exports['debugPort'] = 9229;
$exports['pid'] = getmypid();
$exports['platformStr'] = PHP_OS;
$exports['ppid'] = function_exists('posix_getppid') ? posix_getppid() : 0;
$exports['stdinIsTTY'] = defined('STDIN') && function_exists('posix_isatty') ? posix_isatty(STDIN) : false;
$exports['stdoutIsTTY'] = defined('STDOUT') && function_exists('posix_isatty') ? posix_isatty(STDOUT) : false;
$exports['stderrIsTTY'] = defined('STDERR') && function_exists('posix_isatty') ? posix_isatty(STDERR) : false;
$exports['version'] = phpversion();

$exports['nextTickImpl'] = function($cb) {
    if (class_exists('\\Revolt\\EventLoop')) {
        \Revolt\EventLoop::queue($cb);
    } else {
        $cb();
    }
};

$exports['nextTickCbImpl'] = function($cb, $args) {
    if (class_exists('\\Revolt\\EventLoop')) {
        \Revolt\EventLoop::queue(function() use ($cb, $args) { $cb($args); });
    } else {
        $cb($args);
    }
};

$exports['cwd'] = function() { return getcwd(); };
$exports['chdirImpl'] = function($dir) { return chdir($dir); };
$exports['getEnv'] = function() { return (object)(getenv() ?: []); };
$exports['getUidImpl'] = function() { return function_exists('posix_getuid') ? posix_getuid() : 0; };
$exports['getGidImpl'] = function() { return function_exists('posix_getgid') ? posix_getgid() : 0; };
$exports['uptime'] = function() { return time() - $_SERVER['REQUEST_TIME']; };
$exports['abortImpl'] = function() { exit(1); };
$exports['exit'] = function() { exit(1); };
$exports['argv0'] = function() { global $argv; return $argv[0] ?? ''; };
$exports['getExitCodeImpl'] = function() { return 0; };
$exports['execArgv'] = function() { return ''; };
$exports['execPath'] = function() { return ''; };
$exports['config'] = function() { return (object)[]; };
$exports['connected'] = function() { return false; };

$dummyProcess = ['channelRefImpl', 'channelUnrefImpl', 'cpuUsage', 'cpuUsageDiffImpl', 'disconnectImpl', 'unsetEnvImpl', 'setExitCodeImpl', 'hasUncaughtExceptionCaptureCallback', 'killImpl', 'killStrImpl', 'killIntImpl', 'memoryUsage', 'memoryUsageRss', 'resourceUsage', 'sendImpl', 'sendOptsImpl', 'sendCbImpl', 'sendOptsCbImpl', 'setUncaughtExceptionCaptureCallbackImpl', 'clearUncaughtExceptionCaptureCallback', 'getTitle', 'setTitleImpl'];
foreach ($dummyProcess as $name) {
    $exports[$name] = function(...$args) { return null; };
}

return $exports;
