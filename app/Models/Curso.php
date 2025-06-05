<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Curso extends Model
{
    use HasFactory;
    public $table = "curso";
    public $primaryKey = "id_curso";
    public $fillable = [
        "nombre", "horas", "descripcion", "inicio", "termino", "estado"
    ];
}
