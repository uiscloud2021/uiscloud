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
        return $this->belongsToMany(User::class,'folder_user')
            ->withPivot('user_id');
    }

    //RELACION UNO A MUCHOS INVERSA EN TABLAS
    /*public function files(){
        return $this->belongsTo(File::class);
    }*/

    public function useris(){
        return $this->belongsTo(User::class);
    }

    public function categories(){
        return $this->belongsTo(Category::class,'id');
    }
}
