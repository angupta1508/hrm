<?php

namespace App\Models;

use Illuminate\Support\Facades\URL;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Api extends Model
{
    use HasFactory;

    public static function saveapiLogs($request)
    {
        $url = URL::current();
        $apiLog =   array('request' => json_encode($request), 'url' => $url, 'response' => '');
        $api = ApiLog::create($apiLog);

        return $api;
    }
    public static function updateapiLogs($api, $result)
    {
        $api_id =   $api->id;
        $res_data   =   array('response' => json_encode($result));
        ApiLog::where('id', $api_id)->update($res_data);
    }

    public static function generateUserApiKey($user_id)
    {
        // pr($user_id);die;
        $str = rand() . $user_id;
        $api_key = sha1($str);
        $expires_at = strtotime('+24 hours');
        $api_keys_array = array('api_key' => $api_key, 'user_uni_id' => $user_id, 'expires_at' => $expires_at);

        $user = ApiKeys::where('user_uni_id', "=", $user_id)->first();
        if (!empty($user)) {
            $data_count = $user->count();
        }
        if (empty($data_count)) {
            ApiKeys::create($api_keys_array);
        } else {
            ApiKeys::where('user_uni_id', $user_id)->update($api_keys_array);
        }

        return $api_key;
    }

    public static function checkUserApiKey($user_api_key, $user_uni_id)
    {
        $data_count = ApiKeys::where([['api_key', $user_api_key], ['user_uni_id', $user_uni_id]])->count();
        if ($data_count == 0) {
            return false;
        } else {
            return true;
        }
    }
}
