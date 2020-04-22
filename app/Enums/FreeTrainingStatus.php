<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class FreeTrainingStatus extends Enum
{
    const USED = 'used';
    const EXPIRED = 'expired';
    const AVAILABLE = 'available';
    const NOTSCHEDULED  = 'not_scheduled';
}
