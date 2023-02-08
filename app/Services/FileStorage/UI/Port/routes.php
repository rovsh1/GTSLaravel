<?php

use Custom\Framework\Support\Facades\Route;

use GTS\Services\FileStorage\UI\Port\Controllers\Controller;

Route::register('create', [Controller::class, 'create']);
//$gateway->register('fileCreate', FileCreateAction::class);
