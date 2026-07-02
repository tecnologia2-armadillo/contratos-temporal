<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusPersonal extends Model
{
    use HasFactory;

    protected $table = 'status_personal';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'nombre',
        'descripcion',
    ];
}
