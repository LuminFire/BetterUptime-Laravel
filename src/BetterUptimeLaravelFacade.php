<?php

namespace BrilliantPackages\BetterUptimeLaravel;

use Illuminate\Support\Facades\Facade;

/**
 * @see \BrilliantPackages\BetterUptimeLaravel\BetterUptimeLaravel
 */
class BetterUptimeLaravelFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return BetterUptimeLaravel::class;
    }
}
