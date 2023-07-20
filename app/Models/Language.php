<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;


class Language extends Model
{
    use HasFactory, Sortable;
    protected $table = 'languages';
    protected $fillable = [
        'language_name',
        'language_code',
        'flag_icon',
        'system_language_status',
        'tongue_language_status',
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
    
    public function astrologers()
    {
        return $this->belongsToMany(Astrologer::class,'astrologer_languages','language_id','astrologer_id');
    }
}