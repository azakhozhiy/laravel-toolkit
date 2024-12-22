<?php

declare(strict_types=1);

namespace AZakhozhiy\Laravel\Toolkit\Enum;

enum StatusTypeEnum: string
{
    case SUCCESS = 'SUCCESS';
    case INFO = 'INFO';
    case WARNING = 'WARNING';
    case ERROR = 'ERROR';
    case BLACK = 'BLACK';
    case WHITE = 'WHITE';
    case PURPLE = 'PURPLE';
    case GREEN = 'GREEN';
    case DEFAULT = 'DEFAULT';
}
