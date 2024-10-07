<?php

namespace App\Console\Commands;
use Symfony\Component\Process\Process;
use Illuminate\Console\Command;

class Queue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:queue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Activa queue';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $command = "pkill -9 -f"."/opt/alt/php81/usr/bin/php artisan queue:work";
        $process = Process::fromShellCommandline($command);
        $process->run();

        $command = "cd /home/agiletic/app.back/app-agileticl";
        $process = Process::fromShellCommandline($command);
        $process->run();

        $process = new Process(['php', "artisan", 'queue:restart']);
        $process->run();
        
        $command = "/usr/local/bin/php artisan queue:work --daemon &";
        $process = Process::fromShellCommandline($command);
        $process->run();
    }
}
