<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'user_id',
        'name',
        'color'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function pokemons(){

        return $this->hasMany(pokemon::class);

    }
}
