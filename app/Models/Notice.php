<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Notice extends Model
{
    use HasFactory, Sortable;
    protected $fillable = [
        'title',
        'type',
        'date',
        'attachment',
        'description',
        'status',
        'admin_id'
    ];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];
}
