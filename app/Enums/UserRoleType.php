<?php

namespace App\Enums;

enum UserRoleType : int
{
    case AGENT = 1;
    case CUSTOMER = 2;
    case DISTRIBUTOR = 3;
    case SUPPLIER = 4;
    case NEW_STAFF = 5;
    case MARKETING = 6;
    case ACCOUNTS = 7;
    case ADMINISTRATOR = 8;
    case MANAGER = 9;
    case STAFF = 10;

    public function getDisplayName(){
        return match($this) {
            self::AGENT => 'Agent',
            self::CUSTOMER => 'Customer',
            self::DISTRIBUTOR => 'Distributor',
            self::SUPPLIER => 'Supplier',
            self::NEW_STAFF => 'New Staff',
            self::MARKETING => 'Marketing',
            self::ACCOUNTS => 'Accounts',
            self::ADMINISTRATOR => 'Administrator',
            self::MANAGER => 'Manager',
            self::STAFF => 'Staff',
        };
    }

    public function getRoleName()
    {
        return match($this) {
            self::AGENT => 'Agent',
            self::CUSTOMER => 'b2c',
            self::DISTRIBUTOR => 'Distributor',
            self::SUPPLIER => 'Supplier',
            self::NEW_STAFF => 'New Staff',
            self::MARKETING => 'Marketing',
            self::ACCOUNTS => 'Accounts',   
            self::ADMINISTRATOR => 'Administrator',
            self::MANAGER => 'Manager',
            self::STAFF => 'Staff',
        };
    }   
}
