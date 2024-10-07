<?php

declare(strict_types=1);

namespace BillingService\Enums;

enum SubscriptionType: string
{
    case MONTHLY = 'monthly';
    case ANNUAL = 'annnual';
}
