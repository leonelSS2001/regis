<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model

{
    use HasFactory;

    protected $table = 'productos';

    public function categorias()
    {
        return $this->belongsToMany(Categoria::class);
    }

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class);
    }
    
    public function detallePedidos()
    {
        return $this->hasMany(DetallePedido::class);
    }

}
