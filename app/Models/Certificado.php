<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificado extends Model
{
    use HasFactory;
    public $table="certificado";
    public $primaryKey="id_certificado";
    public $fillable=[
        "id_curso","modelo"
    ];
}
