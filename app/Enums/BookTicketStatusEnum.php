<?php

namespace App\Enums;

enum BookTicketStatusEnum : int
{
    case PENDING = 1;
    case CONFIRMED = 2;
    case CANCELLED = 3;
    case REFUNDED = 4;
    case TEST = 5;
    case CANCELLATION_PENDING = 6;

    public function getUIClass(){
        return match($this) {
            self::TEST,self::PENDING => 'badge bg-warning',
            self::CONFIRMED => 'badge bg-success',
            self::CANCELLED,self::CANCELLATION_PENDING, => 'badge bg-danger',
            self::REFUNDED => 'badge bg-primary',
        };
    }
    public function getDisplayName(){
        return match($this) {
            self::TEST => 'Test',
            self::PENDING => 'Pending',
            self::CONFIRMED => 'Confirmed',
            self::CANCELLED => 'Cancelled',
            self::CANCELLATION_PENDING, => 'Cancellation Pending',
            self::REFUNDED => 'Refunded',
        };
    }
}
