<?php

namespace App\Models;

use Kyslik\ColumnSortable\Sortable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SalarySettlement extends Model
{
    use HasFactory, Sortable;
    protected $fillable = [
        'settlement_month',
        'user_id',
        'type',
        'amount',
        'description',
        'status',
    ];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    public static function getUserSettlementData($month,$user_id){
        $cr = SalarySettlement::where('settlement_month',$month)->where('user_id',$user_id)->where('type','cr')->sum('amount');
        $dr = SalarySettlement::where('settlement_month',$month)->where('user_id',$user_id)->where('type','dr')->sum('amount');
        $arry = (object)array(
            'cr' => $cr,
            'dr' => $dr,
        );
        return $arry;
    }
}
