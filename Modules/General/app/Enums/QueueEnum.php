<?php

namespace Modules\General\Enums;

enum QueueEnum: string
{
    case DEFAULT = 'default';
    case NOTIFICATIONS = 'notifications';
    case DELAYED_LOANS = 'delayed_loans';
}
