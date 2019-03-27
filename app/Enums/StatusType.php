<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class StatusType extends Enum
{
    const Canceled = 'Canceled';
    const Placed = 'Placed';
    const Approved = 'Approved';
    const Shipped = 'Shipped';
    const Received = 'Received';
}
