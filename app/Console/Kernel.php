<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

// Asegúrate de importar tu clase
use App\Console\Commands\TimestampModeloCertificado;

class Kernel extends ConsoleKernel
{
    /**
     * Los comandos disponibles para Artisan.
     *
     * @var array
     */
    protected $commands = [
        TimestampModeloCertificado::class,
    ];

    /**
     * Define el cron de los comandos programados.
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
    }

    /**
     * Registrar los comandos de consola.
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
