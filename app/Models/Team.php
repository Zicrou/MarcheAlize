<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\HasUuid;
class Team extends Model
{
    use HasUuid;
    protected $table = 'teams';
    protected $fillable = [
        'name',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function operations()
    {
        return $this->hasMany(Operation::class);
    }
}
