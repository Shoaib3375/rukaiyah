<?php

namespace App\Enums;

enum NoteType: string {
    case RuqyahLog      = 'ruqyah_log';
    case Observation    = 'observation';
    case Recommendation = 'recommendation';
    case Chat           = 'chat';
}
