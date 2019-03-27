<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class UserType extends Enum
{
    const Admin = 'Admin';
    const Customer = 'Customer';
    const StoreUser = 'Store user';
}
