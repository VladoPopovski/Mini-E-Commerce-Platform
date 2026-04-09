<?php

namespace App\Enums;

enum UserRole: string
{
    case BUYER  = 'buyer';
    case VENDOR = 'vendor';
    case ADMIN  = 'admin';
}
