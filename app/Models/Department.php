<?php

namespace App\Models;

use Kyslik\ColumnSortable\Sortable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Department extends Model 
{
    use HasFactory, Sortable;
    protected $fillable = [
        'department_name',         
        'admin_id',          
        'updated_by',         
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

    public $sortable = ['id', 'bank_name', 'status', 'created_at', 'updated_at'];
    
}
