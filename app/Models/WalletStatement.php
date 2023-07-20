<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletStatement extends Model
{
    use HasFactory; 
    protected $fillable = [
        'user_id',
        'name',
        'email',
        'mobile',
        'payment_id',
        'transation_type',
        'opening_wallet_balance',
        'amount_type',
        'amount',
        'reference_number',
        'narration',
        'status',
    ];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

}
