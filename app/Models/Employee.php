<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $table = 'employees';
    protected $primaryKey = 'id';
    protected $guarded = [];

    public function location()
    {
        return $this->belongsTo(Location::class, 'loacation_id');
    }

    public function business()
    {
        return $this->belongsTo(Business::class, 'business_id');
    }
}
