<?php

declare (strict_types = 1);

use App\Helper;
use App\Router;
use App\SqliteDb;

/*
|--------------------------------------------------------------------------
| Autoload
|--------------------------------------------------------------------------
 */
require __DIR__ . '/../app/Helper.php';

foreach ([
    __DIR__ . '/../app/',
    __DIR__ . '/../app/Controllers'
] as $path) {
    Helper::requireFiles($path);
}

/*
|--------------------------------------------------------------------------
| Create Application
|--------------------------------------------------------------------------
 */

$app = Router::make($_SERVER['REQUEST_URI'], __DIR__ . '/../')->setBaseDir(BASE_PATH)
    ->addHandler('/', [App\Controllers\WebController::class, 'getIndex'])
    ->addHandler('dashboard', [App\Controllers\WebController::class, 'getDashboard'])
    ->addHandler('dashboard', [App\Controllers\WebController::class, 'postDashboard'], 'POST')
    ->addHandler('palindrome', [App\Controllers\WebController::class, 'getPalindrome'])
    ->addHandler('palindrome', [App\Controllers\WebController::class, 'postPalindrome'], 'POST')
    ->addHandler('files', [App\Controllers\WebController::class, 'getFiles'])
    ->addHandler('files', [App\Controllers\WebController::class, 'postFiles'], 'POST')
    ->addHandler('generateFiles', function ($app) {
        !($howMany = $app->request()->input['howMany']) ?: $app->createSessionFiles((int) $howMany);
        //
        $app->redirectTo('/files');
    }, 'POST')
    ->addHandler('migrate', function ($app) {
        $app->dbm()->exec($app->fileContents($app->getRootDir() . '/storage/migrate.sql'));
        $app->dbm()->exec($app->fileContents($app->getRootDir() . '/storage/seed.sql'));
        //
        $app->loadView('migrate.php');
    })
    ->addHandler('migrate-down', function ($app) {
        foreach (['user', 'orders', 'comments'] as $table) {
            $app->dbm()->exec('DROP TABLE IF EXISTS ' . $table);
        }
        //
        $app->redirectTo('/migrate');
    });

/*
|--------------------------------------------------------------------------
| Create Database
|--------------------------------------------------------------------------
 */
$app->dbm(SqliteDb::make($app->getRootDir() . '/storage/sqlitedb.db'));

/*
|--------------------------------------------------------------------------
| Start a session
|--------------------------------------------------------------------------
 */

if (empty(session_id())) {
    session_start();
}

return $app;
