<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonalNoVinculado extends Model
{
    use HasFactory;
    
    protected $table = 'personal_no_vinculado';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'apellido',
        'telefono',
        'correo',
        'fecha_nacimiento',
        'numero_cuenta',
        'tipo_cuenta',
        'banco',
        'identificacion',
        'tipo_identificacion',
    ];

    public function contratos()
    {
        return $this->belongsToMany(
            Contrato::class,
            'contrato_personal_no_vinculado',
            'personal_no_vinculado_id',
            'contrato_id'
        )->withPivot('ip_firma', 'contrato_src')->withTimestamps();
    }
}
