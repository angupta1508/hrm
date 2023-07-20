<?php

namespace App\Models;

use Kyslik\ColumnSortable\Sortable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Mood extends Model
{ 
    use HasFactory, Sortable;
    protected $fillable = [
        'admin_id',
        'user_id', 
        'type_id',          
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

    public $sortable = ['id','admin_id', 'user_id', 'status', 'created_at', 'updated_at'];

    public function mood()
    {
        return $this->belongsTo(MoodType::class,'type_id','id');    //belongsTo() method represents the relationship between the two models.type_id column of the current model to 'id' column of the MoodType Model.
    }
 
}
