<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PasutijumaRiks extends Model
{
    protected $table = 'pasutijuma_riks';
    protected $primaryKey = 'pasutijumarikID';
    protected $fillable = ['daudzums_pozicija', 'nomasSakums', 'nomasBeigums', 'rikID', 'pasutijumsID'];
    protected $casts = ['nomasSakums' => 'date', 'nomasBeigums' => 'date'];
}
