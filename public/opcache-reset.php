<?php
if (function_exists('opcache_reset')) {
    opcache_reset();
    echo 'OPcache cleared successfully';
} else {
    echo 'OPcache not available or already disabled';
}
if (function_exists('apc_clear_cache')) {
    apc_clear_cache();
    echo ' + APC cleared';
}
echo PHP_EOL . 'FIREBASE_APIKEY: ' . bin2hex(getenv('FIREBASE_APIKEY') ?: '');
echo PHP_EOL . 'APP_KEY: ' . getenv('APP_KEY');
