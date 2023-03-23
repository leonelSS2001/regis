<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;
    protected $table = 'pedidos';
    
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
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
