<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Employee;
use App\Models\City;
use App\Models\State;
use App\Models\Country;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class EmployeeUser implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // dd($row);
        $user = new User([
            "admin_id" => $row["admin_id"],
            "user_uni_id" => $row["user_id"],
            "role_id" => $row["role_id"],
            "username" => $row["username"],
            "name" => $row["name"],
            "email" => $row["email"],
            "mobile" => $row["mobile"],
            "address" => $row["address"],
            "gender" => $row["gender"],
            "latitude" => $row["latitude"],
            "longitude" => $row["longitude"],
            "status" => $row["status"],
        ]);
        $cities = new City([
            "name" => $row["city"]
        ]);
        $states = new State([
            "name" => $row["state"]
        ]);
        $countries = new Country([
            "name" => $row["country"]
        ]);

        // dd($user);
        $test = [$user, $cities, $states, $countries];
        // dd($test);
        return $test;
    }
}
