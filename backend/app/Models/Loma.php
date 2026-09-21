<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Loma extends Model
{
    protected $table = 'loma';
    protected $primaryKey = 'lomasID';
    protected $fillable = ['lomasnosaukums'];

    public function lietotaji()
    {
        return $this->belongsToMany(Lietotajs::class, 'lietotajloma', 'lomasID', 'lietotajID');
    }
}