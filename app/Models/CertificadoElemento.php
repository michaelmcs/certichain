<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CertificadoElemento extends Model
{
    protected $table = 'certificado_elemento';

    protected $primaryKey = 'id_elemento';

    public $timestamps = false;

    protected $fillable = [
        'id_certificado',
        'tipo',
        'nombre',
        'contenido',
        'x',
        'y',
        'ancho',
        'alto',
        'fuente',
        'tamano_fuente',
        'alineacion',
    ];
}

