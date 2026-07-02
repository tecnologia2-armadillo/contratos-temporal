<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alimentacion extends Model
{
    use HasFactory;

    protected $table = 'alimentacion';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'nombre',
    ];
}
