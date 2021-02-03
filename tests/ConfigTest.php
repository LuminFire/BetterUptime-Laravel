<?php

namespace BrilliantPackages\BetterUptimeLaravel\Tests;

class ConfigTest extends TestCase
{
    /** @test */
    public function telescope_ignores_better_uptime_when_enabled()
    {
        $this->assertContains('better-uptime', config('telescope.ignore_paths'));
    }
}
