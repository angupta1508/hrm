<?php

namespace App\Models;
 
use App\Models\UserBanker;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
 
class Bank extends Model
{
    use HasFactory, Sortable;
    protected $fillable = [
        'admin_id',
        'bank_name', 
        'branch_name',          
        'bank_logo',     
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
 
    public function user_bankes()
    {
        return $this->hasMany(UserBanker::class, 'bank_id', 'id');
    }
   
  
}
