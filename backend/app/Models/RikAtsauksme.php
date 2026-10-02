<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RikAtsauksme extends Model
{
    protected $table = 'rikatsauksme';
    protected $primaryKey = 'rikatsauksmeID';
    protected $fillable = ['rikID', 'lietotajID', 'vertejums', 'teksts'];

    public function riks()
    {
        return $this->belongsTo(Riks::class, 'rikID');
    }

    public function lietotajs()
    {
        return $this->belongsTo(Lietotajs::class, 'lietotajID');
    }
}