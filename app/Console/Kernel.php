<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
        \App\Console\Commands\ClearWeek::class,
        \App\Console\Commands\ClearMonth::class,
        \App\Console\Commands\ClearQuarter::class,
        \App\Console\Commands\ClearYear::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
        //每个星期六 晚上凌晨 运行任务
        $schedule->command('clear:week')->weekly()->sundays()->at('23:59');;
        //每个月1号凌晨一点 运行任务
        $schedule->command('clear:month')->monthlyOn(1, '1:00');
        //每个季度第一个月的1号凌晨一点 运行任务
        $schedule->command('clear:quarter')->monthlyOn(1, '1:00')->when(function () {
            $time = date("m",time());
            if($time%4 == 1){
                return true;
            }else{
                return false;
            }
        });
        //每个年第一个月的1号凌晨一点 运行任务
        $schedule->command('clear:year')->monthlyOn(1, '1:00')->when(function () {
            $time = date("m",time());
            if($time%12 == 1){
                return true;
            }else{
                return false;
            }
        });
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
