<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kategorija extends Model
{
    protected $table = 'kategorija';
    protected $primaryKey = 'kategorijaID';
    protected $fillable = ['nosaukums', 'apraksts'];

    public function riki() { return $this->hasMany(Riks::class, 'kategorijaID'); }
}
