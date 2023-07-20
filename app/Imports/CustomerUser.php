<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Customer;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CustomerUser implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */

//ImportUser
    public function model(array $row)
    {
        $user = new User([
            'name'        => $row['name'],
            'phone'       => $row['phone'],
            'email'       => $row['email'],
            'user_uni_id'       => $row['customer_id'],
            'status'       => $row['status'],
            'created_at'       => $row['created_at'],
            'updated_at'       => $row['updated_at'],
        ]);
        $customer = new Customer([
            'customer_uni_id'        => $row['customer_id'],
            'country'       => $row['country'],
            'state'       => $row['state'],
            'city'       => $row['city'],
            'birth_date'       => $row['birth_date'],
            'gender'       => $row['gender'],
            'customer_img'       => $row['customer_img'],
            'address'       => $row['address'],
            'longitude'       => $row['longitude'],
            'birth_place'       => $row['birth_place'],
            'birth_time'       => $row['birth_time'],
            'latitude'       => $row['latitude'],
        ]);
        $test = [$user, $customer];
        return $test; 
    }
}
