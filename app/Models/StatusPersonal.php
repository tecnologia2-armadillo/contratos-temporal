<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusPersonal extends Model
{
    use HasFactory;

    protected $table = 'status_personal';
    protected $primaryKey = 'spe_id';
    
    protected $fillable = [
        'spe_status_personal',
        'spe_detalle_status',
    ];
}
