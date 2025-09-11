<?php

namespace App\Enums;

enum BookTicketBookingSourceEnum: int
{
    case PORTAL = 1;
    case BACKEND_PORTAL = 2;
    case API = 3;

    public function getUIClass(){
        return match($this) {
            self::PORTAL=> 'badge bg-warning',
            self::BACKEND_PORTAL => 'badge bg-success',
            self::API => 'badge bg-danger',
            
        };
    }
    public function getDisplayName(){
        return match($this) {
            self::PORTAL=> 'Portal',
            self::BACKEND_PORTAL => 'Backend Portal',
            self::API => 'API',
        };
    }
}
