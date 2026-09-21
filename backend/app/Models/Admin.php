<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    protected $table = 'admin';
    protected $primaryKey = 'administratorID';
    protected $fillable = ['aktivs', 'pedejaPieslegsanas', 'lietotajID'];

    public function lietotajs() { return $this->belongsTo(Lietotajs::class, 'lietotajID'); }
}
