<?php

namespace App\Enums;

enum CustomerTypeEnum : int
{
    case AGENT = 1;
    case CUSTOMER = 2;

    public function getDisplayName(){
        return match($this) {
            self::AGENT => 'Agent',
            self::CUSTOMER => 'Customer',
        };
    }

    public function getRoleName()
    {
        return match($this) {
            self::AGENT => 'Agent',
            self::CUSTOMER => 'b2c',
        };
    }
}
