<?php

namespace App\Jobs;

use App\Models\Oms\OrdenWeb;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SdOrden implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */

     

    public function __construct($opedId)
    {
       
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
      
    }
}
