<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiKeys extends Model
{
    use HasFactory;
    protected $fillable = [
       'api_key',
       'user_uni_id',
       'expires_at',
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

   public function customer()
   {
       return $this->belongsTo(Customer::class,'user_uni_id','customer_uni_id');
   }
   
}
