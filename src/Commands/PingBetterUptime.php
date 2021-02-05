<?php

namespace BrilliantPackages\BetterUptimeLaravel\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PingBetterUptime extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'better-uptime:ping';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ping Better Uptime';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (! config('betteruptime-laravel.heartbeat.enabled')) {
            $this->error('BetterUptime heartbeat is disabled.');
            return 0;
        }

        if (empty(config('betteruptime-laravel.heartbeat.url'))) {
            $this->error('No uptime URL specified.');
            return 1;
        }

        $response = Http::head(config('betteruptime-laravel.heartbeat.url'));
        if ($response->successful()) {
            $this->info('Status '.$response->getStatusCode());
        } else {
            $errorString = 'Error code '.$response->status();

            if (! empty($response->body())) {
                $errorString .= ' with message '.$response->body();
            };

            $this->error($errorString);
            Log::warning('PingBetterUptime failed; '.$errorString);
            return 1;
        }

        return 0;
    }
}
