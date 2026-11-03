<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Concerns\HasUuid;



class Produit extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasUuid;
    protected $fillable = ['designation', 'nombre', 'montant', 'image', 'user_id'];

    public function vente(): HasMany
    {
        return $this->hasMany(Vente::class);
    }
}
