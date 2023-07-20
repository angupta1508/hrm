<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Notification extends Model
{
    use HasFactory, Sortable;
    protected $fillable = [
        'role_id',
        'admin_id',
        'title',    
        'image',
        'description',
        'status',
    ];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
      ];

    public function roles()
    {
        return $this->belongsTo(Role::class,'role_id','id');
    }
}
