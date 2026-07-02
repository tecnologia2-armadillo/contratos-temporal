<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perfilamiento extends Model
{
    use HasFactory;

    protected $table = 'perfilamiento';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'nombre',
    ];

    public function personal()
    {
        return $this->belongsToMany(Personal::class, 'personal_perfilamiento', 'perfilamiento_id', 'personal_id');
    }
}
