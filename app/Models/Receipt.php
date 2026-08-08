<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    use HasFactory;

    protected $casts = [
        'scanned_at' => 'datetime',
        'deadline_at' => 'datetime',
    ];

    protected $fillable = [
        'expedition_id',
        'tracking_number',
        'order_id',
        'product_name',
        'deadline_at',
        'quantity',
        'status',
        'scanned_at',
    ];

    public function expedition()
    {
        return $this->belongsTo(Expedition::class);
    }

    public function scanLogs()
    {
        return $this->hasMany(ScanLog::class);
    }
}
