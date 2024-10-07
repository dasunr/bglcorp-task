<?php

declare(strict_types=1);

namespace BillingService\Interfaces;

use Carbon\Carbon;

interface BillInterface
{
    public function getAmount(): float;
    public function getBillingPeriodStartDate(): Carbon;
    public function getBillingPeriodEndDate(): Carbon;
}
