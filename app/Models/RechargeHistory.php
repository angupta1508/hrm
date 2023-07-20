<?php

namespace App\Models;

use Kyslik\ColumnSortable\Sortable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RechargeHistory extends Model
{
    use HasFactory , Sortable;
    protected $table = 'recharge_histories';
    protected $fillable = [
        'recharge_uni_id',
        'amount',
        'package_uni_id',
        'package_type',
        'admin_id',
        'order_id',
        'razorpay_id',
        'pay_method',
        'status',
    ];
}
