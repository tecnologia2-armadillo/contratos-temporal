<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Autorizado extends Model
{
    use HasFactory;

    protected $table = 'autorizados';
    // primaryKey is default 'id'
    
    protected $fillable = [
        'num_cedula',
        'nombre',
    ];
}
