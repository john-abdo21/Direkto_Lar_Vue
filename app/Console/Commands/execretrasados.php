<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\RestrictionController;

class execretrasados extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:retrasados';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Se ejecutara un proceso que envia correos a los usuarios que tengan en sus proyectos actividades retrasadas.';

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
     * @return int
     */
    public function handle()
    {
        $controller = app()->make(RestrictionController::class);
        app()->call([$controller, 'cron_enviar_notificacionDiaria']);
    }
}
