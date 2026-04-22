<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perfilamiento extends Model
{
    use HasFactory;

    protected $table = 'perfilamiento';
    protected $primaryKey = 'perf_id';
    
    protected $fillable = [
        'perf_nombre_perfil',
    ];

    public function personal()
    {
        return $this->belongsToMany(Personal::class, 'personal_perfilamiento', 'perf_id', 'per_id');
    }
}
