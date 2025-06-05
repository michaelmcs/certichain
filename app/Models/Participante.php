<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Participante extends Model
{
    use HasFactory;
    public $table = "participante";
    public $primaryKey = "id_participante";
    public $timestamps = false;
    protected $fillable = [
        'id_curso',
        'dni',
        'nombre',
        'apellido',
        'correo',
        'codigo',
        'participo_como',
        'certificado',
        'programa_id',
        'tipo_id'
    ];
}
