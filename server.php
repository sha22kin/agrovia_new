<?php

// Router used by `php artisan serve` (its document root is /public).
//
// Asset links are generated as "/public/<theme>/..." (see assetLink() in
// app/helpers.php) because production points the web root at the project
// folder. Here that prefix would 404, so serve those files ourselves.

$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)
);

// Regular static files that already live under the document root.
if ($uri !== '/' && file_exists(__DIR__.'/public'.$uri)) {
    return false;
}

if (str_starts_with($uri, '/public/')) {
    $publicPath = realpath(__DIR__.'/public');
    $file = realpath(__DIR__.$uri);

    if (
        $file !== false
        && is_file($file)
        && str_starts_with($file, $publicPath.DIRECTORY_SEPARATOR)
        && strtolower(pathinfo($file, PATHINFO_EXTENSION)) !== 'php'
    ) {
        $types = [
            'css' => 'text/css',
            'js' => 'application/javascript',
            'json' => 'application/json',
            'svg' => 'image/svg+xml',
            'ico' => 'image/x-icon',
            'woff' => 'font/woff',
            'woff2' => 'font/woff2',
            'ttf' => 'font/ttf',
            'map' => 'application/json',
        ];
        $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));

        header('Content-Type: '.($types[$extension] ?? mime_content_type($file) ?: 'application/octet-stream'));
        header('Content-Length: '.filesize($file));
        readfile($file);

        return true;
    }
}

// The app uses paths relative to the project root (e.g. "public/medies/noimage.jpg"),
// which is the working directory in production but not under `artisan serve`.
chdir(__DIR__);

require_once __DIR__.'/public/index.php';
