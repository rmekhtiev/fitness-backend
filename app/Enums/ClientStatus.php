<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class ClientStatus extends Enum
{
    const ACTIVE = 'active';
    const FROZEN = 'frozen';
    const NOT_ACTIVATED = 'not_activated';
    const EXPIRED = 'expired';
    const NO_SUBSCRIPTION = 'no_subscription';

}
