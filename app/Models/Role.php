<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Support\Str;

class Role extends Model
{
    use HasFactory, Sluggable, Sortable;

    /** 
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'admin_id',
        'name',
        'slug',
        'role_type',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [];
    
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    public $sortable = ['name', 'role_type', 'status', 'created_at', 'updated_at'];

    public function setSlugAttribute($value) {
        $slug = Str::slug($value);
        $row = static::where('id', '=', $this->id)->first();

        if (static::whereSlug($slug)->where('id', '!=', $this->id)->exists()) {
            $slug = $this->incrementSlug($slug);
        }
        
        $this->attributes['slug'] = $slug;
    }

    public function incrementSlug($slug, $count=0) {
        $original = $slug;
        
        while (static::whereSlug($slug)->where('id', '!=', $this->id)->exists()) {
            $slug = "{$original}-" . $this->incrementSlug($slug, $count++);
        }
    
        return $slug;
    
    }
    
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    public function user()
    {
        return $this->hasMany(Role::class);
    }

    public function notification()
    {
        return $this->hasMany(Notification::class);
    }
}
