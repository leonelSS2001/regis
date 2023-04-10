<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;
    protected $table = 'pedidos';
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
    public function detallePedidos()
    {
        return $this->hasMany(DetallePedido::class);
    }
}
