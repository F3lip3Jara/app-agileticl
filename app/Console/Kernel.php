<?php

namespace App\Console;

use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */

     protected $commands = [
        
        Commands\Websocket::class,
        Commands\Queue::class,
       
    ];


    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
        $horaServidor = Carbon::now()->format('H:i'); 
        $schedule->command('app:websocket')->dailyAt($horaServidor);
        $schedule->command('app:queue')->dailyAt($horaServidor);
        $schedule->command('app:actualiza_indicadores')->dailyAt($horaServidor);
      
        

    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
