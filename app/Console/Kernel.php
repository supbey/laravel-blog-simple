<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();

        // 使用调试命令间隔一段时间处理队列任务，  *这个需要编辑服务器的 crontab 开启调度命令*
        // 每1分钟运行一次
        // $schedule->command('queue:work')->everyMinute();
        // 每5分钟运行一次
        // $schedule->command('queue:work')->everyFiveMinutes();
        // 一天运行一次
        // $schedule->command('queue:work')->daily();
        // 每个星期一早上8:15运行
        // $schedule->command('queue:work')->weeklyOn(1, '8:15');   
        
        
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
