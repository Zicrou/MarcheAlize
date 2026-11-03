<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Concerns\HasUuid;


class Depense extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasUuid;
    protected $fillable = ['libelle', 'montant', 'user_id', 'statut'];
}
