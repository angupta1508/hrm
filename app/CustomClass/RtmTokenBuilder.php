<?php
namespace App\CustomClass;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use App\t_log;
use App\User;
class RtmTokenBuilder
{
    const RoleRtmUser = 1;
    
    public static function buildToken($appID, $appCertificate, $userAccount, $role, $privilegeExpireTs)
    {
        $token = AccessToken::init($appID, $appCertificate, $userAccount, "");
        $Privileges = AccessToken::Privileges;
        $token->addPrivilege($Privileges["kRtmLogin"], $privilegeExpireTs);
        
        return $token->build();
    }
}
