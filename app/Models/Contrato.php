<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contrato extends Model
{
    use HasFactory;

    protected $table = 'contratos';

    protected $fillable = [
        'nombre',
        'terminos',
        'fecha_inicio',
        'fecha_fin',
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin'    => 'date',
    ];

    /**
     * Personal vinculado asociado a este contrato (tabla intermedia contrato_personal).
     * Pivot: ip_firma, contrato_src
     */
    public function personal()
    {
        return $this->belongsToMany(
            Personal::class,
            'contrato_personal',
            'contrato_id',
            'personal_id',
            'id',
            'per_id'
        )->withPivot('ip_firma', 'contrato_src')->withTimestamps();
    }

    /**
     * Personal no vinculado asociado a este contrato (tabla intermedia contrato_personal_no_vinculado).
     * Pivot: ip_firma, contrato_src
     */
    public function personalNoVinculado()
    {
        return $this->belongsToMany(
            PersonalNoVinculado::class,
            'contrato_personal_no_vinculado',
            'contrato_id',
            'personal_no_vinculado_id'
        )->withPivot('ip_firma', 'contrato_src')->withTimestamps();
    }
}
