<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class PrefersType extends Enum
{
    const STRETCHING = 'stretching';
    const GYM = 'gym';
    const PERSONAL = 'personal';
    const SINGLE = 'single';
}
