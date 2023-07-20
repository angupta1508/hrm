<?php

namespace App\Models;

use Kyslik\ColumnSortable\Sortable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Holiday extends Model
{
    use HasFactory, Sortable;
    protected $fillable = [
        'holiday_name',
        'date',
        'holiday_type',
        'status',
        'admin_id'


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

    // public $sortable = ['id', 'holiday_name','date', 'holiday_type','status', 'created_at', 'updated_at'];

}
