<?php

declare(strict_types=1);

use App\Http\Controllers\Controller;

arch('php')
    ->preset()
    ->php();

arch('security')
    ->preset()
    ->security();

arch('laravel')
    ->preset()
    ->laravel();

arch('strict')
    ->expect('App')
    ->toUseStrictTypes()
    ->classes()->not->toBeAbstract()
    ->ignoring(Controller::class)
    ->classes()->toBeFinal()
    ->ignoring(Controller::class);

arch('function')
    ->expect([
        'sleep',
        'usleep',
    ])->not->toBeUsed();
