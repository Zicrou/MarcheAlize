<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;



class Produit extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = ['designation', 'nombre', 'montant', 'image'];

    public function vente(): HasMany
    {
        return $this->hasMany(Vente::class);
    }
}
