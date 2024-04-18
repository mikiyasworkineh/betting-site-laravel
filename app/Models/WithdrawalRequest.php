<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WithdrawalRequest extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'amount',
        'status',
        'reference', // Add reference attribute
    ];

    // Optionally, define validation rules
    protected $rules = [
        'reference' => 'string|max:255', // Example validation rule
    ];
}
