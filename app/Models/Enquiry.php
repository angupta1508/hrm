<?php

namespace App\Models;

use Kyslik\ColumnSortable\Sortable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Enquiry extends Model
{
    use HasFactory, Sortable;
    protected $fillable = [
        'gateway_name',         
        'gateway_txn_charges',         
        'description',         
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

    public $sortable = ['id', 'gateway_name', 'gateway_txn_charges', 'description', 'status', 'created_at', 'updated_at'];

}
