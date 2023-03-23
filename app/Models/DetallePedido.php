<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detalle_Pedido extends Model
{
    use HasFactory;
    protected $table = 'detalle_pedidos';

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    //relaciones inversas
    public function pedido()
    {
        return $this->belongsTo(Pedido::class);
    }
    
}
 