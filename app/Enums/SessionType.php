<?php

namespace App\Enums;

enum SessionType: string {
    case Video    = 'video';
    case Chat     = 'chat';
    case Phone    = 'phone';
    case InPerson = 'in_person';
}
