<?php

namespace App\Enums;

enum AuthAction: string
{
    case LOGIN = 'LOGIN';
    case LOGOUT = 'LOGOUT';
}
