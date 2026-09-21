<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Lietotajs extends Authenticatable
{
    use HasApiTokens;
    protected $table = 'lietotajs';
    protected $primaryKey = 'lietotajsID';
    protected $fillable = ['vards', 'epasts', 'telefons', 'parole'];
    protected $hidden = ['parole'];

    public function getAuthPassword() { return $this->parole; }

    public function lomas()
    {
        return $this->belongsToMany(Loma::class, 'lietotajloma', 'lietotajID', 'lomasID');
    }

    public function admin()
    {
        return $this->hasOne(Admin::class, 'lietotajID');
    }

    public function pasutijumi()
    {
        return $this->hasMany(Pasutijums::class, 'lietotajID');
    }
}
