<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Eps extends Model
{
    use HasFactory;

    protected $table = 'epss'; // as specified in SQL
    protected $primaryKey = 'eps_id';
    
    protected $fillable = [
        'eps_nombre',
    ];
}
