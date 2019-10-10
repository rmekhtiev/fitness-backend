<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class IssueType extends Enum
{
    const PENDING = 'pending';
    const IN_WORK = 'in-work';
    const READY = 'ready';
}
