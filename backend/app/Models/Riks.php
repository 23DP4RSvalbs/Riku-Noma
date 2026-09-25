<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Riks extends Model
{
    protected $table = 'riks';
    protected $primaryKey = 'rikID';
    protected $fillable = ['nosaukums','apraksts','daudzums','cenadiena','foto','kods','zimols','nomasilgumsmin','nomasilgumsmax','redzamsKatalogs','statuss','kategorijaID'];
    protected $casts = ['cenadiena' => 'decimal:2', 'redzamsKatalogs' => 'boolean'];

    public function kategorija() 
    { 
        return $this->belongsTo(Kategorija::class, 'kategorijaID'); 
    }
    
    public function pasutijumi()
    {
        return $this->belongsToMany(Pasutijums::class, 'pasutijuma_riks', 'rikID', 'pasutijumsID')
                    ->withPivot(['daudzums_pozicija', 'nomasSakums', 'nomasBeigums']);
    }
}
