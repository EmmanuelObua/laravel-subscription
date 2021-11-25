<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notified extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $table = 'notified';
}
