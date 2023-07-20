<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Support\Str;
use Kyslik\ColumnSortable\Sortable;

class LanguagePage extends Model
{
    use HasFactory, Sortable;

    protected $fillable = [
        'page_id',
        'language_id',
        'page_name',
        'page_content',
        'page_meta_title',
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

    public function setSlugAttribute($value)
    {
        $slug = Str::slug($value);
        $row = static::where('id', '=', $this->id)->first();

        if (static::whereSlug($slug)->where('id', '!=', $this->id)->exists()) {
            $slug = $this->incrementSlug($slug);
        }

        $this->attributes['page_slug'] = $slug;
    }

    public function incrementSlug($slug, $count = 0)
    {
        $original = $slug;

        while (static::whereSlug($slug)->where('id', '!=', $this->id)->exists()) {
            $slug = "{$original}-" . $this->incrementSlug($slug, $count++);
        }

        return $slug;
    }

    public function sluggable(): array
    {
        return [
            'page_slug' => [
                'source' => 'page_name'
            ]
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'astrologer_id', 'user_uni_id');
    }
}
