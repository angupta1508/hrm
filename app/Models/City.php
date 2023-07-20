<?php

namespace App\Models;

use Kyslik\ColumnSortable\Sortable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class City extends Model
{
    use HasFactory, Sortable;

    protected $fillable = [
        'name',         
        'state_id',         
        'country_id',         
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

    public $sortable = ['id', 'name', 'status', 'created_at', 'updated_at'];

    public function state()
    {
        return $this->belongsTo(State::class, 'state_id', 'id');
    }


}
