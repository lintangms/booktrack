<?php

/**
 * Laravel - A PHP Framework For Web Artisans
 * Root gateway for local development / Apache subfolder setups
 */

$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? ''
);

if (php_sapi_name() === 'cli-server' && $uri !== '/' && file_exists(__DIR__.'/public'.$uri)) {
    return false;
}

require_once __DIR__.'/public/index.php';
