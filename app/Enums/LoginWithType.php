<?php

namespace App\Enums;

enum LoginWithType:string {

    case OTP = 'OTP';
    case PASSWORD = 'PASSWORD';
}