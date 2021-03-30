<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Folder extends Model
{
    use HasFactory;

    //HABILITAR ASIGNACION MASIVA
    protected $guarded = ['id', 'created_at', 'update_at'];

    //RELACION MUCHOS A MUCHOS EN TABLAS
    public function users(){
        return $this->belongsToMany(User::class);
    }

    public function files(){
        return $this->belongsToMany(File::class);
    }

    //RELACION UNO A MUCHOS INVERSA EN TABLAS
    public function useris(){
        return $this->belongsTo(User::class);
    }

    public function categories(){
        return $this->belongsTo(Category::class);
    }
}
