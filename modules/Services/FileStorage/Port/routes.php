<?php

use Custom\Framework\Support\Facades\Route;
use Module\Services\FileStorage\Port\Controllers\Controller;

Route::register('create', [Controller::class, 'create']);
//$gateway->register('fileCreate', FileCreateAction::class);
