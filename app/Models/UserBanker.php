<?php

namespace App\Models;

use App\Models\Bank;
use App\Models\User;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserBanker extends Model
{
    use HasFactory, Sortable;
    protected $fillable = [
        'user_id',
        'gateway_fund_id',
        'bank_id',
        'admin_id',
        'account_no',
        'account_type',
        'ifsc_code',
        'account_name',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function bank()
    {
        return $this->belongsTo(Bank::class, 'bank_id', 'id');
    }
}
