<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Location extends Model
{
    use HasFactory, Sortable;
    protected $fillable = [
        'admin_id',
        'location_name',
        'latitude',
        'longitude',
        'ip',
        'acceptable_range',
        'weekly_holiday',
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

    public $sortable = ['id', 'admin_id', 'location_name', 'latitude', 'longitude', 'acceptable_range', 'weekly_holiday', 'status', 'created_at', 'updated_at'];

    public function user()
    {
        return $this->belongsToMany(User::class, 'location_users', 'location_id', 'user_id');
    }

    public static function checkInGeo($request)
    {
        $location = getLocationUser($request);
        $lat2 = $request->lat;
        $long2 = $request->long;
        foreach ($location as $key => $value) {
            $lat1 = $value->latitude;
            $lon1 = $value->longitude;
            $meters = self::distance($lat1, $lon1, $lat2, $long2);
            if ($value->acceptable_range > $meters) {
                return true;
            }
        }
        return false;
    }

    public static function distance($lat1, $lon1, $lat2, $long2)
    {

        $theta = $lon1 - $long2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;

        $kilometers = ($miles * 1.609344);
        if(!empty($kilometers)){
            return ($kilometers * 1000);
        }
        else{
            return 0;
        }
    }
}
