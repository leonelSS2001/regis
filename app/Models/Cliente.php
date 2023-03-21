<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;
    //definiendo relaciones inversas 
    public function pedido(){
        return $this->hasMany(Pedido::class);
    }
}


 