<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;

    //HABILITAR ASIGNACION MASIVA
    protected $guarded = ['id', 'created_at', 'update_at'];
    
    //RELACION UNO A MUCHOS INVERSA EN TABLAS
    /*public function users(){
        //return $this->belongsTo(User::class);
        return $this->belongsTo(User::class,'id');
    }*/

    /*public function files(){
        //return $this->belongsTo(File::class);
        return $this->belongsTo(File::class,'id');
    }*/
}
