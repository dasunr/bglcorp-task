<?php

declare(strict_types=1);

namespace BillingService\Classes;

use Carbon\Carbon;
use BillingService\Interfaces\BillInterface;

class Bill implements BillInterface
{
    private float $amount;
    private Carbon $billingPeriodStartDate;
    private Carbon $billingPeriodEndDate;

    public function __construct(float $amount, string $billingPeriodStartDate, string $billingPeriodEndDate)
    {
        $this->amount = $amount;
        $this->billingPeriodStartDate = new Carbon($billingPeriodStartDate);
        $this->billingPeriodEndDate = new Carbon($billingPeriodEndDate);
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getBillingPeriodStartDate(): Carbon
    {
        return $this->billingPeriodStartDate;
    }

    public function getBillingPeriodEndDate(): Carbon
    {
        return $this->billingPeriodEndDate;
    }
}
