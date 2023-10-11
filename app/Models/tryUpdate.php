<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tryUpdate extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable =   [
        'record',
    ];
}
