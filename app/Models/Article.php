<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    public function topic()
    {
        return $this->belongsTo(Topic::class, 'id_topic');
    }
    public function user()
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }
}
