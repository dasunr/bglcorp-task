<?php

declare(strict_types=1);

namespace BillingService\Classes;

use Carbon\Carbon;
use BillingService\Enums\SubscriptionType;
use BillingService\Interfaces\BillingServiceInterface;

class BillingService implements BillingServiceInterface
{
    public function generateBill(Product $product): Bill
    {

        $totalCost = $product->getTotalCost();

        $bill = new Bill(
            $totalCost,
            $product->getBillingPeriodStartDate()->toDateString(),
            $product->getBillingPeriodEndDate()->toDateString()
        );
        $billingPeriodStartDate = Carbon::now()->addDay();
        $billingPeriodEndDate = $product->getSubscriptionType() === SubscriptionType::MONTHLY ?
            $billingPeriodStartDate->copy()->addMonth() :
            $billingPeriodStartDate->copy()->addYear();

        return $bill;
    }
}
