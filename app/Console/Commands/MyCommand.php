<?php

namespace App\Console\Commands;

use Exception;
use App\Models\User;
use App\Mail\NotifyMail;
use App\Models\SmsTemplate;
use App\Models\EmailTemplates;
use Illuminate\Console\Command;
use App\Models\UserNotification;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;

class MyCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:name';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public static function SendNotification($user_id, $email_template_code = '', $sms_template_code = '', $other = [])
    {
        $userData = User::getUserDetails($user_id, 'emp');
        $emailtemplate = EmailTemplates::where('template_code', $email_template_code)->first();
        $smstemplate = SmsTemplate::where('template_code', $sms_template_code)->first();
        $result = (object)[];
        if (!empty($email_template_code) && !empty($userData->email) && !empty($userData->name) && !empty($emailtemplate->content)) {
            $user = (object)array(
                'name' => $userData->name,
                'email' => $userData->email,
            );
            $emailData = (object)array(
                'title' => $emailtemplate->title,
                'content' => $emailtemplate->content,
                'template_code' => $emailtemplate->template_code,
            );

            $result = self::sendMail($emailData, $user, $other);
        }
        if (!empty($sms_template_code) &&  !empty(Config::get('sms_live_mode')) && !empty($smstemplate->content)) {
            $message = self::templateMessage($smstemplate->content, $userData, $other);
            $result = self::send_sms($userData->phone, $message);
        }
    }

    public static function templateMessage($data, $user, $other = [])
    {
        $emailFindReplace = array(
            '*WEBSITE_URL*'                 =>  URL::to('/'),
            '*APP_URL*'                     =>  Config::get('app_url'),
            '*COMPANY_NAME*'                =>  Config::get('company_name'),
            '*USER_EMAIL*'                  =>  !empty($user->email) ? $user->email : '',
            '*USER_NAME*'                   =>  !empty($user->name) ? $user->name : '',
            '*OTP_CODE*'                    =>  !empty($other['otp_code']) ? $other['otp_code'] : '',
            '*USER*'                        =>  !empty($other['user']) ? $other['user'] : '',
            '*AUTHORISED_PERSON*'           =>  !empty($other['authorised_person_id']) ? $other['authorised_person_id'] : '',
            '*DATE*'                        =>  !empty($other['date']) ? $other['date'] : '',
            '*WEBSITE_LOGO*'                =>  Config::get('logo'),
            '*SUPPORT_EMAIL*'               =>  Config::get('email'),
            '*CONTACT_EMAIL*'               =>  Config::get('mobile_no'),
        );
        $message = strtr($data, $emailFindReplace);
        return $message;
    }

    public static function sendMail($template, $user, $other = [])
    {
        $result = false;
        if (!empty($template)) {
            $subject = self::templateMessage($template->title, $user, $other);
            $body = self::templateMessage($template->content, $user, $other);

            $mailData = [
                'action' => $template->title,
                'subject' => $subject,
                'body' => $body,
            ];
            $mailData['template_code'] = $template->template_code;
            $mailData['name'] = $user->name;
            $mailData['email'] = $user->email;

            Config::set('mail.mailers.smtp.host', Config::get('smtp_host'));
            Config::set('mail.mailers.smtp.username', Config::get('smtp_username'));
            Config::set('mail.mailers.smtp.password', Config::get('smtp_password'));
            Config::set('mail.mailers.smtp.port', Config::get('smtp_port'));
            Config::set('mail.from.address', Config::get('smtp_username'));
            Config::set('mail.from.name', Config::get('company_name'));
            // dd(Config::get('mail'));
            try {
                $mail = Mail::to($mailData['email'])->send(new NotifyMail($mailData));
                $result = (object)array(
                    'status' => 1,
                    'msg' => $mail,
                );
            } catch (Exception $e) {
                if (!empty($e)) {
                    $result = (object)array(
                        'status' => 0,
                        'msg' => $e,
                    );
                }
            }
        }
        return $result;
    }

    public static function send_sms($c_number, $c_message)
    {
        $c_number = str_replace('+91', '', $c_number);
        $fields = [
            "route" => Config::get('route'),
            "sender_id" => Config::get('sender_id'),
            "message" => $c_message,
            "language" => "english",
            "flash" => 0,
            "numbers" => $c_number,
        ];

        $headers = array(
            'Authorization: ' . Config::get('auth_key'),
            'Content-Type: application/json',
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, Config::get('sms_url'));
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $response = curl_exec($ch);
        $result = (object)array(
            'status' => 1,
            'msg' => $response,
        );
        if ($response === false) {
            $result = (object)array(
                'status' => 0,
                'msg' => curl_error($ch),
            );
        }
        curl_close($ch);
        return $result;
    }

    public static function fireBaseNotification($user_id, $title, $message)
    {
        $userData = User::getUserDetails($user_id, 'emp');
        $array = array(
            'admin_id' => $userData->admin_id,
            'role_id' => $userData->role_id,
            'user_id' => $userData->user_id,
            'title' => $title,
            'description' => $message,
        );
        UserNotification::create($array);
        $registatoin_ids = array();
        if (!empty($userData->user_fcm_token)) {
            $registatoin_ids[] = $userData->user_fcm_token;
        }
        $arry = [
            'title' => $title,
            'image' => $userData->profile_image,
            'description' => $message,
            'token' => '',
            'channelName' => '',
            'user_uni_id' => '',
            'start_tiame' => '',
            'duration' => '',
            'ctype' => '',
            'start_time' => '',
            'duration' => ''
        ];
        $arry['type'] = 'android';
        $arry['chunk'] = $registatoin_ids;
        if (!empty($registatoin_ids)) {
            pushNotification($arry);
        }
        return true;
    }


    public static function converNumber($number)
    {
        if ($number < 0) {
            $number = - ($number);
        }
        if (($number < 0) || ($number > 9999999999999)) {
            throw new Exception("Number is out of range");
        }

        $Gn = floor($number / 1000000);
        /* Millions (giga) */
        $number -= $Gn * 1000000;
        $kn = floor($number / 1000);
        /* Thousands (kilo) */
        $number -= $kn * 1000;
        $Hn = floor($number / 100);
        /* Hundreds (hecto) */
        $number -= $Hn * 100;
        $Dn = floor($number / 10);
        /* Tens (deca) */
        $n = $number % 10;
        /* Ones */

        $res = "";

        if ($Gn) {
            $res .= self::converNumber($Gn) .  "Million";
        }

        if ($kn) {
            $res .= (empty($res) ? "" : " ") . self::converNumber($kn) . " Thousand";
        }

        if ($Hn) {
            $res .= (empty($res) ? "" : " ") . self::converNumber($Hn) . " Hundred";
        }

        $ones = array("", "One", "Two", "Three", "Four", "Five", "Six", "Seven", "Eight", "Nine", "Ten", "Eleven", "Twelve", "Thirteen", "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eightteen", "Nineteen");
        $tens = array("", "", "Twenty", "Thirty", "Fourty", "Fifty", "Sixty", "Seventy", "Eigthy", "Ninety");

        if ($Dn || $n) {
            if (!empty($res)) {
                $res .= " and ";
            }

            if ($Dn < 2) {
                $res .= $ones[$Dn * 10 + $n];
            } else {
                $res .= $tens[$Dn];

                if ($n) {
                    $res .= "-" . $ones[$n];
                }
            }
        }

        if (empty($res)) {
            $res = "zero";
        }

        return $res;
    }
}
