<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FactoryWorker extends Model
{
    use HasFactory;

    protected $table = 'factoryworkers';
    protected $guarded = [];
}
