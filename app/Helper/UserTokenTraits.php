<?php

namespace App\Helper;

use App\Models\UserToken;
use Illuminate\Support\Str;

trait UserTokenTraits
{

    function createWebToken()
    {
        $app_token =  hash('sha256',  Str::random(60));

        $token = UserToken::where('user_id', $this->id)->first();

        if (!$token) {
            $token = new UserToken();
        }

        $token->user_id = $this->id;
        $token->web_access_token = $app_token;
        $token->save();
        return $app_token;
    }


    function createAppToken($device_token, $device_type)
    {

        $app_token =  hash('sha256',  Str::random(60));

        // clean if other user login with same device token
        $existDeviceToken =  UserToken::where('device_token', $device_token)->first();

        if( $existDeviceToken) {
            $existDeviceToken->device_token = "";
            $existDeviceToken->device_type = "";
            $existDeviceToken->app_access_token = "";
            $existDeviceToken->save();
        }
        
        $token = UserToken::where('user_id', $this->id)->first();

        if (!$token) {
            $token = new UserToken();
        }

        $token->user_id = $this->id;
        $token->device_type = strtoupper($device_type);
        $token->device_token = $device_token;
        $token->app_access_token = $app_token;
        $token->save();

        return  $app_token;
    }
}