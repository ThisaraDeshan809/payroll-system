<?php

namespace App\Models;

use App\Traits\CreatedUpdatedBy;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory,  CreatedUpdatedBy, SoftDeletes, HasUuids;
    protected $fillable = ['name', 'slug', 'icon', 'description', 'status', 'created_by', 'updated_by'];
    // protected $primaryKey = 'id';
    // public $incrementing = true;
}
