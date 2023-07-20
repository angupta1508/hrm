<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Vendor;
use App\Models\Customer;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class VendorUser implements ToModel, WithHeadingRow
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
            'name'        => $row['name'],
            'phone'       => $row['phone'],
            'email'       => $row['email'],
            'user_uni_id'       => $row['vendor_id'],
            'status'       => $row['status'],
        ]);
        $vendors = new Vendor([
            'vendor_uni_id'        => $row['vendor_id'],
            'firm_name'       => $row['firm_name'],
            'country'       => $row['country_id'],
            'state'       => $row['state_id'],
            'city'       => $row['city_id'],
            'address'       => $row['address'],
            'pin_code'       => $row['pin_code'],
            'gst_no'       => $row['gst_no'],
            'term'       => $row['term'],
        ]);
        $test = [$user, $vendors];
        return $test; 
    }
}
