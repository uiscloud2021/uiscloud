<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'position',
        'phone',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];

    public function adminlte_image(){ //metodo para la imagen de usuario
        return "https://picsum.photos/300/300";
    }

    public function adminlte_desc(){ //metodo para rol de usuario
        return "Administrador";
    }

    public function adminlte_profile_url(){ //metodo para perfil de usuario
        return "profile/username";
    }

    //RELACION MUCHOS A MUCHOS
    public function files(){
        return $this->belongsToMany(File::class);
    }

    public function categories(){
        return $this->belongsToMany(Category::class);
    }

    public function folders(){
        return $this->belongsToMany(Folder::class);
    }

    //RELACION UNO A MUCHOS
    public function filies(){
        return $this->hasMany(File::class);
    }

    public function foldiers(){
        return $this->hasMany(Folder::class);
    }
}
