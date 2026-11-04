<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Concerns\HasUuid;

class Type extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasUuid;
    protected $fillable = ['name'];

    public function operations():HasMany
    {
        return $this->hasMany(Operation::class);
    }
}
