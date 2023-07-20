<?php

namespace App\Models;

use App\Models\State;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Country extends Model
{
    use HasFactory, Sortable;

    protected $fillable = [
        'name',         
        'nicename',         
        'iso',         
        'iso3',         
        'numcode',         
        'phonecode',         
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
    
    public function states()
    {
        return $this->hasMany(State::class, 'country_id', 'id');
    }

}
