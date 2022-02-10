<?php

namespace BrilliantPackages\BetterUptimeLaravel;

use BrilliantPackages\BetterUptimeLaravel\Commands\PingBetterUptime;
use Illuminate\Console\Scheduling\Schedule;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class BetterUptimeLaravelServiceProvider extends PackageServiceProvider
{
    /**
     * Configure the package.
     *
     * More info: https://github.com/spatie/laravel-package-tools
     *
     * @param \Spatie\LaravelPackageTools\Package $package
     *
     * @return void
     * @since 1.0.0
     */
    public function configurePackage(Package $package): void
    {
        $package
            ->name('betteruptime-laravel')
            ->hasConfigFile()
            ->hasRoute('betteruptime')
            ->hasViews()
            ->hasCommand(PingBetterUptime::class);
    }

    /**
     * Add the BetterUptime monitor page to ignored paths.
     *
     * @return void
     * @since 1.0.0
     */
    public function packageRegistered()
    {
        if (config('betteruptime-laravel.monitor.enabled')) {
            config()->set('telescope.ignore_paths', array_merge(
                (array) config('telescope.ignore_paths'),
                [config('betteruptime-laravel.monitor.path')]
            ));
        }
    }

    /**
     * Schedule the heartbeat monitor.
     *
     * @return void
     * @since 1.0.0
     */
    public function packageBooted()
    {
        if (config('betteruptime-laravel.heartbeat.enabled') && config('betteruptime-laravel.heartbeat.minutes')) {
            $this->callAfterResolving(Schedule::class, function (Schedule $schedule) {
                $schedule->command('better-uptime:ping')->cron('*/' . config('betteruptime-laravel.heartbeat.minutes') . ' * * * *');
            });
        }
    }
}
