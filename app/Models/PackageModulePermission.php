<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageModulePermission extends Model
{
    use HasFactory;
    protected $table = 'package_module_permissions';
    protected $fillable = [
        'package_uni_id',
        'module_id',
        'module_name',
        'status',
        'trash',
    ];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
      ];
}
