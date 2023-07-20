<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class DutyType extends Model
{
    use HasFactory, Sortable;
    
    protected $fillable = [
        'duty_type',         
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

    public $sortable = ['id', 'duty_type', 'status', 'created_at', 'updated_at'];

    public function user_bankes()
    {
        // return $this->hasMany(UserBanker::class, 'bank_id', 'id');
    }
}
