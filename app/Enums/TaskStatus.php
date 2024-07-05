<?php

namespace App\Enums;

use App\Enums\Traits\EnumHelper;

enum TaskStatus: string
{
    use EnumHelper;

    case NEW = 'Новые';
    case IN_PROGRESS = 'В процессе';
    case IN_REVIEW = 'На этапе согласования';
    case DONE = 'Готово';
    case ARCHIVE = 'Архив';
}
