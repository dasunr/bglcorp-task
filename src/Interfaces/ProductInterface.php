<?php

declare(strict_types=1);

namespace BillingService\Interfaces;

use Carbon\Carbon;
use BillingService\Enums\SubscriptionType;

interface ProductInterface
{
    public function getSubscriptionType(): SubscriptionType;
    public function getRate(): float;
    public function getStartDate(): Carbon;
    public function getBillingPeriodStartDate(): Carbon;
    public function getBillingPeriodEndDate(): Carbon;
}
