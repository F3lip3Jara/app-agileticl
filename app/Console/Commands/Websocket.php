<?php

namespace App\Console\Commands;
use Illuminate\Support\Facades\Log;
use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class Websocket extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:websocket';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reactiva el websocket';

    /**
     * Execute the console command.
     */
    public function handle()
    {
       // Ruta al archivo artisan
          
       $command = "pkill -9 -f"."/opt/alt/php81/usr/bin/php artisan schedule:work";
       $process = Process::fromShellCommandline($command);
       $process->run();
       
       $command = "pkill -9 -f"."/opt/alt/php81/usr/bin/php artisan websocket:serve";
       $process = Process::fromShellCommandline($command);
       $process->run();
       
       
       $command = "cd /home/agiletic/app.back/app-agileticl";
        $process = Process::fromShellCommandline($command);
        $process->run();

        
        $command = "/usr/local/bin/php artisan websockets:restart &";
        $process = Process::fromShellCommandline($command);
        $process->run();
         
        $command = "/usr/local/bin/php artisan websockets:serve &";
        $process = Process::fromShellCommandline($command);
        $process->run();
       
      
      
    }
}
