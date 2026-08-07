<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expedition extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
    ];

    public function receipts()
    {
        return $this->hasMany(Receipt::class);
    }
}
