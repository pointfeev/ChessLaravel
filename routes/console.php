<?php

use App\Models\GameState;
use Illuminate\Support\Facades\Artisan;

Artisan::command('db:clean', function () {
    GameState::clean();
});
