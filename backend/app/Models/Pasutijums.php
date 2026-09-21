<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pasutijums extends Model
{
    protected $table = 'pasutijums';
    protected $primaryKey = 'pasutijumsID';
    protected $fillable = ['kopsumma', 'statuss', 'izveidesdatums', 'lietotajID'];
    protected $casts = ['kopsumma' => 'decimal:2', 'izveidesdatums' => 'datetime'];

    public function lietotajs() 
    { 
        return $this->belongsTo(Lietotajs::class, 'lietotajID'); 
    }
    
    public function riki()
    {
        return $this->belongsToMany(Riks::class, 'pasutijuma_riks', 'pasutijumsID', 'rikID')
                    ->withPivot(['daudzums_pozicija', 'nomasSakums', 'nomasBeigums']);
    }
}
