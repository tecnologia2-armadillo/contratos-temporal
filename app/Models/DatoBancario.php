<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DatoBancario extends Model
{
    use HasFactory;

    protected $table = 'datos_bancarios';
    protected $primaryKey = 'dba_id';
    
    protected $fillable = [
        'dba_num_cuenta',
        'dba_banco_id',
    ];

    public function banco()
    {
        return $this->belongsTo(Banco::class, 'dba_banco_id', 'ban_id');
    }
}
