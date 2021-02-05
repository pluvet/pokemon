<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pokemon extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'team_id',
        'name',
        'level',
        'exp'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function Team(){
        return $this->belongsTo(Team::class);
    }
}
