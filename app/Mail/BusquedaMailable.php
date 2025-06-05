<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BusquedaMailable extends Mailable
{
    use Queueable, SerializesModels;

    public $subject = "MI CERTIFICADO"; //ESTO ES EL ASUNTO DEL CORREO
    public $codigo; //solo creamos para pasar variables a la vista y ENVIARSELOS

    public function __construct($codigo)
    {
        $this->codigo = $codigo;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    
    public function build()
    {
        /* ESTO ES LA VISTA QUE SE VA A ENVIAR AL CORREO */
        return $this->view('certificados/vistaCorreo');
    }
}
