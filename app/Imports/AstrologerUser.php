<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Customer;
use App\Models\Astrologer;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AstrologerUser implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */

//ImportUser
    public function model(array $row)
    {
     //  dd($row);
        $user = new User([
            'name'             => $row['name'],
            'phone'            => $row['phone'],
            'email'            => $row['email'],
            'role_id'          => $row['role_id'],
            'user_uni_id'      => $row['astrologer_id'],
            'status'           => $row['status'],
        ]);
        $astrologer = new Astrologer([
            'display_name'        => $row['display_name'],
            'astrologer_uni_id'       => $row['astrologer_id'],
            'house_no'       => $row['house_no'],
            'street_area'       => $row['street_area'],
            'city'       => $row['city'],
            'landmark'       => $row['landmark'],
            'longitude'       => $row['longitude'],
            'latitude'       => $row['latitude'],
            'birth_date'       => $row['birth_date'],
            'gender'       => $row['gender'],
            'pin_code'       => $row['pin_code'],
            'experience'       => $row['experience'],
            'existing_website'       => $row['existing_website'],
            'existing_fees'       => $row['existing_fees'],
            'associate_template'       => $row['associate_template'],
            'writing_language'       => $row['writing_language'],
            'writing_details'       => $row['writing_details'],
            'teaching_experience'       => $row['teaching_experience'],
            'teaching_subject'       => $row['teaching_subject'],
            'teaching_year'       => $row['teaching_year'],
            'available_gadgets'       => $row['available_gadgets'],
            'astro_img'       => $row['astro_img'],
            'live_status'       => $row['live_status'],
            'video_status'       => $row['video_status'],
            'online_status'       => $row['online_status'],
            'call_status'       => $row['call_status'],
            'chat_status'       => $row['chat_status'],
            'busy_status'       => $row['busy_status'],
            'admin_percentage'       => $row['admin_percentage'],
            'livetoken'       => $row['livetoken'],
            'livechannel'       => $row['livechannel'],
            'astro_next_online_datetime'       => $row['astro_next_online_datetime'],
            'process_status'       => $row['process_status'],
            'long_biography'       => $row['long_biography'],
            'tag'       => $row['tag'],
        ]);
        $test = [$user, $astrologer];
       
        return $test; 
    }
}
