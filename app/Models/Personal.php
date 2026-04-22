<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Personal extends Model
{
    use HasFactory;

    protected $table = 'personal';
    protected $primaryKey = 'per_id';
    
    protected $fillable = [
        'per_pdf_cedula',
        'per_pdf_rut',
        'per_pdf_comprobante_banco',
        'per_pdf_hoja_vida',
        'per_tipo_doc',
        'per_num_doc',
        'per_primer_nombre',
        'per_segundo_nombre',
        'per_primer_apellido',
        'per_segundo_apellido',
        'per_telefono_llamada',
        'per_telefono_whatsapp',
        'per_fecha_nacimiento',
        'per_correo',
        'per_direccion_residencia',
        'per_nombre_emergencia',
        'per_telefono_contacto_emergencia',
        'per_estatura',
        'per_talla_camiseta',
        'per_talla_pantalon',
        'per_talla_zapatos',
        'per_localidad_barrio',
        'per_alergias',
        'per_nivel_ingles',
        'per_genero_id',
        'per_ciudad_id',
        'per_eps_id',
        'per_ali_id',
        'per_dato_bancario',
        'per_status_personal',
        'per_foto',
        'per_detalle_status',
        'contrato_firmado',
        'contrato_src',
    ];

    /**
     * Get the personal's full name.
     */
    public function getNombreCompletoAttribute()
    {
        return trim("{$this->per_primer_nombre} {$this->per_segundo_nombre} {$this->per_primer_apellido} {$this->per_segundo_apellido}");
    }

    // Relationships

    public function genero()
    {
        return $this->belongsTo(Genero::class, 'per_genero_id', 'gen_id');
    }

    public function ciudad()
    {
        return $this->belongsTo(Ciudad::class, 'per_ciudad_id', 'ciu_id');
    }

    public function eps()
    {
        return $this->belongsTo(Eps::class, 'per_eps_id', 'eps_id');
    }

    public function alimentacion()
    {
        return $this->belongsTo(Alimentacion::class, 'per_ali_id', 'ali_id');
    }

    public function datoBancario()
    {
        return $this->belongsTo(DatoBancario::class, 'per_dato_bancario', 'dba_id');
    }

    public function status()
    {
        return $this->belongsTo(StatusPersonal::class, 'per_status_personal', 'spe_id');
    }

    public function perfiles()
    {
        return $this->belongsToMany(Perfilamiento::class, 'personal_perfilamiento', 'per_id', 'perf_id');
    }
}
