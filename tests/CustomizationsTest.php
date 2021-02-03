<?php

namespace BrilliantPackages\BetterUptimeLaravel\Tests;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;

class CustomizationsTest extends TestCase
{
    public function getEnvironmentSetup($app)
    {
        $app['config']->set('betteruptime-laravel.monitor.path', 'custom-route');

        $app['config']->set('betteruptime-laravel.heartbeat.enabled', true);
        $app['config']->set('betteruptime-laravel.heartbeat.url', 'https://example.com/better-uptime-test');
        $app['config']->set('betteruptime-laravel.heartbeat.minutes', 47);

        parent::getEnvironmentSetUp($app);
    }

    /** @test */
    public function custom_route_works()
    {
        $this->assertEquals('custom-route', $this->app['router']->getRoutes()->getByName('betteruptime')->uri);
    }

    /** @test */
    public function custom_schedule_works()
    {
        $scheduler = $this->app->make(Schedule::class);

        $events = $scheduler->events();

        $this->assertStringContainsString('better-uptime:ping', $events[0]->command);
        $this->assertEquals('*/47 * * * *', $events[0]->expression);
    }

    /** @test */
    public function heartbeat_http_success()
    {
        Http::fake(['example.com/better-uptime-test' => Http::response()]);

        $test = Artisan::call('better-uptime:ping');

        $this->assertEquals(0, $test);
    }

    /** @test */
    public function heartbeat_http_fail()
    {
        Http::fake(['example.com/better-uptime-test' => Http::response(null, 500)]);

        $test = Artisan::call('better-uptime:ping');

        $this->assertEquals(1, $test);
    }
}
