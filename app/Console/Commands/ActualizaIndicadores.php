<?php

namespace App\Console\Commands;

use App\Http\Controllers\Parametros\MonedaController;
use Illuminate\Console\Command;

class ActualizaIndicadores extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:actualiza_indicadores';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
       
        $controller = new MonedaController();
        // Llamar al método del controlador
        $response = $controller->indicadores();

        // Mostrar la respuesta en la consola
        $this->info($response);

        return 0;
    }
}
