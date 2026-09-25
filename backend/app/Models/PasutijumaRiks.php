<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PasutijumaRiks extends Model
{
    protected $table = 'pasutijuma_riks';
    protected $primaryKey = 'pasutijumarikID';
    protected $fillable = ['daudzums_pozicija', 'nomassakums', 'nomasbeigums', 'rikID', 'pasutijumsID'];
    protected $casts = ['nomassakums' => 'date', 'nomasbeigums' => 'date'];


    public function pasutijums()
    {
        return $this->belongsTo(Pasutijums::class, 'pasutijumsID');
    }
}
