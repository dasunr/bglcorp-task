<?php

declare(strict_types=1);

namespace BillingService\Classes;

use Carbon\Carbon;
use BillingService\Enums\SubscriptionType;
use BillingService\Interfaces\ProductInterface;
use InvalidArgumentException;

class Product implements ProductInterface
{
    private SubscriptionType $subscriptionType;
    private float $monthlyRate;
    private int|null $usage;
    private Carbon $startDate;
    private Carbon $billingPeriodStartDate;
    private Carbon $billingPeriodEndDate;

    public function __construct(
        SubscriptionType $subscriptionType,
        float $rate,
        string $startDate,
        string $billingPeriodStartDate,
        string $billingPeriodEndDate,
        int $usage = null,
    ) {
        if ($rate <= 0) {
            throw new InvalidArgumentException('Rate must be a positive value');
        }
        $startDateCarbon = new Carbon($startDate);
        if ($startDateCarbon->isFuture()) {
            throw new InvalidArgumentException('Start date must be in the future');
        }

        $billingPeriodStartDateCarbon = new Carbon($billingPeriodStartDate);
        $billingPeriodEndDateCarbon = new Carbon($billingPeriodEndDate);
        if (
            $billingPeriodStartDateCarbon->isPast() &&
            $billingPeriodEndDateCarbon->isPast() &&
            $billingPeriodEndDateCarbon->isBefore($billingPeriodStartDateCarbon)
        ) {
            throw new InvalidArgumentException('Billing period dates are invalid');
        }

        $this->subscriptionType = $subscriptionType;
        $this->monthlyRate = $rate;
        $this->usage = $usage;
        $this->startDate = $startDateCarbon;
        $this->billingPeriodStartDate = $billingPeriodStartDateCarbon;
        $this->billingPeriodEndDate = $billingPeriodEndDateCarbon;
    }

    public function getSubscriptionType(): SubscriptionType
    {
        return $this->subscriptionType;
    }

    public function getRate(): float
    {
        return $this->monthlyRate;
    }

    public function getTotalCost(): float
    {
        if (
            $this->subscriptionType !== SubscriptionType::MONTHLY &&
            $this->subscriptionType !== SubscriptionType::ANNUAL
        ) {
            throw new InvalidArgumentException('Invalid subscription type');
        }

        if ($this->subscriptionType === SubscriptionType::MONTHLY) {
            return $this->usage === null ? $this->monthlyRate : $this->monthlyRate * $this->usage;
        }

        if ($this->subscriptionType === SubscriptionType::ANNUAL) {
            return $this->usage === null ? $this->monthlyRate * 12 : $this->monthlyRate * $this->usage;
        }
    }

    public function getStartDate(): Carbon
    {
        return $this->startDate;
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
