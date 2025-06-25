<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Solicitud extends Model
{
    use SoftDeletes;
    protected $table = 'solicitudes';

    protected $fillable = [
        'creador',
        'contenido',
        'revisado_por',
        'respuesta',
        'estado',
        'tipo_solicitud'
    ];

    public function productos(): BelongsToMany
    {
        return $this->belongsToMany(Producto::class)->withTrashed();
    }
}
