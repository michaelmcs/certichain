<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Usuario extends Authenticatable
{
    use Notifiable;

    public $table = "usuario";
    public $primaryKey = "id_usuario";
    //public $timestamps=false;
    protected $fillable = [
        'usuario', 'password', 'dni', 'nombres', 'telefono', 'correo', 'foto'
    ];
}
