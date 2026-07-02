<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DatoBancario extends Model
{
    use HasFactory;

    protected $table = 'cuentas_bancarias';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'numero_cuenta',
        'tipo_cuenta',
        'banco_id',
        'personal_id',
    ];

    public function banco()
    {
        return $this->belongsTo(Banco::class, 'banco_id', 'id');
    }
}
