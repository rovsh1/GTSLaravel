<?php

use GTS\Services\FileStorage\UI\Port\Actions\FileCreateAction;

Route::module('fileStorage');

Route::register('create///', FileCreateAction::class);
//$gateway->register('fileCreate', FileCreateAction::class);
