<?php

namespace BrilliantPackages\BetterUptimeLaravel\Tests;

use Illuminate\Console\Scheduling\Schedule;

class ConfigTest extends TestCase
{
    /** @test */
    public function telescope_ignores_better_uptime_when_enabled()
    {
        $this->assertContains('better-uptime', config('telescope.ignore_paths'));
    }

    /** @test */
    public function default_route_exists()
    {
        $this->assertEquals('better-uptime', $this->app['router']->getRoutes()->getByName('betteruptime')->uri);
    }

    /** @test */
    public function default_schedule_works()
    {
        $this->heartbeatUrl = 'https://example.com/heartbeat';
        $this->refreshApplication();

        $scheduler = $this->app->make(Schedule::class);

        $events = $scheduler->events();

        $this->assertStringContainsString('better-uptime:ping', $events[0]->command);
        $this->assertEquals('*/5 * * * *', $events[0]->expression);
    }

    /** @test */
    public function schedule_is_not_registered_when_url_is_blank()
    {
        $scheduler = $this->app->make(Schedule::class);

        $this->assertEmpty($scheduler->events());
    }
}
