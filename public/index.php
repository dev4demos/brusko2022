<?php

/**
 * Brusko - A PHP Test Application.
 * brusko.ru
 *
 */

date_default_timezone_set('Europe/Samara');

define('BASE_PATH', __DIR__);

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
 */

$app = require_once __DIR__ . '/../bootstrap/app.php';

$app->run();
