<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    //HABILITAR ASIGNACION MASIVA
    protected $guarded = ['id', 'created_at', 'update_at'];

    //RELACION MUCHOS A MUCHOS
    public function users(){
        return $this->belongsToMany(User::class,'category_user')
            ->withPivot('category_id');
    }

    //RELACION UNO A MUCHOS
    public function files(){
        return $this->hasMany(File::class);
    }

    public function folders(){
        return $this->hasMany(Folder::class);
    }
}
