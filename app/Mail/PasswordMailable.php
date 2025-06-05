<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PasswordMailable extends Mailable
{
    use Queueable, SerializesModels;

    public $subject = "Recuperar Clave"; //ESTO ES EL ASUNTO DEL CORREO
    public $dni; //solo creamos para pasar variables a la vista y ENVIARSELOS 
    public function __construct($dni)
    {
        $this->dni = $dni; //ALMACENAMOS LOS DATOS QUE SE RECIBEN PARA MOSTRAR EN LA VISTA
    }


   

    public function build()
    {
        /* ESTO ES LA VISTA QUE SE VA A ENVIAR AL CORREO */
        return $this->view('auth.vistaPassword');
    }
}
