<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class SubscriptionStatus extends Enum
{
    const ACTIVE = 'active';
    const FROZEN = 'frozen';
    const NOT_ACTIVATED = 'not activated';
    const EXPIRED = 'expired';
}
