<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banco extends Model
{
    use HasFactory;

    protected $table = 'bancos';
    protected $primaryKey = 'ban_id';
    
    protected $fillable = [
        'ban_banco_nombre',
    ];
}
