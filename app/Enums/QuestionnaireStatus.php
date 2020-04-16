<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class QuestionnaireStatus extends Enum
{
    const FILLED =   "filled";
    const UNFILLED =   "unfilled";
}
