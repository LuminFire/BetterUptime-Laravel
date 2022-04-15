<?php

namespace BrilliantPackages\BetterUptimeLaravel\Tests;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Symfony\Component\Console\Command\Command;

class CommandTest extends TestCase
{
    /** @test */
    public function artisan_command_fails_if_disabled()
    {
        config([
            'betteruptime-laravel.heartbeat.enabled' => false,
        ]);

        Http::fake();

        $this
            ->artisan('better-uptime:ping')
            ->expectsOutput('BetterUptime heartbeat is disabled.')
            ->assertExitCode(Command::SUCCESS);

        Http::assertNothingSent();
    }

    /** @test */
    public function artisan_command_fails_if_url_is_missing()
    {
        Http::fake();

        $this
            ->artisan('better-uptime:ping')
            ->expectsOutput('No uptime URL specified.')
            ->assertExitCode(Command::FAILURE);

        Http::assertNothingSent();
    }

    /** @test */
    public function artisan_command_sends_request()
    {
        config([
            'betteruptime-laravel.heartbeat.enabled' => true,
            'betteruptime-laravel.heartbeat.url' => 'example.com',
        ]);

        Http::fake();

        $this
            ->artisan('better-uptime:ping')
            ->assertExitCode(Command::SUCCESS);

        Http::assertSent(function (Request $request) {
            return $request->url() === config('betteruptime-laravel.heartbeat.url');
        });
    }

    /** @test */
    public function artisan_command_retries()
    {
        config([
            'betteruptime-laravel.heartbeat.enabled' => true,
            'betteruptime-laravel.heartbeat.url' => 'example.com',
        ]);

        Http::fake([
            'example.com' => Http::sequence()
                                  ->push(['error' => true], 400)
                                  ->push(['error' => true], 500)
                                  ->push(['error' => false], 200)
                                  ->push(['error' => false], 200)
                                  ->push(['error' => false], 200),
        ]);

        $this
            ->artisan('better-uptime:ping')
            ->assertExitCode(Command::SUCCESS);

        Http::assertSentCount(3);

        Http::assertSent(function (Request $request) {
            return $request->url() === config('betteruptime-laravel.heartbeat.url');
        });
    }
}
