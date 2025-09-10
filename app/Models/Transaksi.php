<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function masseur()
    {
        return $this->belongsTo(\App\Models\Admin::class, 'masseur_id');
    }

    public function customer()
    {
        return $this->belongsTo(\App\Models\Admin::class, 'user_order_id');
    }
}
