<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use HasFactory;
    //relacion de 1:N con 
    public function autos(){
        return $this->hasMany(Auto::class);
    }
}

