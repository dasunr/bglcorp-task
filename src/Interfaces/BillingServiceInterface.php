<?php

declare(strict_types=1);

namespace BillingService\Interfaces;

use BillingService\Classes\Bill;
use BillingService\Classes\Product;

interface BillingServiceInterface
{
    public function generateBill(Product $product): Bill;
}
