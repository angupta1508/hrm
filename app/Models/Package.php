<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;
    protected $fillable = [
        'package_type',
        'package_uni_id',
        'name',
        'price',
        'trial_duration',
        'duration',
        'label',
        'description', 
        'user_limit', 
        'status',
        'trash',
    ];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];


    public static function getPackageDetail($id){
        $package = Package::where('package_uni_id',$id)->where('status',1)->first();
        return $package;
    }
}
