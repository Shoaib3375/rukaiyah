<?php

namespace App\Enums;

enum UserRole: string {
    case Patient = 'patient';
    case Raqi    = 'raqi';
    case Admin   = 'admin';
}
