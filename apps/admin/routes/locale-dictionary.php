<?php

declare(strict_types=1);

use App\Admin\Http\Controllers\Data\LocaleDictionaryController;
use App\Admin\Support\Facades\AclRoute;

AclRoute::for('locale-dictionary')
    ->get('/search', LocaleDictionaryController::class . '@search', 'read', 'search');
