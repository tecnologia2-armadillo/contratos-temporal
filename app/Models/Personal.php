<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Personal extends Model
{
    use HasFactory;

    protected $table = 'personal';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'foto',
        'tipo_documento',
        'num_documento',
        'primer_nombre',
        'segundo_nombre',
        'primer_apellido',
        'segundo_apellido',
        'telefono_llamada',
        'telefono_whatsapp',
        'fecha_nacimiento',
        'correo',
        'direccion_residencia',
        'nombre_emergencia',
        'telefono_contacto_emergencia',
        'estatura',
        'talla_camiseta',
        'talla_pantalon',
        'talla_zapatos',
        'localidad_barrio',
        'alergias',
        'nivel_ingles',
        'generos_id',
        'municipios_id',
        'eps_id',
        'alimentacion_id',
        'status_personal_id',
        'detalle_status',
        'signature_token',
    ];

    /**
     * The attributes that should be appended to the model's array form.
     *
     * @var array
     */
    protected $appends = ['nombre_completo'];

    /**
     * The "booted" method of the model.
     */
    protected static function booted()
    {
        static::creating(function ($person) {
            if (empty($person->signature_token)) {
                $person->signature_token = (string) Str::uuid();
            }
        });
    }

    /**
     * Get or create a signature token for the person.
     */
    public function getSignatureTokenAttribute($value)
    {
        if (empty($value)) {
            $newToken = (string) Str::uuid();
            $this->update(['signature_token' => $newToken]);
            return $newToken;
        }
        return $value;
    }

    /**
     * Get the personal's full name.
     */
    public function getNombreCompletoAttribute()
    {
        return trim("{$this->primer_nombre} {$this->segundo_nombre} {$this->primer_apellido} {$this->segundo_apellido}");
    }

    // Relationships

    public function genero()
    {
        return $this->belongsTo(Genero::class, 'generos_id', 'id');
    }

    public function ciudad()
    {
        return $this->belongsTo(Ciudad::class, 'municipios_id', 'id');
    }

    public function eps()
    {
        return $this->belongsTo(Eps::class, 'eps_id', 'id');
    }

    public function alimentacion()
    {
        return $this->belongsTo(Alimentacion::class, 'alimentacion_id', 'id');
    }

    public function datoBancario()
    {
        return $this->hasOne(DatoBancario::class, 'personal_id', 'id');
    }

    public function status()
    {
        return $this->belongsTo(StatusPersonal::class, 'status_personal_id', 'id');
    }

    public function perfiles()
    {
        return $this->belongsToMany(Perfilamiento::class, 'personal_perfilamiento', 'personal_id', 'perfilamiento_id');
    }

    public function contratos()
    {
        return $this->belongsToMany(
            Contrato::class,
            'contrato_personal',
            'personal_id',
            'contrato_id',
            'id',
            'id'
        )->withPivot('ip_firma', 'contrato_src')->withTimestamps();
    }
}
