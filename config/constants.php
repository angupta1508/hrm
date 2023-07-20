<?php

return [
    'bank_image_path' => 'uploads/banks/',
    'user_image_path' => 'uploads/users/',
    'notice_image_path' => 'uploads/notices/',
    'language_image_path' => 'uploads/languages/',
    'department_image_path' => 'uploads/departments/',
    'notification_image_path' => 'uploads/notifications/',
    'withdrawal_requests_image_path' => 'uploads/withdrawal_requests/',
    'setting_image_path' => 'uploads/setting/',
    'attendance_image_path' => 'uploads/attendance/',
    'leave_image_path' => 'uploads/leave/',
    'request_hard_copy_image_path' => 'uploads/leave/',
    'leave_request_hard_copy_image_path' => 'uploads/Leave_request_hard_copy/',
    'default_user_image_path' => 'assets/img/user.png',
    'default_image_path' => 'assets/img/default.jpg',

    // CSV Sample Path
    'default_user_csv' => 'assets/file/users.csv',

    'default_otp_code' => '666331',
    'default_company_lang' => 'en',
    'default_country' => '99',
    'default_state' => '33',
    "default_page_limit" => 15,
    "api_page_limit" => 10,
    'small_image_width' => '300',
    'small_image_height' => '300',
    'icon_image_width' => '100',
    'icon_image_height' => '100',

    //currency symbol
    'indian_currency_symbol' => '₹',

    "superadmin_role_id" => 1,
    "superadmin_staff_role_id" => 2,
    "admin_role_id" => 3,
    "admin_staff_role_id" => 4,
    "employee_role_id" => 5,
    "role_slug_admin" => "admin",
    "role_slug_superadmin" => "superadmin",

    "role_slug_admin-staff" => "admin-staff",
    "role_slug_superadmin-staff" => "superadmin-staff",
    "role_slug_employee" => "employee",
    "role_type_superadmin" => "Superadmin",
    "role_type_admin" => "Admin",
    "role_type_employee" => "User",
    "User" => "User",

    "no_change_msg" => "Please keep this the same if you do not need to make any changes.",
    "payment_type" => ['cr' => 'Credit', 'dr' => 'Debit'],
    "default_status" => ['1' => 'Active', '0' => 'Inactive'],
    "miss_punch_status" => ['1' => 'In Time', '0' => 'Out Time'],
    "default_leave_status" => ['0' => 'Pending', '1' => 'Approve', '2' => 'Cancel'],
    "default_attendance_status" => ['A' => 'A', 'P' => 'p', 'HD' => 'HD', 'MP' => 'MP', 'L' => 'L', 'WO' => 'WO', 'HO' => 'HO', 'HL' => 'HL'],
    "weekend" => ['4' => '4', '5' => '5', '6' => '6'],
    "default_weekly_holiday" => ['Sunday' => 'Sunday', 'Monday' => 'Monday', 'Tuesday' => 'Tuesday', 'Wednesday' => 'Wednesday', 'Thursday' => 'Thursday', 'Friday' => 'Friday', 'Saturday' => 'Saturday'],
    "gender_list" => ['Male' => 'Male', 'Female' => 'Female',  'Other' => 'Other'],
    "account_type" => ['Saving' => 'Saving', 'Current' => 'Current'],
    "default_attendance_type" => ['auto' => 'auto', 'manual' => 'manual'],
    "default_mood_type" => ['Very Sad' => 'Very Sad', 'Sad' => 'Sad', 'Normal' => 'Normal', 'Good' => 'Good', 'Excellent' => 'Excellent'],
    "default_shift_type" => ['Day' => 'Day', 'Night' => 'Night'],
    "package_label" => ['Silver' => 'Silver', 'Gold' => 'Gold', 'PLATINUM' => 'PLATINUM'],
    "default_leave_status" => ['1' => 'Approve', '0' => 'Pending', '2' => 'Cancel'],
    "month_name" => [
        '1' => 'January', '2' => 'February', '3' => 'March', '4' => 'April', '5' => 'May', '6' => 'June', '7' => 'July', '8' => 'August', '9' => 'September', '10' => 'October', '11' => 'November', '12' => 'December'
    ],

    "salary_based_on" => ['0' => 'Per Month', '1' => 'Per Hours'],

    "esic_persent" => '0.75',
    "pf_month"      => '12',
    "autoLeaveApplyday"  => '20',

    "night_shift_start_time" => '20:00:00',
    "night_shift_middle_time" => '00:00:00',
    "night_shift_end_time" => '08:00:00',

];
