<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminModulePermission extends Model
{
    use HasFactory;
    protected $table = 'admin_module_permissions';
    protected $fillable = [
        'role_id',
        'module_id',
        'operation',
        'status',
    ];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
      ];
}
