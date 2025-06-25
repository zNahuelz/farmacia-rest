<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Producto extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'nombre',
        'stock',
        'precio'
    ];
    
    public function solicitudes(): BelongsToMany
    {
        return $this->belongsToMany(Solicitud::class);
    }
}
