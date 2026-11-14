<?php

use Illuminate\Support\Facades\Artisan;

Artisan::command('about-base', function () {
    $this->info('Лесная гавань · Laravel 13 · Bootstrap 5');
})->purpose('Информация о проекте');
