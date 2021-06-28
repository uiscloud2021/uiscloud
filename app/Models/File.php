<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    //HABILITAR ASIGNACION MASIVA
    protected $guarded = ['id', 'created_at', 'update_at'];

    //RELACION MUCHOS A MUCHOS EN TABLAS
    public function users(){
        return $this->belongsToMany(User::class,'file_user')
            ->withPivot('user_id');
    }

    //RELACION DE UNO A MUCHOS
    /*public function folders(){
        return $this->hasMany(Folder::class,'id_folder');
    }*/

    //RELACION UNO A MUCHOS INVERSA EN TABLAS
    public function useris(){
        return $this->belongsTo(User::class,'id_user');
    }

    public function categories(){
        return $this->belongsTo(Category::class,'category_id');
    }

    /*public function logs(){
        return $this->belongsTo(Log::class,'files')
        ->withPivot('file_id');;
    }*/
}


