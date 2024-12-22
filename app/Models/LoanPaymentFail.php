<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanPaymentFail extends Model
{
    use HasFactory;

    protected $table = 'loan_payment_fails';
    protected $guarded = [];
}
