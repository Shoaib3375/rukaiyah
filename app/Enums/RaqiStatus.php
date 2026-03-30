<?php

namespace App\Enums;

enum RaqiStatus: string {
    case Pending   = 'pending';
    case Active    = 'active';
    case Suspended = 'suspended';
}
