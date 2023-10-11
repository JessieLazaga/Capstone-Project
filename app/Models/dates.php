<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class dates extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'chart_Date',
        'product_id',
    ];
}
